<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder to demonstrate the Audit Log feature
 * 
 * Run with: php artisan db:seed --class=AuditLogDemoSeeder
 */
class AuditLogDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "\n=== Audit Log Demo Seeder ===\n";

        // Create test user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role_id' => 1,
                'is_active' => true,
            ]
        );

        echo "✓ Admin user ready: {$admin->name}\n";

        // Authenticate the admin user
        auth()->login($admin);

        // Demo 1: Create a product
        echo "\nDemo 1: Creating a product...\n";
        $product = Product::create([
            'category_id' => 1,
            'name' => 'Demo Product',
            'sku' => 'DEMO-SKU-001',
            'price' => 99.99,
            'sale_price' => 79.99,
            'stock_quantity' => 100,
            'description' => 'This is a demo product for testing audit logs',
            'is_new' => true,
            'is_featured' => true,
        ]);
        echo "✓ Product created: {$product->name} (ID: {$product->id})\n";

        // Demo 2: Update the product
        echo "\nDemo 2: Updating product price...\n";
        $product->update([
            'price' => 89.99,
            'sale_price' => 69.99,
            'stock_quantity' => 95,
        ]);
        echo "✓ Product updated\n";

        // Demo 3: Create an order
        echo "\nDemo 3: Creating an order...\n";
        $order = Order::create([
            'user_id' => $admin->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St, City, State 12345',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'vnpay',
            'total' => 199.99,
        ]);
        echo "✓ Order created: Order #{$order->id}\n";

        // Demo 4: Update order status
        echo "\nDemo 4: Updating order status...\n";
        $order->update([
            'order_status' => 'processing',
            'payment_status' => 'paid',
        ]);
        echo "✓ Order updated\n";

        // Demo 5: Update user
        echo "\nDemo 5: Updating user profile...\n";
        $admin->update([
            'phone_number' => '9876543210',
            'address' => '999 Test Ave, Demo City, DC 99999',
        ]);
        echo "✓ User updated\n";

        // Display audit logs
        echo "\n=== Audit Logs Created ===\n";
        $logs = AuditLog::latest()->limit(10)->get();

        foreach ($logs as $log) {
            $user = $log->user->name ?? 'Unknown';
            $model = class_basename($log->model_type);
            echo "ID: {$log->id} | Action: {$log->action} | User: {$user} | Model: {$model} #{$log->model_id}\n";
        }

        echo "\n=== Demo Complete ===\n";
        echo "✓ Visit /admin/audit-logs to see the audit log interface\n";
        echo "✓ " . AuditLog::count() . " audit logs created\n\n";
    }
}
