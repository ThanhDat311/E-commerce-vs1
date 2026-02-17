<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VendorTestDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Create or Get Vendor User
            $vendor = User::firstOrCreate(
                ['email' => 'vendor@demo.com'],
                [
                    'name' => 'TechGiant Store',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role_id' => 4, // Assuming 4 is vendor based on output
                ]
            );

            // Assign Vendor Role
            if (method_exists($vendor, 'assignRole')) {
                $vendor->assignRole('vendor');
            }

            $this->command->info("Vendor user checked: {$vendor->email}");

            // 2. Real Categories
            $categories = [
                'Electronics' => Category::firstOrCreate(['name' => 'Electronics', 'slug' => 'electronics']),
                'Fashion' => Category::firstOrCreate(['name' => 'Fashion', 'slug' => 'fashion']),
                'Home & Office' => Category::firstOrCreate(['name' => 'Home & Office', 'slug' => 'home-office']),
            ];

            // 3. Real Products List
            $realProducts = [
                [
                    'name' => 'iPhone 15 Pro Max',
                    'category' => 'Electronics',
                    'price' => 1199.00,
                    'description' => 'Titanium design, A17 Pro chip, 48MP Main camera, USB-C.',
                    'image_url' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'IPH15PM-256',
                    'stock_quantity' => 50,
                    'is_featured' => true,
                    'is_new' => true,
                ],
                [
                    'name' => 'MacBook Air M2',
                    'category' => 'Electronics',
                    'price' => 999.00,
                    'description' => 'Strikingly thin design. Supercharged by M2. Up to 18 hours of battery life.',
                    'image_url' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'MBA-M2-13',
                    'stock_quantity' => 20,
                    'is_featured' => true,
                    'is_new' => false,
                ],
                [
                    'name' => 'Sony WH-1000XM5',
                    'category' => 'Electronics',
                    'price' => 348.00,
                    'description' => 'Industry Leading Noise Canceling Wireless Headphones.',
                    'image_url' => 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'SONY-XM5-BLK',
                    'stock_quantity' => 100,
                    'is_featured' => false,
                    'is_new' => false,
                ],
                [
                    'name' => 'Samsung 49" Odyssey G9',
                    'category' => 'Electronics',
                    'price' => 1299.99,
                    'description' => 'OLED Curved Smart Gaming Monitor, 240Hz, 0.03ms.',
                    'image_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'SAM-G9-OLED',
                    'stock_quantity' => 5,
                    'is_featured' => true,
                    'is_new' => true,
                ],
                [
                    'name' => 'Nike Air Force 1 \'07',
                    'category' => 'Fashion',
                    'price' => 115.00,
                    'description' => 'The radiance lives on in the Nike Air Force 1 \'07, the b-ball icon.',
                    'image_url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'NIKE-AF1-WHT',
                    'stock_quantity' => 200,
                    'is_featured' => false,
                    'is_new' => false,
                ],
                [
                    'name' => 'Classic Leather Jacket',
                    'category' => 'Fashion',
                    'price' => 250.00,
                    'description' => 'Premium genuine leather jacket with a timeless design.',
                    'image_url' => 'https://images.unsplash.com/photo-1551028919-ac66e624ec12?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'LTHR-JKT-BLK',
                    'stock_quantity' => 30,
                    'is_featured' => true,
                    'is_new' => false,
                ],
                [
                    'name' => 'Ergonomic Office Chair',
                    'category' => 'Home & Office',
                    'price' => 199.99,
                    'description' => 'Breathable mesh seat and back, adjustable lumbar support.',
                    'image_url' => 'https://images.unsplash.com/photo-1505843490538-5133c6c7d0e1?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'ERGO-CHAIR-01',
                    'stock_quantity' => 45,
                    'is_featured' => false,
                    'is_new' => true,
                ],
                [
                    'name' => 'Standing Desk Converter',
                    'category' => 'Home & Office',
                    'price' => 129.50,
                    'description' => 'Turn any desk into a standing desk. Height adjustable.',
                    'image_url' => 'https://images.unsplash.com/photo-1616464916356-3a777b2b60b1?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'STND-DESK-PROP',
                    'stock_quantity' => 15,
                    'is_featured' => false,
                    'is_new' => false,
                ],
                [
                    'name' => 'Mechanical Keyboard Keychron K2',
                    'category' => 'Electronics',
                    'price' => 79.00,
                    'description' => '75% Layout Wireless Mechanical Gaming Keyboard with Gateron Switches.',
                    'image_url' => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'KEYCHRON-K2',
                    'stock_quantity' => 60,
                    'is_featured' => true,
                    'is_new' => false,
                ],
                [
                    'name' => 'Smart LED Bulb (Pack of 4)',
                    'category' => 'Home & Office',
                    'price' => 39.99,
                    'description' => 'Multicolor smart bulbs compatible with Alexa and Google Home.',
                    'image_url' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
                    'sku' => 'SMART-LED-4PK',
                    'stock_quantity' => 120,
                    'is_featured' => false,
                    'is_new' => true,
                ]

            ];

            // 4. Create Products
            $createdProducts = collect();
            foreach ($realProducts as $data) {
                $categoryName = $data['category'];
                unset($data['category']);

                $product = Product::updateOrCreate(
                    ['sku' => $data['sku']], // Prevent duplicates
                    array_merge($data, [
                        'vendor_id' => $vendor->id,
                        'category_id' => $categories[$categoryName]->id,
                    ])
                );
                $createdProducts->push($product);
            }

            $this->command->info("Created/Updated " . $createdProducts->count() . " real products.");

            // 5. Create Orders
            // Create 5 random orders
            for ($i = 0; $i < 5; $i++) {
                $order = Order::factory()->create();

                // Add 1-3 items to each order using the real products
                $orderProducts = $createdProducts->random(rand(2, 4));

                foreach ($orderProducts as $product) {
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'price' => $product->price,
                        'quantity' => rand(1, 2),
                    ]);
                }

                $total = $order->items->sum(fn($item) => $item->price * $item->quantity);
                $order->update(['total' => $total]);
            }

            $this->command->info("Created 5 orders with real products.");
        });
    }
}
