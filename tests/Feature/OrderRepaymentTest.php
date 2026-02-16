<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderRepaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_initiate_repayment_for_pending_order()
    {
        // 1. Setup
        $user = User::factory()->create(['role_id' => 3]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'vnpay',
            'total' => 100,
        ]);

        // 2. Mock OrderService
        $this->mock(OrderService::class, function ($mock) use ($order, $user) {
            $mock->shouldReceive('repayOrder')
                ->with($order->id, $user->id)
                ->once()
                ->andReturn([
                    'success' => true,
                    'is_redirect' => true,
                    'redirect_url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?query=test',
                ]);
        });

        // 3. Action
        $response = $this->actingAs($user)->post(route('orders.repay', $order));

        // 4. Assert
        $response->assertStatus(302);
        $response->assertRedirect('https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?query=test');
    }

    public function test_customer_cannot_repay_paid_order()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'paid',
        ]);

        // Mock Service throwing exception
        $this->mock(OrderService::class, function ($mock) use ($order, $user) {
            $mock->shouldReceive('repayOrder')
                ->with($order->id, $user->id)
                ->once()
                ->andThrow(new \Exception('Order is already paid.'));
        });

        $response = $this->actingAs($user)->post(route('orders.repay', $order));

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Unable to process payment: Order is already paid.');
    }
}
