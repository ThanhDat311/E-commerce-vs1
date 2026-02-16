<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPriceDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_details_display_correct_prices()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'total' => 100.00,
        ]);

        $product = Product::factory()->create(['name' => 'Test Product']);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 50.00,
            'total' => 100.00,
        ]);

        $response = $this->actingAs($user)->get(route('orders.show', $order));

        $response->assertStatus(200);
        // Check for Unit Price
        $response->assertSee('50.00');
        // Check for Item Total
        $response->assertSee('100.00');
        // Check for Subtotal calculation in view
        $response->assertSee('100.00');
    }
}
