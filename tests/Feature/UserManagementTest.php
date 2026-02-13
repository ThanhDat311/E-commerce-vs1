<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Order;

beforeEach(function () {
    // Ensure roles exist
    if (Role::count() === 0) {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Vendor']);
        Role::create(['name' => 'Customer']);
    }

    $this->adminRole = Role::where('name', 'Admin')->first();
    $this->customerRole = Role::where('name', 'Customer')->first();

    $this->admin = User::factory()->create([
        'role_id' => $this->adminRole->id,
        'is_active' => true,
    ]);
});

it('displays the user management index page', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.users.index'));

    $response->assertStatus(200);
    $response->assertSee('Users Management');
    $response->assertSee('Add New User');
});

it('can filter users by role', function () {
    $customer = User::factory()->create(['role_id' => $this->customerRole->id, 'name' => 'John Customer']);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.users.index', ['role' => $this->customerRole->id]));

    $response->assertStatus(200);
    $response->assertSee('John Customer');
});

it('can search users', function () {
    $targetUser = User::factory()->create(['role_id' => $this->customerRole->id, 'name' => 'Target User', 'email' => 'target@example.com']);
    $otherUser = User::factory()->create(['role_id' => $this->customerRole->id, 'name' => 'Other User']);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.users.index', ['search' => 'target']));

    $response->assertStatus(200);
    $response->assertSee('Target User');
    $response->assertDontSee('Other User');
});

it('displays the user profile page', function () {
    $user = User::factory()->create(['role_id' => $this->customerRole->id]);

    // Create some orders for stats
    Order::factory()->count(3)->create([
        'user_id' => $user->id,
        'total' => 100,
        'order_status' => 'delivered'
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.users.show', $user));

    $response->assertStatus(200);
    $response->assertSee($user->name);
    $response->assertSee('User Profile');
    $response->assertSee('Total Orders');
    $response->assertSee('3'); // 3 orders
    $response->assertSee('300.00'); // Total spent 300
});

it('can toggle user status', function () {
    $user = User::factory()->create(['role_id' => $this->customerRole->id, 'is_active' => true]);

    $response = $this->actingAs($this->admin)
        ->patch(route('admin.users.toggle_status', $user));

    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => false]);

    $response = $this->actingAs($this->admin)
        ->patch(route('admin.users.toggle_status', $user));

    $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => true]);
});
