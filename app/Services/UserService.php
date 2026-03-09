<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Update a user's active status.
     */
    public function toggleStatus(User $user): bool
    {
        $user->is_active = ! $user->is_active;
        $user->save();

        Log::info('User status toggled', [
            'user_id' => $user->id,
            'is_active' => $user->is_active,
        ]);

        return $user->is_active;
    }

    /**
     * Update a user's role.
     */
    public function updateRole(User $user, int $roleId): void
    {
        $user->role_id = $roleId;
        $user->save();

        Log::info('User role updated', [
            'user_id' => $user->id,
            'role_id' => $roleId,
        ]);
    }

    /**
     * Reset a user's password to a new random value and return it.
     */
    public function resetPassword(User $user): string
    {
        $newPassword = \Illuminate\Support\Str::random(12);

        $user->password = Hash::make($newPassword);
        $user->save();

        Log::info('Admin reset password for user', ['user_id' => $user->id]);

        return $newPassword;
    }

    /**
     * Invalidate all of the user's sessions, forcing a logout.
     */
    public function forceLogout(User $user): void
    {
        // Invalidate all Sanctum API tokens
        $user->tokens()->delete();

        // Invalidate session-based auth by cycling the "remember token"
        $user->forceFill(['remember_token' => null])->save();

        Log::info('User force-logged out', ['user_id' => $user->id]);
    }

    /**
     * Get a paginated list of users, optionally filtered by role or search term.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUsers(?string $search = null, ?int $roleId = null, int $perPage = 20)
    {
        return User::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->when($roleId, fn ($q) => $q->where('role_id', $roleId))
            ->latest()
            ->paginate($perPage);
    }
}
