<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CouponController
 * 
 * API endpoints for coupon validation and application
 * Handles AJAX requests from checkout page
 */
class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Validate coupon code and get discount details
     * 
     * POST /api/v1/coupons/validate
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateCoupon(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50',
                'order_total' => 'required|numeric|min:0',
            ]);

            $userId = Auth::id(); // Get authenticated user ID

            $result = $this->couponService->validateCoupon(
                $validated['code'],
                $validated['order_total'],
                $userId
            );

            if ($result['valid']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['data']['message'],
                    'data' => [
                        'coupon_id' => $result['data']['coupon_id'],
                        'code' => $result['data']['code'],
                        'discount_type' => $result['data']['discount_type'],
                        'discount_value' => $result['data']['discount_value'],
                        'discount_amount' => round($result['data']['discount_amount'], 2),
                        'original_total' => round($result['data']['original_total'], 2),
                        'final_total' => round($result['data']['final_total'], 2),
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'],
                ], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while validating the coupon.',
            ], 500);
        }
    }

    /**
     * Get coupon usage history for authenticated user
     * 
     * GET /api/v1/coupons/history
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function history()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $history = $this->couponService->getUserCouponHistory(Auth::id());

            return response()->json([
                'success' => true,
                'data' => $history->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'coupon_code' => $item->coupon->code,
                        'discount_amount' => $item->discount_amount,
                        'used_at' => $item->used_at->format('M d, Y H:i'),
                        'order_id' => $item->order_id,
                    ];
                }),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
            ], 500);
        }
    }

    /**
     * Check if user has already used a specific coupon
     * 
     * POST /api/v1/coupons/check-usage
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUsage(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $validated = $request->validate([
                'coupon_id' => 'required|exists:coupons,id',
            ]);

            $hasUsed = $this->couponService->hasUserUsedCoupon(
                $validated['coupon_id'],
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'has_used' => $hasUsed,
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
            ], 500);
        }
    }
}
