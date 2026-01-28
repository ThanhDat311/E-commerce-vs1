<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('assignedRole')->latest();

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

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'phone_number' => ['nullable', 'string', 'max:20'], // [UPDATED] phone_number
            'address' => ['nullable', 'string', 'max:255'],      // [ADDED] address
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number, // [UPDATED]
            'address' => $request->address,           // [ADDED]
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
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

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot lock/unlock yourself!');
        }

        $user->update([
            'is_active' => ! $user->is_active
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
            'role_id' => $request->role_id
        ]);

        $newRole = $user->fresh()->assignedRole->name ?? 'Unknown';

        return back()->with(
            'success',
            "Role updated from {$oldRole} to {$newRole}."
        );
    }
}
