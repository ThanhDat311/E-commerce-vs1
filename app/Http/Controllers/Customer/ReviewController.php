<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService) {}

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $result = $this->reviewService->submitReview(
            $request->user(),
            $product,
            $validated
        );

        if (! $result['success']) {
            return back()->with('review_error', match ($result['message']) {
                'you_already_reviewed' => 'You have already reviewed this product.',
                'purchase_required' => 'You must purchase and receive this product before you can write a review.',
                default => 'Unable to submit your review.',
            });
        }

        return back()->with('review_success', 'Your review has been submitted. Thank you!');
    }
}
