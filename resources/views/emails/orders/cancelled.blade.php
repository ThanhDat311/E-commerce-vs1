<x-mail::message>
# Order Cancelled

Hi **{{ $order->first_name }}**,

We're sorry to let you know that your order **#{{ $order->id }}** has been cancelled.

@if($reason)
**Reason:** {{ $reason }}
@endif

@if($order->payment_status === 'paid' && $order->payment_method === 'vnpay')
<x-mail::panel>
💳 **Refund Notice:** Because you paid via VNPay, a refund of **{{ number_format($order->total, 0, ',', '.') }}₫** has been initiated. It typically takes 3–7 business days to appear in your account.
</x-mail::panel>
@endif

**Order Summary**

<x-mail::table>
| Item | Qty | Price |
|:-----|:---:|------:|
@foreach($order->orderItems as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format($item->price, 0, ',', '.') }}₫ |
@endforeach
| **Total** | | **{{ number_format($order->total, 0, ',', '.') }}₫** |
</x-mail::table>

If you have any questions, please contact our support team.

<x-mail::button :url="url('/shop')" color="primary">
Continue Shopping
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
