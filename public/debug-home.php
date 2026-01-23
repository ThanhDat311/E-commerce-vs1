<?php

/**
 * Debug script to verify the home page product display issue
 * Place this file at: public/debug-home.php
 * Access at: http://localhost:8080/debug-home.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "=== HOME PAGE PRODUCT DISPLAY DEBUG ===\n\n";

// 1. Check database products
echo "1. DATABASE CHECK:\n";
$totalCount = DB::table('products')->count();
echo "   Total products in DB: $totalCount\n";

if ($totalCount === 0) {
    echo "   ❌ NO PRODUCTS IN DATABASE - Seeding needed!\n";
} else {
    echo "   ✓ Products exist in database\n";
}

echo "\n";

// 2. Check Product Model query
echo "2. PRODUCT MODEL QUERY (HomeController method):\n";
$newProducts = Product::latest()->take(8)->get();
echo "   Query: Product::latest()->take(8)->get()\n";
echo "   Results: " . $newProducts->count() . " products\n";

if ($newProducts->isEmpty()) {
    echo "   ❌ ISSUE FOUND: Query returned empty collection!\n";
    echo "   Debug info:\n";
    $sqlQuery = Product::latest()->take(8)->toSql();
    $bindings = Product::latest()->take(8)->getBindings();
    echo "   SQL: $sqlQuery\n";
    echo "   Bindings: " . json_encode($bindings) . "\n";
} else {
    echo "   ✓ Products retrieved successfully\n";
    echo "   Sample products:\n";
    foreach ($newProducts->take(3) as $p) {
        echo "      - ID: {$p->id}, Name: {$p->name}, Image: {$p->image_url}\n";
    }
}

echo "\n";

// 3. Check component rendering
echo "3. COMPONENT DATA FLOW:\n";
echo "   Component: <x-product-grid :products=\"\$newProducts\" />\n";
echo "   Expected props: ['products' => Collection]\n";

if ($newProducts->isNotEmpty()) {
    echo "   ✓ Will pass " . $newProducts->count() . " products to component\n";
} else {
    echo "   ❌ Empty collection passed to component\n";
}

echo "\n";

// 4. Check view file structure
echo "4. VIEW STRUCTURE CHECK:\n";
echo "   File: resources/views/home.blade.php\n";
$viewPath = base_path('resources/views/home.blade.php');
if (file_exists($viewPath)) {
    echo "   ✓ View file exists\n";
    $content = file_get_contents($viewPath);
    if (strpos($content, 'x-product-grid') !== false) {
        echo "   ✓ Component included in view\n";
    } else {
        echo "   ❌ Component NOT found in view\n";
    }
} else {
    echo "   ❌ View file NOT found!\n";
}

echo "\n";

// 5. Check component file
echo "5. PRODUCT-GRID COMPONENT CHECK:\n";
$componentPath = base_path('resources/views/components/product-grid.blade.php');
if (file_exists($componentPath)) {
    echo "   ✓ Component file exists\n";
    $componentContent = file_get_contents($componentPath);
    if (strpos($componentContent, '@forelse') !== false) {
        echo "   ✓ Component has proper loop logic\n";
    } else {
        echo "   ❌ Component missing loop logic\n";
    }
} else {
    echo "   ❌ Component file NOT found!\n";
}

echo "\n";

// 6. Check partial files
echo "6. PARTIAL FILES CHECK:\n";
$partialPath = base_path('resources/views/partials/product-item.blade.php');
if (file_exists($partialPath)) {
    echo "   ✓ product-item.blade.php exists\n";
} else {
    echo "   ❌ product-item.blade.php NOT found!\n";
}

echo "\n";

// 7. Check featured products
echo "7. FEATURED PRODUCTS (for Tab 2):\n";
$featuredProducts = Product::inRandomOrder()->take(8)->get();
echo "   Query: Product::inRandomOrder()->take(8)->get()\n";
echo "   Results: " . $featuredProducts->count() . " products\n";

echo "\n";

// 8. Final diagnosis
echo "8. DIAGNOSIS:\n";
if ($totalCount === 0) {
    echo "   ❌ ROOT CAUSE: No products in database\n";
    echo "   ✓ SOLUTION: Run 'php artisan db:seed'\n";
} elseif ($newProducts->isEmpty()) {
    echo "   ❌ ROOT CAUSE: Product query returns empty\n";
    echo "   ✓ Check: Global scopes affecting Product model\n";
    echo "   ✓ Check: Database permissions\n";
} else {
    echo "   ✓ NO ISSUES FOUND: All checks passed\n";
    echo "   Products are fetched correctly from DB\n";
    echo "   Issue may be:\n";
    echo "     - Frontend JavaScript not rendering\n";
    echo "     - CSS hiding elements\n";
    echo "     - Browser cache issue\n";
}

echo "\n=== END DEBUG ===\n";
