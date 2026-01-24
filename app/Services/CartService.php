<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $productRepository;
    protected $flashSaleService;
    protected $couponService;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        FlashSaleService $flashSaleService,
        CouponService $couponService
    ) {
        $this->productRepository = $productRepository;
        $this->flashSaleService = $flashSaleService;
        $this->couponService = $couponService;
    }

    public function getCart(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Get cart details with flash sale prices and optional coupon discount
     * 
     * Flash sales are applied FIRST (changes product price)
     * Then coupons are applied to the final total
     * 
     * @param string|null $couponCode Optional coupon code to apply
     * @param int|null $userId User ID for coupon per-user limit checking
     * 
     * @return array
     */
    public function getCartDetails(?string $couponCode = null, ?int $userId = null): array
    {
        $sessionCart = $this->getCart();
        // Lấy danh sách ID từ keys của mảng session
        $productIds = array_keys($sessionCart);

        if (empty($productIds)) {
            return [
                'cartItems' => [],
                'subTotal'  => 0,
                'shipping'  => 3.00,
                'discount'  => 0,
                'total'     => 3.00,
                'coupon'    => null,
            ];
        }

        $products = $this->productRepository->findByIds($productIds);

        $cartItems = [];
        $subTotal = 0;
        $shipping = 3.00; // Phí ship cố định (có thể tách ra config)

        foreach ($products as $product) {
            $id = $product->id;

            // Lấy quantity từ session, fallback = 1
            $quantity = $sessionCart[$id]['quantity'] ?? 1;

            // STEP 1: Check for active flash sale (applies FIRST)
            $effectivePrice = $this->flashSaleService->getEffectivePrice($product);
            $originalPrice = $product->price;
            $isOnSale = (float)$originalPrice !== (float)$effectivePrice;

            $subtotal = (float)$effectivePrice * $quantity;
            $subTotal += $subtotal;

            // Tạo mảng item theo cấu trúc yêu cầu
            $cartItems[] = [
                'id'        => $product->id,
                'name'      => $product->name,
                'price'     => $effectivePrice, // Use effective price (sale if active)
                'original_price' => $originalPrice,
                'image_url' => $product->image_url ?? 'img/product-1.png',
                'quantity'  => $quantity,
                'subtotal'  => $subtotal,
                'is_on_sale' => $isOnSale,
                'discount_percentage' => $isOnSale ? $this->flashSaleService->getDiscountPercentage($product) : null,
            ];
        }

        // STEP 2: Apply coupon discount (if provided)
        $discount = 0;
        $couponApplied = null;

        if ($couponCode) {
            $couponValidation = $this->couponService->validateCoupon($couponCode, $subTotal, $userId);

            if ($couponValidation['valid']) {
                $discount = $couponValidation['data']['discount_amount'];
                $couponApplied = [
                    'code' => $couponCode,
                    'type' => $couponValidation['data']['discount_type'],
                    'discount_amount' => $discount,
                ];
            }
        }

        $total = $subTotal + $shipping - $discount;

        return [
            'cartItems' => $cartItems,
            'subTotal'  => $subTotal,
            'shipping'  => $shipping,
            'discount'  => $discount,
            'total'     => $total,
            'coupon'    => $couponApplied,
        ];
    }

    public function addToCart(int $productId, int $quantity = 1): array
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            return ['status' => false, 'message' => 'Product not found!'];
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'quantity' => $quantity
            ];
        }

        Session::put('cart', $cart);

        return ['status' => true, 'message' => 'Product added to cart successfully!'];
    }

    /**
     * Update quantity and return new cart totals (AJAX ready)
     * Includes flash sale prices and coupon discount if applicable
     */
    public function updateQuantity($id, $quantity, ?string $couponCode = null, ?int $userId = null)
    {
        $cart = $this->getCart();

        if (!isset($cart[$id])) {
            throw new \Exception("Product not found in cart");
        }

        if ($quantity < 1) {
            throw new \Exception("Quantity must be at least 1");
        }

        // Kiểm tra tồn kho thực tế (Optional - Recommended)
        $product = $this->productRepository->find($id);
        if ($product && $quantity > $product->stock_quantity) {
            throw new \Exception("Only {$product->stock_quantity} items left in stock");
        }

        // Cập nhật session
        $cart[$id]['quantity'] = $quantity;
        Session::put('cart', $cart);

        // Tính toán lại tổng tiền (with flash sales and coupon) để trả về cho Frontend
        $newData = $this->getCartDetails($couponCode, $userId);

        // Tìm item trong cartItems theo id
        $itemTotal = 0;
        foreach ($newData['cartItems'] as $item) {
            if ($item['id'] == $id) {
                $itemTotal = $item['subtotal'];
                break;
            }
        }

        return [
            'success'    => true,
            'item_total' => $itemTotal,
            'subtotal'   => $newData['subTotal'],
            'shipping'   => $newData['shipping'],
            'discount'   => $newData['discount'],
            'cart_total' => $newData['total'],
            'message'    => 'Cart updated successfully'
        ];
    }

    /**
     * Apply coupon and return updated cart details
     * 
     * @param string $couponCode
     * @param int|null $userId
     * 
     * @return array [success => bool, message => string, data => array|null]
     */
    public function applyCoupon(string $couponCode, ?int $userId = null): array
    {
        $cartDetails = $this->getCartDetails(null, $userId);

        if (empty($cartDetails['cartItems'])) {
            return [
                'success' => false,
                'message' => 'Your cart is empty.',
                'data' => null,
            ];
        }

        // Validate coupon
        $validation = $this->couponService->validateCoupon($couponCode, $cartDetails['subTotal'], $userId);

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['error'],
                'data' => null,
            ];
        }

        // Return updated cart with coupon applied
        return [
            'success' => true,
            'message' => $validation['data']['message'],
            'data' => $this->getCartDetails($couponCode, $userId),
        ];
    }

    public function removeFromCart(int $id): void
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
    }

    public function clearCart(): void
    {
        Session::forget('cart');
    }
}
