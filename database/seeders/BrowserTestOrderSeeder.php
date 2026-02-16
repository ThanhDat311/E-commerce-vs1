<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BrowserTestOrderSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Test User
        $user = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
                'role_id' => 3, // Customer
                'email_verified_at' => now(),
            ]
        );

        // 2. Ensure Products Exist
        if (Product::count() < 3) {
            Product::factory()->count(5)->create();
        }
        $products = Product::take(3)->get();

        // 3. Create Orders
        // Order 1: Pending, Unpaid
        $order1 = Order::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => $user->email,
            'phone' => '0123456789',
            'address' => '123 Test Street',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cod',
            'total' => 0, // Will update
        ]);
        $this->addItems($order1, $products);

        // Order 2: Completed, Paid
        $order2 = Order::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => $user->email,
            'phone' => '0123456789',
            'address' => '456 Paided Lane',
            'order_status' => 'delivered',
            'payment_status' => 'paid',
            'payment_method' => 'vnpay',
            'total' => 0,
        ]);
        $this->addItems($order2, $products->shuffle()->take(1));

        // Order 3: Cancelled
        $order3 = Order::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => $user->email,
            'phone' => '0123456789',
            'address' => '789 Cancelled Blvd',
            'order_status' => 'cancelled',
            'payment_status' => 'failed',
            'payment_method' => 'cod',
            'total' => 0,
        ]);
        $this->addItems($order3, $products->take(1));
    }

    private function addItems($order, $products)
    {
        $total = 0;
        foreach ($products as $product) {
            $qty = rand(1, 3);
            $price = $product->sale_price ?? $product->price;
            $itemTotal = $qty * $price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name, // Ensure this field exists based on model
                'quantity' => $qty,
                'price' => $price,
                'total' => $itemTotal,
            ]);
            $total += $itemTotal;
        }
        $order->update(['total' => $total]);
    }
}
