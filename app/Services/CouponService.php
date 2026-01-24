<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Log;

/**
 * CouponService
 * 
 * Handles coupon validation, discount calculation, and usage tracking
 * with comprehensive error handling and business rule enforcement
 */
class CouponService
{
    /**
     * Validate coupon code and return discount details
     * 
     * @param string $code Coupon code to validate
     * @param float $orderTotal Current order total before discount
     * @param int|null $userId User ID for per-user limit checking
     * 
     * @return array ['valid' => bool, 'data' => array, 'error' => string|null]
     */
    public function validateCoupon(string $code, $orderTotal, ?int $userId = null): array
    {
        try {
            // Trim and uppercase code
            $code = strtoupper(trim($code));

            // Find coupon by code
            $coupon = Coupon::where('code', $code)
                ->where('is_active', true)
                ->first();

            if (!$coupon) {
                return [
                    'valid' => false,
                    'error' => 'Coupon code not found or inactive.',
                    'data' => null,
                ];
            }

            // Check if coupon is valid (dates, usage limits)
            if (!$coupon->isValid()) {
                $reason = $this->getInvalidReason($coupon);

                return [
                    'valid' => false,
                    'error' => $reason,
                    'data' => null,
                ];
            }

            // Check per-user limit if userId provided
            if ($userId && !$coupon->canUserUse($userId)) {
                return [
                    'valid' => false,
                    'error' => 'You have already used this coupon the maximum number of times.',
                    'data' => null,
                ];
            }

            // Check minimum order amount
            if ($coupon->min_order && $orderTotal < $coupon->min_order) {
                return [
                    'valid' => false,
                    'error' => "Minimum order amount is {$coupon->min_order}. Current total: {$orderTotal}",
                    'data' => null,
                ];
            }

            // Calculate discount
            $discount = $coupon->calculateDiscount($orderTotal);
            $finalTotal = $orderTotal - $discount;

            return [
                'valid' => true,
                'error' => null,
                'data' => [
                    'coupon_id' => $coupon->id,
                    'code' => $coupon->code,
                    'discount_type' => $coupon->type,
                    'discount_value' => $coupon->value,
                    'discount_amount' => $discount,
                    'original_total' => $orderTotal,
                    'final_total' => $finalTotal,
                    'message' => "Coupon applied! You saved \${$discount}",
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Coupon validation error', ['code' => $code, 'error' => $e->getMessage()]);

            return [
                'valid' => false,
                'error' => 'An error occurred while validating the coupon.',
                'data' => null,
            ];
        }
    }

    /**
     * Apply coupon to an order/user
     * 
     * @param int $couponId Coupon ID
     * @param int $userId User ID
     * @param float $discountAmount Amount discounted
     * @param int|null $orderId Order ID (optional, set when order is created)
     * 
     * @return bool
     */
    public function applyCoupon(int $couponId, int $userId, $discountAmount, ?int $orderId = null): bool
    {
        try {
            $coupon = Coupon::findOrFail($couponId);

            // Record usage
            UserCoupon::create([
                'user_id' => $userId,
                'coupon_id' => $couponId,
                'order_id' => $orderId,
                'discount_amount' => $discountAmount,
                'used_at' => now(),
            ]);

            // Increment coupon usage counter
            $coupon->markAsUsed();

            Log::info('Coupon applied', [
                'coupon_id' => $couponId,
                'user_id' => $userId,
                'discount' => $discountAmount,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error applying coupon', ['coupon_id' => $couponId, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Check if user has already used a specific coupon
     * 
     * @param int $couponId
     * @param int $userId
     * 
     * @return bool
     */
    public function hasUserUsedCoupon(int $couponId, int $userId): bool
    {
        return UserCoupon::where('coupon_id', $couponId)
            ->where('user_id', $userId)
            ->whereNotNull('used_at')
            ->exists();
    }

    /**
     * Get user's coupon usage history
     * 
     * @param int $userId
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserCouponHistory(int $userId)
    {
        return UserCoupon::where('user_id', $userId)
            ->with('coupon', 'order')
            ->orderByDesc('used_at')
            ->get();
    }

    /**
     * Get detailed reason why coupon is invalid
     * 
     * @param Coupon $coupon
     * 
     * @return string
     */
    private function getInvalidReason(Coupon $coupon): string
    {
        $now = now();

        if (!$coupon->is_active) {
            return 'This coupon is no longer active.';
        }

        if ($coupon->starts_at && $now < $coupon->starts_at) {
            return "This coupon is not yet active. It starts on {$coupon->starts_at->format('M d, Y')}.";
        }

        if ($coupon->expires_at && $now > $coupon->expires_at) {
            return "This coupon has expired on {$coupon->expires_at->format('M d, Y')}.";
        }

        if ($coupon->max_usage && $coupon->used_count >= $coupon->max_usage) {
            return 'This coupon has reached its usage limit.';
        }

        return 'This coupon is not available at this time.';
    }
}
