<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class HeaderComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Lấy danh sách categories
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // Lấy dữ liệu giỏ hàng từ session
        $cart = Session::get('cart', []);
        $cartCount = count($cart);
        $cartTotal = 0;

        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        $view->with([
            'categories' => $categories,
            'cartCount' => $cartCount,
            'cartTotal' => $cartTotal,
        ]);
    }
}
