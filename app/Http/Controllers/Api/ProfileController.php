<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    use ApiResponse;

    /**
     * Get authenticated user's profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $addresses = $user->addresses()
            ->select('id', 'type', 'street', 'city', 'state', 'postal_code', 'country', 'is_default')
            ->get();

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'addresses' => $addresses,
        ], 'Profile retrieved successfully');
    }

    /**
     * Update user's profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|nullable|string|max:20',
                'email' => 'sometimes|required|email|unique:users,email,' . $request->user()->id,
            ]);

            $user = $request->user();
            $user->update($validated);

            return $this->successResponse([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'updated_at' => $user->updated_at,
            ], 'Profile updated successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Update password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed|different:current_password',
            ]);

            $user = $request->user();

            if (!password_verify($validated['current_password'], $user->password)) {
                return $this->errorResponse('Current password is incorrect', 422);
            }

            $user->update(['password' => bcrypt($validated['password'])]);

            return $this->successResponse(null, 'Password updated successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Add address to profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addAddress(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:home,office,other',
                'street' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'postal_code' => 'required|string',
                'country' => 'required|string',
                'is_default' => 'boolean',
            ]);

            $user = $request->user();

            // If this is default, unset other defaults
            if ($validated['is_default'] ?? false) {
                $user->addresses()->update(['is_default' => false]);
            }

            $address = $user->addresses()->create($validated);

            return $this->successResponse($address, 'Address added successfully', 201);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Update address
     *
     * @param Request $request
     * @param int $addressId
     * @return JsonResponse
     */
    public function updateAddress(Request $request, int $addressId): JsonResponse
    {
        try {
            $user = $request->user();
            $address = $user->addresses()->findOrFail($addressId);

            $validated = $request->validate([
                'type' => 'sometimes|in:home,office,other',
                'street' => 'sometimes|string',
                'city' => 'sometimes|string',
                'state' => 'sometimes|string',
                'postal_code' => 'sometimes|string',
                'country' => 'sometimes|string',
                'is_default' => 'boolean',
            ]);

            if ($validated['is_default'] ?? false) {
                $user->addresses()->update(['is_default' => false]);
            }

            $address->update($validated);

            return $this->successResponse($address, 'Address updated successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Delete address
     *
     * @param Request $request
     * @param int $addressId
     * @return JsonResponse
     */
    public function deleteAddress(Request $request, int $addressId): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($addressId);

        $address->delete();

        return $this->successResponse(null, 'Address deleted successfully');
    }
}
