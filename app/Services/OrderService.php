<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AiFeatureStore;
use App\Models\Product;
use App\Services\Payment\PaymentFactory;
use App\Models\OrderHistory; 

use Exception;

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
            throw new Exception("Cart is empty");
        }

        // --- CHECK RỦI RO (AI RISK MANAGEMENT) ---
        $dataToCheck = array_merge($customerData, [
            'total' => $cartData['total'],
            'quantity' => array_sum(array_column($cartItems, 'quantity'))
        ]);

        // Gọi service check rủi ro
        $riskAnalysis = $this->riskService->assessOrderRisk($dataToCheck, $userId);

        // Nếu bị chặn thì throw Exception ngay
        if (!$riskAnalysis['allowed']) {
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
                
                if (!$product) {
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
                        "Insufficient stock for product: {$product->name}. " .
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
                'user_id'        => $userId,
                'first_name'     => $customerData['first_name'],
                'last_name'      => $customerData['last_name'] ?? '',
                'email'          => $customerData['email'],
                'phone'          => $customerData['phone'],
                'address'        => $customerData['address'],
                'note'           => $customerData['note'] ?? null,
                'total'          => $cartData['total'],

                'order_status'   => 'pending',
                'payment_method' => $customerData['payment_method'],
                'payment_status' => 'unpaid'
            ];

            // Tạo Order Master
            $order = $this->orderRepository->createOrder($orderData);

            // Tạo Order Items (Chi tiết đơn hàng)
            foreach ($cartItems as $item) {
                $this->orderRepository->createOrderItem([
                    'order_id'     => $order->id,
                    'product_id'   => $item['id'],
                    'product_name' => $item['name'],
                    'quantity'     => $item['quantity'],
                    'price'        => $item['price'],
                    'total'        => $item['price'] * $item['quantity']
                ]);
            }

            // 3. Xử lý Thanh toán qua Factory
            $paymentGateway = $this->paymentFactory->getGateway($customerData['payment_method']);
            $paymentResult = $paymentGateway->process($order);

            // --- CẬP NHẬT LOG AI (Gắn order_id vào log đã ghi trước đó) ---
            if (!empty($riskAnalysis['log_id'])) {
                AiFeatureStore::where('id', $riskAnalysis['log_id'])->update([
                    'order_id' => $order->id
                ]);
            }
            // -------------------------------------------------------------

            DB::commit();

            // Xóa giỏ hàng sau khi đặt thành công
            $this->cartService->clearCart();

            // Trả về kết quả bao gồm cả Redirect URL nếu có
            return [
                'order' => $order,
                'payment_result' => $paymentResult
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Checkout Failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function handlePaymentCallback(string $gatewayName, \Illuminate\Http\Request $request): array
    {
        // 1. Lấy Gateway từ Factory (đã inject từ trước)
        $gateway = $this->paymentFactory->getGateway($gatewayName);

        // 2. Xác thực dữ liệu (Gọi hàm verify vừa viết ở bước 1)
        $verificationResult = $gateway->verify($request);

        if (!$verificationResult['success']) {
            return $verificationResult; // Trả về lỗi nếu chữ ký sai hoặc GD thất bại
        }

        // 3. Tìm Order trong DB
        $orderId = $verificationResult['order_id'];
        $order = $this->orderRepository->find($orderId); // Đảm bảo repo có hàm find($id)

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        // 4. Idempotency Check (Quan trọng): Kiểm tra xem đơn đã được xử lý trước đó chưa
        // Để tránh trường hợp User refresh trang callback hoặc IPN gọi nhiều lần
        if ($order->payment_status === 'paid') {
            return ['success' => true, 'order_id' => $orderId, 'message' => 'Order already processed'];
        }

        // 5. Cập nhật trạng thái trong Transaction
        DB::beginTransaction();
        try {
            // Update trạng thái thanh toán
            $order->payment_status = 'paid';
            
            // Có thể update luôn trạng thái đơn hàng nếu muốn
            if ($order->order_status === 'pending') {
                $order->order_status = 'processing';
            }
            
            $order->save();

            // Ghi lịch sử đơn hàng
            if (class_exists(OrderHistory::class)) {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id, // Hoặc null nếu không xác định
                    'action' => 'Payment Received',
                    'description' => "Received payment via {$gatewayName}. TransNo: " . ($verificationResult['transaction_no'] ?? 'N/A')
                ]);
            }

            DB::commit();

            return $verificationResult;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Payment Callback DB Error: " . $e->getMessage());
            throw $e;
        }
    }
}
