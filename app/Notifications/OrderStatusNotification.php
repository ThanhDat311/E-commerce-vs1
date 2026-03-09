<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Order $order,
        public readonly string $oldStatus,
        public readonly string $newStatus
    ) {}

    /**
     * @return array<string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Persisted notification for the customer's notification feed.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "Your order #{$this->order->id} status has been updated to ".ucfirst(str_replace('_', ' ', $this->newStatus)),
            'url' => route('orders.show', $this->order->id),
        ];
    }

    /**
     * Real-time push via Echo/Reverb on the user's private channel.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'order_id' => $this->order->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "Your order #{$this->order->id} status has been updated to ".ucfirst(str_replace('_', ' ', $this->newStatus)),
            'url' => route('orders.show', $this->order->id),
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function broadcastType(): string
    {
        return 'order-status-notification';
    }

    /**
     * Also send an email for significant status transitions.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order #{$this->order->id} status updated")
            ->line("Your order #{$this->order->id} status has changed from '{$this->oldStatus}' to '{$this->newStatus}'.")
            ->action('View Order', route('orders.show', $this->order->id));
    }
}
