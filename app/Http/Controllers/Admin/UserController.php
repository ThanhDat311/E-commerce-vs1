<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('assignedRole')->latest();

        // Search by name, email, or ID
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('id', $request->search);
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(15);
        $roles = Role::orderBy('name')->get();

        return view('pages.admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('pages.admin.users.create', compact('roles'));
    }

    public function show(User $user)
    {
        $user->load([
            'assignedRole',
            'authLogs' => function ($query) {
                $query->latest()->limit(10);
            },
            'orders',
        ]);

        $stats = [
            'total_orders' => $user->orders()->count(),
            'active_orders' => $user->orders()->whereIn('order_status', ['pending', 'processing', 'shipped'])->count(),
            'total_spent' => $user->orders()->where('order_status', 'delivered')->sum('total'),
            'last_login' => $user->authLogs()->where('is_successful', true)->latest()->first()?->created_at,
        ];

        return view('pages.admin.users.show', compact('user', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('pages.admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'phone_number' => ['nullable', 'string', 'max:20'], // [UPDATED]
            'address' => ['nullable', 'string', 'max:255'],      // [ADDED]
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number, // [UPDATED]
            'address' => $request->address,           // [ADDED]
            'role_id' => $request->role_id,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself!');
        }

        // Check for active orders
        if ($user->orders()->whereIn('order_status', ['pending', 'processing', 'shipped'])->exists()) {
            return back()->with('error', 'Cannot delete user with active orders.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(12);
        $user->update(['password' => Hash::make($newPassword)]);

        // TODO: Send email with new password in production
        return back()->with('success', "Password reset successfully. New password: {$newPassword}");
    }

    public function forceLogout(User $user)
    {
        // Invalidate all sessions for this user
        DB::table('sessions')->where('user_id', $user->id)->delete();

        return back()->with('success', 'User has been logged out from all devices.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot lock/unlock yourself!');
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $message = $user->is_active
            ? 'User account has been unlocked.'
            : 'User account has been locked.';

        return back()->with('success', $message);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        if ($user->id === Auth::id() && Auth::user()->role_id !== $request->role_id) {
            return back()->with('error', 'You cannot change your own role!');
        }

        $oldRole = $user->assignedRole->name ?? 'Unknown';

        $user->update([
            'role_id' => $request->role_id,
        ]);

        $newRole = $user->fresh()->assignedRole->name ?? 'Unknown';

        return back()->with(
            'success',
            "Role updated from {$oldRole} to {$newRole}."
        );
    }
}
