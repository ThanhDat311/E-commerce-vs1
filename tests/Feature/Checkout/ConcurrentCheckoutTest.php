<?php

declare(strict_types=1);

namespace Tests\Feature\Checkout;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

uses()->group('checkout', 'concurrency');

beforeEach(function () {
    Event::fake(); // Prevent actual events from firing during tests

    // Create base user and product
    $this->user = User::factory()->create();
    $this->product = Product::factory()->create([
        'stock_quantity' => 1,
        'price' => 100.00,
    ]);

    // Mock cart data in session
    $this->cartSession = [
        $this->product->id => [
            'id' => $this->product->id,
            'quantity' => 1,
        ],
    ];
});

it('prevents duplicate checkout requests using atomic locks (Idempotency)', function () {
    // Arrange: Manually acquire the lock to simulate a concurrent request currently processing
    $lockKey = "checkout_lock_user_{$this->user->id}";
    $lock = Cache::lock($lockKey, 5);
    $lock->get();

    // Act: Send the second request while the lock is still active
    $response = $this->actingAs($this->user)
        ->withSession(['cart' => $this->cartSession])
        ->post('/checkout/process', [
            'payment_method' => 'vnpay',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890', // Must be min 10
            'address' => '123 Test Street',
        ]);

    // Assert: Must return 302 Redirect with error in session
    $response->assertStatus(302)
        ->assertSessionHasErrors(['error' => 'Your previous checkout is still processing. Please wait.']);

    // Cleanup
    $lock->release();
});

it('fails gracefully when product goes out of stock during transaction (Race Condition)', function () {
    // Arrange: Set stock to 0 to simulate another user just bought the last item right before this transaction acquired the DB lock
    $this->product->update(['stock_quantity' => 0]);

    // Act
    $response = $this->actingAs($this->user)
        ->withSession(['cart' => $this->cartSession])
        ->post('/checkout/process', [
            'payment_method' => 'vnpay',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '0987654321', // Must be min 10
            'address' => '456 Order Ave',
        ]);

    // Assert: Must return 302 Redirect with Out of stock error
    $response->assertStatus(302)
        ->assertSessionHasErrors(['error' => "Insufficient stock for product: {$this->product->name}. Available: 0, Requested: 1"]);
});
