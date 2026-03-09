<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ReviewService
{
    /**
     * Check whether a user has purchased and received a specific product.
     */
    public function hasPurchased(User $user, Product $product): bool
    {
        return $user->orders()
            ->where('order_status', 'delivered')
            ->whereHas('orderItems', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();
    }

    /**
     * Check whether the user has already reviewed the product.
     */
    public function hasAlreadyReviewed(User $user, Product $product): bool
    {
        return Review::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Submit a new review after verifying eligibility.
     *
     * @param  array{rating: int, comment: string|null}  $data
     * @return array{success: bool, message: string, review?: Review}
     */
    public function submitReview(User $user, Product $product, array $data): array
    {
        if ($this->hasAlreadyReviewed($user, $product)) {
            return [
                'success' => false,
                'message' => 'you_already_reviewed',
            ];
        }

        if (! $this->hasPurchased($user, $product)) {
            return [
                'success' => false,
                'message' => 'purchase_required',
            ];
        }

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'review_submitted',
            'review' => $review,
        ];
    }

    /**
     * Recalculate and return the product's current average rating.
     */
    public function getAverageRating(Product $product): float
    {
        return (float) round(
            Review::where('product_id', $product->id)->avg('rating') ?? 0,
            1
        );
    }

    /**
     * Get paginated reviews for a product, newest first.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getProductReviews(Product $product, int $perPage = 10)
    {
        return Review::with('user:id,name')
            ->where('product_id', $product->id)
            ->latest()
            ->paginate($perPage);
    }
}
