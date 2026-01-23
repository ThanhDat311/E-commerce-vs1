<?php

namespace App\Console\Commands;

use App\Events\OrderPlaced;
use App\Models\Order;
use Illuminate\Console\Command;

class TestOrderNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:order-notification {--order-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test real-time order notification by broadcasting an event';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $orderId = $this->option('order-id');

        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("Order #{$orderId} not found");
                return;
            }
        } else {
            $order = Order::latest()->first();
            if (!$order) {
                $this->error('No orders found in database. Create an order first.');
                return;
            }
        }

        $this->info('Broadcasting OrderPlaced event...');
        $this->table(
            ['Field', 'Value'],
            [
                ['Order ID', $order->id],
                ['Customer', $order->first_name . ' ' . $order->last_name],
                ['Email', $order->email],
                ['Amount', '$' . $order->total],
                ['Status', $order->order_status],
                ['Payment Method', $order->payment_method],
            ]
        );

        event(new OrderPlaced($order));

        $this->info('✓ Event broadcasted successfully!');
        $this->line('');
        $this->info('Expected behavior:');
        $this->line('1. All connected admin users should receive a toast notification');
        $this->line('2. Notification should appear in top-right corner');
        $this->line('3. Notification auto-dismisses after 10 seconds');
        $this->line('4. Clicking "View Order" redirects to order details page');
    }
}
