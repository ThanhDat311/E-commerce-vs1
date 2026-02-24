<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = User::where('role_id', 4)->first() ?? User::factory()->create(['role_id' => 4]);

        $categories = Category::all()->pluck('id', 'slug');

        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'category_slug' => 'smartphones-tablets',
                'price' => 1199,
                'image_url' => 'img/products/1771414452_iphone-15-pro-max-titan-xanh-2-638629415445427350-750x500.jpg',
                'sku' => 'IP15PM-BLUE',
                'stock_quantity' => 50,
                'description' => 'The latest iPhone with Titanium design.',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'category_slug' => 'smartphones-tablets',
                'price' => 1299,
                'image_url' => 'img/products/1771416024_samsung-galaxy-s24-ultra-xam-1-750x500.jpg',
                'sku' => 'S24U-GREY',
                'stock_quantity' => 100,
                'description' => 'Samsung flagship with Galaxy AI.',
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'category_slug' => 'audio-headphones',
                'price' => 349,
                'image_url' => 'img/products/1771416282_619G-Sa2NSL._AC_SX522_.jpg',
                'sku' => 'SONY-WH5',
                'stock_quantity' => 100,
                'description' => 'Industry-leading noise canceling headphones.',
            ],
            [
                'name' => 'MacBook Air M2',
                'category_slug' => 'laptops-computers',
                'price' => 999,
                'image_url' => 'img/products/1771417342_41H8TjeAcwL._AC_SL1200_.jpg',
                'sku' => 'MBA-M2',
                'stock_quantity' => 30,
                'description' => 'Supercharged by Apple M2 chip.',
            ],
            [
                'name' => 'Canon EOS R6 Mark II',
                'category_slug' => 'cameras-photography',
                'price' => 2499,
                'image_url' => 'img/products/1771417500_61Ks9X44eVL._AC_SL1181_.jpg',
                'sku' => 'CANON-R6M2',
                'stock_quantity' => 15,
                'description' => 'Full-frame mirrorless camera for professionals.',
            ],
            // Adding more based on files found
            [
                'name' => 'Premium Leather Bag',
                'category_slug' => 'bags-accessories',
                'price' => 150,
                'image_url' => 'img/products/1771417709_81n1T4CYfmL._AC_SL1500_.jpg',
                'sku' => 'BAG-LTHR-01',
                'stock_quantity' => 45,
                'description' => 'Stylish and durable leather bag.',
            ],
            [
                'name' => 'Mechanical Keyboard',
                'category_slug' => 'electronics',
                'price' => 120,
                'image_url' => 'img/products/1771417817_61BGLYEN-xL._AC_SL1500_.jpg',
                'sku' => 'KBD-MECH-01',
                'stock_quantity' => 60,
                'description' => 'RGB backlit mechanical keyboard.',
            ],
        ];

        foreach ($products as $data) {
            $categorySlug = $data['category_slug'];
            unset($data['category_slug']);

            DB::table('products')->updateOrInsert(
                ['sku' => $data['sku']],
                array_merge($data, [
                    'slug' => Str::slug($data['name']),
                    'vendor_id' => $vendor->id,
                    'category_id' => $categories[$categorySlug] ?? $categories['electronics'],
                    'is_new' => true,
                    'is_featured' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
