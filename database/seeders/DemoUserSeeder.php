<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // Demo Admin User
        DB::table('users')->insert([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => 1,
            'phone_number' => '+1-555-0101',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Demo Staff User
        DB::table('users')->insert([
            'name' => 'Staff Demo',
            'email' => 'staff@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => 2,
            'phone_number' => '+1-555-0102',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Demo Customer User
        DB::table('users')->insert([
            'name' => 'Customer Demo',
            'email' => 'customer@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => 3,
            'phone_number' => '+1-555-0103',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Demo Vendor User
        DB::table('users')->insert([
            'name' => 'Vendor Demo',
            'email' => 'vendor@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => 4,
            'phone_number' => '+1-555-0104',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Additional Demo Customers
        $customers = [
            ['name' => 'John Smith', 'email' => 'john@example.com', 'phone' => '+1-555-0201'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah@example.com', 'phone' => '+1-555-0202'],
            ['name' => 'Mike Wilson', 'email' => 'mike@example.com', 'phone' => '+1-555-0203'],
            ['name' => 'Emily Davis', 'email' => 'emily@example.com', 'phone' => '+1-555-0204'],
            ['name' => 'David Brown', 'email' => 'david@example.com', 'phone' => '+1-555-0205'],
        ];

        foreach ($customers as $customer) {
            DB::table('users')->insert([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role_id' => 3,
                'phone_number' => $customer['phone'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Additional Demo Vendors
        $vendors = [
            ['name' => 'TechStore Pro', 'email' => 'techstore@example.com', 'phone' => '+1-555-0301'],
            ['name' => 'Fashion Hub', 'email' => 'fashionhub@example.com', 'phone' => '+1-555-0302'],
            ['name' => 'Home & Garden', 'email' => 'homegarden@example.com', 'phone' => '+1-555-0303'],
        ];

        foreach ($vendors as $vendor) {
            DB::table('users')->insert([
                'name' => $vendor['name'],
                'email' => $vendor['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role_id' => 4,
                'phone_number' => $vendor['phone'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}