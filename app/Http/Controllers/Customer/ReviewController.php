<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        // Check if user has already reviewed
        $alreadyReviewed = Review::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('review_error', 'You have already reviewed this product.');
        }

        // Check if user has purchased and received the product
        $hasPurchased = $user->orders()
            ->where('order_status', 'delivered')
            ->whereHas('orderItems', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->exists();

        if (!$hasPurchased) {
            return back()->with('review_error', 'You must purchase and receive this product before you can write a review.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('review_success', 'Your review has been submitted. Thank you!');
    }
}
