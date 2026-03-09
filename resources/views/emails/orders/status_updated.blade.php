@php
    $statusLabels = [
        'pending'    => ['label' => 'Pending',     'color' => 'warning', 'icon' => '🕐'],
        'processing' => ['label' => 'Processing',  'color' => 'primary', 'icon' => '📦'],
        'shipped'    => ['label' => 'Shipped',     'color' => 'primary', 'icon' => '🚚'],
        'delivered'  => ['label' => 'Delivered',   'color' => 'success', 'icon' => '✅'],
        'cancelled'  => ['label' => 'Cancelled',   'color' => 'error',   'icon' => '❌'],
    ];
    $info = $statusLabels[$newStatus] ?? ['label' => ucfirst($newStatus), 'color' => 'primary', 'icon' => '📋'];
@endphp
<x-mail::message>
# {{ $info['icon'] }} Order Status Update

Hi **{{ $order->first_name }}**,

Your order **#{{ $order->id }}** status has been updated.

<x-mail::panel>
**New Status: {{ $info['label'] }}**

@if($newStatus === 'shipped' && $order->tracking_number)
Your tracking number is: **{{ $order->tracking_number }}**
(Carrier: {{ $order->shipping_carrier ?? 'N/A' }})
@elseif($newStatus === 'delivered')
Your order has been delivered. We hope you love your purchase!
@elseif($newStatus === 'processing')
We're preparing your order. You'll receive another update when it ships.
@endif
</x-mail::panel>

<x-mail::button :url="route('orders.show', $order->id)" color="{{ $info['color'] === 'error' ? 'primary' : $info['color'] }}">
View Order Details
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
