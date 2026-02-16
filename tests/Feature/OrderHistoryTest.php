<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_order_history()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $orders = Order::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->withoutExceptionHandling()->get(route('orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.store.orders.index');
        $response->assertViewHas('orders');
        foreach ($orders as $order) {
            $response->assertSee('Order #'.$order->id);
        }
    }

    public function test_customer_cannot_view_others_orders()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $otherUser = User::factory()->create(['role_id' => 3]);
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('orders.show', $order));

        $response->assertStatus(403);
    }

    public function test_customer_can_filter_orders_by_status()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $pendingOrder = Order::factory()->create(['user_id' => $user->id, 'order_status' => 'pending', 'payment_status' => 'pending']);
        $completedOrder = Order::factory()->create(['user_id' => $user->id, 'order_status' => 'delivered']);

        // Filter To Pay
        $response = $this->actingAs($user)->get(route('orders.index', ['status' => 'to_pay']));
        $response->assertStatus(200);
        $response->assertSee('Order #'.$pendingOrder->id);
        $response->assertDontSee('Order #'.$completedOrder->id);

        // Filter Completed
        $response = $this->actingAs($user)->get(route('orders.index', ['status' => 'completed']));
        $response->assertStatus(200);
        $response->assertSee('Order #'.$completedOrder->id);
        $response->assertDontSee('Order #'.$pendingOrder->id);
    }

    public function test_customer_can_search_orders()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $order1 = Order::factory()->create(['user_id' => $user->id]);
        $order2 = Order::factory()->create(['user_id' => $user->id]);

        $product = Product::factory()->create(['name' => 'UniqueProduct']);
        OrderItem::factory()->create(['order_id' => $order1->id, 'product_id' => $product->id]);

        $response = $this->actingAs($user)->get(route('orders.index', ['search' => 'UniqueProduct']));

        $response->assertStatus(200);
        $response->assertSee('Order #'.$order1->id);
        $response->assertDontSee('Order #'.$order2->id); // Assuming order2 doesn't have the product
    }
}
