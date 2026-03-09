<x-mail::message>
# Order Confirmed ✅

Hi **{{ $order->first_name }}**,

Thank you for your order! We've received it and are getting it ready.

---

**Order #{{ $order->id }}**
**Date:** {{ $order->created_at->format('d M Y, H:i') }}
**Payment:** {{ strtoupper($order->payment_method) }}

<x-mail::table>
| Item | Qty | Price |
|:-----|:---:|------:|
@foreach($order->orderItems as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format($item->price, 0, ',', '.') }}₫ |
@endforeach
| **Total** | | **{{ number_format($order->total, 0, ',', '.') }}₫** |
</x-mail::table>

**Shipping Address:**
{{ $order->address }}

@if($order->payment_method === 'vnpay')
<x-mail::panel>
Your payment via VNPay has been received. We will process your order shortly.
</x-mail::panel>
@elseif($order->payment_method === 'cod')
<x-mail::panel>
You chose Cash on Delivery. Please have **{{ number_format($order->total, 0, ',', '.') }}₫** ready when your order arrives.
</x-mail::panel>
@endif

<x-mail::button :url="route('orders.show', $order->id)" color="success">
View Your Order
</x-mail::button>

Thank you for shopping with us!

{{ config('app.name') }}
</x-mail::message>
