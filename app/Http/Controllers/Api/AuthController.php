<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Register a new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'phone' => $validated['phone'] ?? null,
            ]);

            $token = $user->createToken('api_token')->plainTextToken;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
                'token' => $token,
            ], 'User registered successfully', 201);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !password_verify($validated['password'], $user->password)) {
                return $this->errorResponse('Invalid credentials', 401);
            }

            // Revoke all existing tokens
            $user->tokens()->delete();

            $token = $user->createToken('api_token')->plainTextToken;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
                'token' => $token,
            ], 'Logged in successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Logout user (revoke token)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }

    /**
     * Get current user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->successResponse([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'created_at' => $user->created_at,
        ], 'User retrieved successfully');
    }

    /**
     * Refresh token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $user = $request->user();

        // Revoke old token
        $request->user()->currentAccessToken()->delete();

        // Create new token
        $token = $user->createToken('api_token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
        ], 'Token refreshed successfully');
    }
}
