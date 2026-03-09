<?php

use Illuminate\Support\Facades\Broadcast;

// Default user model channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin private channel — only role_id = 1 (admin) can subscribe
Broadcast::channel('admin-channel', function ($user) {
    return (int) $user->role_id === 1;
});

// Per-order private channel — customer can subscribe to their own orders
Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
    return \App\Models\Order::withoutGlobalScopes()
        ->where('id', $orderId)
        ->where('user_id', $user->id)
        ->exists();
});
