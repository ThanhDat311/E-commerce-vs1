<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    use ApiResponse;

    /**
     * Get user's cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get cart items from session or database
        $cartItems = $request->session()->get('cart', []);

        // Enrich with product details
        $items = [];
        $total = 0;

        foreach ($cartItems as $productId => $quantity) {
            $product = Product::find($productId);

            if ($product) {
                $itemTotal = $product->price * $quantity;
                $total += $itemTotal;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $itemTotal,
                ];
            }
        }

        return $this->successResponse([
            'items' => $items,
            'total_items' => count($items),
            'cart_total' => $total,
        ], 'Cart retrieved successfully');
    }

    /**
     * Add product to cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:100',
            ]);

            $product = Product::findOrFail($validated['product_id']);

            // Check stock
            if ($product->stock < $validated['quantity']) {
                return $this->errorResponse('Insufficient stock available', 400);
            }

            // Get or initialize cart from session
            $cart = $request->session()->get('cart', []);

            // Add or update product in cart
            if (isset($cart[$validated['product_id']])) {
                $newQuantity = $cart[$validated['product_id']] + $validated['quantity'];

                if ($newQuantity > $product->stock) {
                    return $this->errorResponse('Requested quantity exceeds available stock', 400);
                }

                $cart[$validated['product_id']] = $newQuantity;
            } else {
                $cart[$validated['product_id']] = $validated['quantity'];
            }

            // Save updated cart to session
            $request->session()->put('cart', $cart);

            return $this->successResponse([
                'product_id' => $validated['product_id'],
                'product_name' => $product->name,
                'quantity' => $cart[$validated['product_id']],
                'cart_total_items' => array_sum($cart),
            ], 'Product added to cart successfully', 201);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Update cart item quantity
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function update(Request $request, int $productId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:100',
            ]);

            $product = Product::findOrFail($productId);
            $cart = $request->session()->get('cart', []);

            if (!isset($cart[$productId])) {
                return $this->errorResponse('Product not in cart', 404);
            }

            if ($product->stock < $validated['quantity']) {
                return $this->errorResponse('Insufficient stock available', 400);
            }

            $cart[$productId] = $validated['quantity'];
            $request->session()->put('cart', $cart);

            return $this->successResponse([
                'product_id' => $productId,
                'quantity' => $validated['quantity'],
                'cart_total_items' => array_sum($cart),
            ], 'Cart updated successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Remove product from cart
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function remove(Request $request, int $productId): JsonResponse
    {
        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return $this->errorResponse('Product not in cart', 404);
        }

        unset($cart[$productId]);
        $request->session()->put('cart', $cart);

        return $this->successResponse([
            'cart_total_items' => array_sum($cart),
        ], 'Product removed from cart successfully');
    }

    /**
     * Clear entire cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request): JsonResponse
    {
        $request->session()->forget('cart');

        return $this->successResponse(null, 'Cart cleared successfully');
    }

    /**
     * Apply coupon to cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'coupon_code' => 'required|string',
            ]);

            // Check coupon validity (implement based on your coupon system)
            // For now, return a placeholder response

            return $this->successResponse([
                'coupon_code' => $validated['coupon_code'],
                'discount' => 0,
                'message' => 'Coupon applied',
            ], 'Coupon applied successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }
}
