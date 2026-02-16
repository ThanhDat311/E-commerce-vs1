<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\AiFeatureStore;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Payment\PaymentFactory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $orderRepository;

    protected $productRepository;

    protected $cartService;

    protected $riskService;

    protected $paymentFactory;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        CartService $cartService,
        RiskManagementService $riskService,
        PaymentFactory $paymentFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
        $this->riskService = $riskService;
        $this->paymentFactory = $paymentFactory;
    }

    public function processCheckout(array $customerData, ?int $userId = null)
    {
        // 1. Lấy dữ liệu giỏ hàng live từ DB để đảm bảo giá cả chính xác
        $cartData = $this->cartService->getCartDetails();
        $cartItems = $cartData['cartItems'];

        if (empty($cartItems)) {
            throw new Exception('Cart is empty');
        }

        // --- CHECK RỦI RO (AI RISK MANAGEMENT) ---
        $dataToCheck = array_merge($customerData, [
            'total' => $cartData['total'],
            'quantity' => array_sum(array_column($cartItems, 'quantity')),
        ]);

        // Gọi service check rủi ro
        $riskAnalysis = $this->riskService->assessOrderRisk($dataToCheck, $userId);

        // Nếu bị chặn thì throw Exception ngay
        if (! $riskAnalysis['allowed']) {
            throw new Exception("Security Alert: Transaction blocked. Reason: {$riskAnalysis['reason']}");
        }
        // -----------------------------------------

        DB::beginTransaction();

        try {
            // --- INVENTORY HANDLING (Theo quy trình ELECTRO-AI-ENGINE.md) ---
            // 1. Check: Kiểm tra số lượng tồn
            // 2. Lock: Khóa dòng dữ liệu trong DB (lockForUpdate)
            // 3. Decrement: Trừ tồn kho
            // 4. Rollback: Nếu lỗi, hoàn tác toàn bộ Transaction

            foreach ($cartItems as $item) {
                $productId = $item['id'];
                $requestedQuantity = $item['quantity'];

                // Get product via Repository (Clean Architecture)
                $product = $this->productRepository->find($productId);

                if (! $product) {
                    throw new Exception("Product #{$productId} not found");
                }

                // Lock product row for update (tránh race condition)
                // Note: lockForUpdate() must be called within transaction
                $product = Product::where('id', $productId)
                    ->lockForUpdate()
                    ->first();

                // Check stock availability
                if ($product->stock_quantity < $requestedQuantity) {
                    throw new Exception(
                        "Insufficient stock for product: {$product->name}. ".
                            "Available: {$product->stock_quantity}, Requested: {$requestedQuantity}"
                    );
                }

                // Decrement stock (atomic operation within transaction)
                $product->stock_quantity -= $requestedQuantity;
                $product->save();
            }
            // -----------------------------------------------------------------

            // Chuẩn bị data để tạo Order
            $orderData = [
                'user_id' => $userId,
                'first_name' => $customerData['first_name'],
                'last_name' => $customerData['last_name'] ?? '',
                'email' => $customerData['email'],
                'phone' => $customerData['phone'],
                'address' => $customerData['address'],
                'note' => $customerData['note'] ?? null,
                'total' => $cartData['total'],

                'order_status' => 'pending',
                'payment_method' => $customerData['payment_method'],
                'payment_status' => 'unpaid',
            ];

            // Tạo Order Master
            $order = $this->orderRepository->createOrder($orderData);

            // Tạo Order Items (Chi tiết đơn hàng)
            foreach ($cartItems as $item) {
                $this->orderRepository->createOrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            // 3. Xử lý Thanh toán qua Factory
            $paymentGateway = $this->paymentFactory->getGateway($customerData['payment_method']);
            $paymentResult = $paymentGateway->process($order);

            // --- CẬP NHẬT LOG AI (Gắn order_id vào log đã ghi trước đó) ---
            if (! empty($riskAnalysis['log_id'])) {
                AiFeatureStore::where('id', $riskAnalysis['log_id'])->update([
                    'order_id' => $order->id,
                ]);
            }
            // -------------------------------------------------------------

            DB::commit();

            // Dispatch OrderPlaced event for real-time admin notifications
            event(new OrderPlaced($order));

            // Xóa giỏ hàng sau khi đặt thành công
            $this->cartService->clearCart();

            // Trả về kết quả bao gồm cả Redirect URL nếu có
            return [
                'order' => $order,
                'payment_result' => $paymentResult,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Checkout Failed: '.$e->getMessage());
            throw $e;
        }
    }

    public function handlePaymentCallback(string $gatewayName, \Illuminate\Http\Request $request, bool $isIpn = false): array
    {
        Log::info('Payment callback received', [
            'gateway' => $gatewayName,
            'is_ipn' => $isIpn,
            'params' => $request->all(),
        ]);

        // 1. Lấy Gateway từ Factory
        $gateway = $this->paymentFactory->getGateway($gatewayName);

        // 2. Xác thực dữ liệu
        $verificationResult = $gateway->verify($request);

        if (! $verificationResult['success']) {
            Log::warning('Payment verification failed', [
                'gateway' => $gatewayName,
                'message' => $verificationResult['message'],
            ]);

            return $verificationResult;
        }

        // 3. Tìm Order trong DB với lockForUpdate để tránh race condition
        $orderId = $verificationResult['order_id'];
        $order = \App\Models\Order::with('items')->lockForUpdate()->find($orderId);

        if (! $order) {
            Log::error('Order not found', ['order_id' => $orderId]);

            return ['success' => false, 'message' => 'Order not found'];
        }

        // 4. Amount validation for fraud protection
        $expectedAmount = intval($order->total * 100);
        $receivedAmount = intval($verificationResult['amount'] * 100);

        if ($expectedAmount !== $receivedAmount) {
            Log::error('Amount mismatch detected', [
                'order_id' => $orderId,
                'expected' => $expectedAmount,
                'received' => $receivedAmount,
            ]);

            return [
                'success' => false,
                'message' => 'Amount validation failed',
            ];
        }

        // 5. Idempotency Check
        if (in_array($order->payment_status, ['paid', 'failed', 'cancelled'])) {
            Log::info('Order already processed', [
                'order_id' => $orderId,
                'status' => $order->payment_status,
            ]);

            return [
                'success' => $order->payment_status === 'paid',
                'order_id' => $orderId,
                'message' => 'Order already processed',
            ];
        }

        // 6. Xử lý trong Transaction
        DB::beginTransaction();
        try {
            $isSuccess = $verificationResult['success'];

            if ($isSuccess) {
                // Success: Update to paid/processing
                $order->payment_status = 'paid';
                if ($order->order_status === 'pending') {
                    $order->order_status = 'processing';
                }

                // Log success
                OrderHistory::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'action' => 'Payment Received',
                    'description' => "Payment successful via {$gatewayName}. TransNo: ".($verificationResult['transaction_no'] ?? 'N/A'),
                ]);

                Log::info('Payment processed successfully', [
                    'order_id' => $orderId,
                    'gateway' => $gatewayName,
                    'amount' => $verificationResult['amount'],
                ]);
            } else {
                // Failure: Set to cancelled and restore inventory
                $order->payment_status = 'failed';
                $order->order_status = 'cancelled';

                // Restore inventory
                foreach ($order->items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $product->stock_quantity += $item->quantity;
                        $product->save();
                    }
                }

                // Log failure
                OrderHistory::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'action' => 'Payment Failed',
                    'description' => "Payment failed via {$gatewayName}. Code: ".($verificationResult['response_code'] ?? 'Unknown'),
                ]);

                Log::warning('Payment failed, inventory restored', [
                    'order_id' => $orderId,
                    'gateway' => $gatewayName,
                    'response_code' => $verificationResult['response_code'] ?? 'Unknown',
                ]);
            }

            $order->save();
            DB::commit();

            return array_merge($verificationResult, ['order_id' => $orderId]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment callback DB error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    // Thêm vào trong class OrderService

    /**
     * Xử lý hủy đơn hàng từ phía Customer
     *
     * @return Order
     *
     * @throws Exception
     */
    public function cancelOrder(int $orderId, int $userId, ?string $reason = '')
    {
        DB::beginTransaction();

        try {
            // 1. Tìm đơn hàng (Sử dụng Repository hoặc Eloquent trực tiếp nếu cần eager load)
            // Ở đây dùng Eloquent để tiện load items và relationship
            $order = \App\Models\Order::with('items')->find($orderId);

            if (! $order) {
                throw new Exception('Order not found.');
            }

            // 2. Security Check: Đảm bảo chính chủ mới được hủy
            if ($order->user_id !== $userId) {
                throw new Exception('Unauthorized access to this order.');
            }

            // 3. State Check: Chỉ cho phép hủy khi đang 'pending'
            // Nếu đã 'processing' (đang đóng gói) hoặc 'shipped' thì không được hủy
            if ($order->order_status !== 'pending') {
                throw new Exception("Cannot cancel order in '{$order->order_status}' state.");
            }

            // 4. INVENTORY RESTOCK (Quan trọng: Hoàn kho)
            foreach ($order->items as $item) {
                $product = $this->productRepository->find($item->product_id);

                if ($product) {
                    // Lock row để update an toàn
                    $product = \App\Models\Product::where('id', $item->product_id)
                        ->lockForUpdate()
                        ->first();

                    // Cộng lại số lượng tồn kho
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                }
            }

            // 5. Cập nhật trạng thái đơn hàng
            $order->order_status = 'cancelled';

            // Nếu đã thanh toán online (VNPay) nhưng chưa ship -> Cần quy trình Refund (Ở đây tạm đánh dấu logic)
            // if ($order->payment_status === 'paid') { ...trigger refund logic... }

            $order->save();

            // 6. Ghi lịch sử (Audit Log)
            if (class_exists(\App\Models\OrderHistory::class)) {
                \App\Models\OrderHistory::create([
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'action' => 'Order Cancelled',
                    'description' => 'Customer cancelled order. Reason: '.($reason ?? 'No reason provided'),
                ]);
            }

            // 7. Gửi Email (Optional - Uncomment nếu đã setup Mail Queue)
            // Mail::to($order->email)->queue(new \App\Mail\Order\OrderCancelledMail($order));

            DB::commit();

            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order Cancellation Failed: '.$e->getMessage());
            throw $e; // Ném lỗi ra để Controller bắt
        }
    }

    /**
     * Xử lý thanh toán lại cho đơn hàng
     */
    public function repayOrder(int $orderId, int $userId)
    {
        // 1. Tìm đơn hàng
        $order = $this->orderRepository->find($orderId);

        if (! $order) {
            throw new Exception('Order not found.');
        }

        // 2. Validate Ownership
        if ($order->user_id !== $userId) {
            throw new Exception('Unauthorized access.');
        }

        // 3. Validate Status
        if ($order->payment_status === 'paid') {
            throw new Exception('Order is already paid.');
        }

        if ($order->order_status === 'cancelled') {
            throw new Exception('Cannot pay for cancelled order.');
        }

        // 4. Get Payment Gateway & Process
        $paymentGateway = $this->paymentFactory->getGateway($order->payment_method);

        return $paymentGateway->process($order);
    }
}
