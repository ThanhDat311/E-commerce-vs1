<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupportTicket>
 */
class SupportTicketFactory extends Factory
{
    protected $model = SupportTicket::class;

    public function definition(): array
    {
        $subjects = [
            'Order not received',
            'Product damaged during shipping',
            'Wrong item delivered',
            'Refund request for cancelled order',
            'Account login issues',
            'Unable to reset password',
            'Payment not processed',
            'Discount code not working',
            'Product quality concerns',
            'Tracking information not updating',
            'Need help with product setup',
            'Billing inquiry',
        ];

        return [
            'user_id' => User::factory(),
            'subject' => fake()->randomElement($subjects),
            'status' => fake()->randomElement(['open', 'in_progress', 'resolved', 'closed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'category' => fake()->randomElement(['order_issue', 'account', 'technical', 'billing', 'other']),
            'order_id' => fake()->boolean(60) ? Order::factory() : null,
            'assigned_to' => null,
        ];
    }

    /**
     * Indicate that the ticket is open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    /**
     * Indicate that the ticket is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    /**
     * Indicate that the ticket is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
        ]);
    }

    /**
     * Indicate that the ticket is urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }

    /**
     * Indicate that the ticket is related to an order.
     */
    public function withOrder(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => Order::factory(),
            'category' => 'order_issue',
        ]);
    }
}
