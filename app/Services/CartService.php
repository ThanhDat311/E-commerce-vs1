<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getCart(): array
    {
        return Session::get('cart', []);
    }

    public function getCartDetails(): array
    {
        $sessionCart = $this->getCart();
        // Lấy danh sách ID từ keys của mảng session
        $productIds = array_keys($sessionCart);

        if (empty($productIds)) {
            return [
                'cartItems' => [],
                'subTotal'  => 0,
                'shipping'  => 0,
                'total'     => 0
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

            $subtotal = $product->price * $quantity;
            $subTotal += $subtotal;

            // Tạo mảng item theo cấu trúc yêu cầu
            $cartItems[] = [
                'id'        => $product->id,
                'name'      => $product->name,
                'price'     => $product->price,
                'image_url' => $product->image_url ?? 'img/product-1.png',
                'quantity'  => $quantity,
                'subtotal'  => $subtotal
            ];
        }

        return [
            'cartItems' => $cartItems,
            'subTotal'  => $subTotal,
            'shipping'  => $shipping,
            'total'     => $subTotal + $shipping
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

    // [NEW] Method cập nhật số lượng (Dùng cho AJAX Update)
    public function updateQuantity($id, $quantity)
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

        // Tính toán lại tổng tiền để trả về cho Frontend
        $newData = $this->getCartDetails();

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
            'cart_total' => $newData['total'],
            'message'    => 'Cart updated successfully'
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
