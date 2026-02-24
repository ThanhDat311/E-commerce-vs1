<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Automotive', 'image_url' => 'img/categories/1771345187_automotive.png'],
            ['name' => 'Bags & Accessories', 'image_url' => 'img/categories/1771345255_bags-accessories.png'],
            ['name' => 'Beauty & Personal Care', 'image_url' => 'img/categories/1771345297_beauty-personal-care.png'],
            ['name' => 'Audio & Headphones', 'image_url' => 'img/categories/1771345333_audio-headphones.png'],
            ['name' => 'Books', 'image_url' => 'img/categories/1771345376_books.png'],
            ['name' => 'Media', 'image_url' => 'img/categories/1771345505_media.png'],
            ['name' => 'Cameras & Photography', 'image_url' => 'img/categories/1771345545_cameras-photography.png'],
            ['name' => 'Fashion', 'image_url' => 'img/categories/1771345588_fashion.png'],
            ['name' => 'Fitness & Gym', 'image_url' => 'img/categories/1771345614_fitness-gym.png'],
            ['name' => 'Electronics', 'image_url' => 'img/categories/1771345614_fitness-gym.png'], // Reusing some if needed, but let's be more precise
            ['name' => 'Home & Living', 'image_url' => 'img/categories/1771412458_home-living.png'],
            ['name' => 'Furniture', 'image_url' => 'img/categories/1771345759_furniture.png'],
            ['name' => 'Home Decor', 'image_url' => 'img/categories/1771412553_home-decor.png'],
            ['name' => 'Kitchen & Dining', 'image_url' => 'img/categories/1771412588_kitchen-dining.png'],
            ['name' => 'Smartphones & Tablets', 'image_url' => 'img/categories/1771413046_smartphones-tablets.png'],
            ['name' => 'Laptops & Computers', 'image_url' => 'img/categories/1771413060_laptops-computers.png'],
        ];

        // Ensure Electronics uses the correct image if available
        foreach ($categories as &$category) {
            if ($category['name'] === 'Electronics') {
                $category['image_url'] = 'img/categories/1771345661_electronics.png';
            }
        }

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'image_url' => $category['image_url'],
                    'description' => "Explore our range of {$category['name']} products.",
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
