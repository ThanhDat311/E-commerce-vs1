<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cannot delete category with soft deleted products', function () {
    // Authenticate as admin
    $admin = User::factory()->create(['role_id' => 1]);
    $this->actingAs($admin);

    // Create category
    $category = Category::factory()->create();

    // Create product in category
    $product = Product::factory()->create([
        'category_id' => $category->id,
    ]);

    // Soft delete the product
    $product->delete();

    // Confirm product is soft deleted
    expect($product->fresh()->trashed())->toBeTrue();

    // Attempt to verify that we CANNOT delete the category yet (due to foreign key)
    // or rather, we want to verify that our controller PREVENTS it gracefully.
    // Current behavior: It throws QueryException.
    // Desired behavior: It redirects back with error.

    // So we can assert that the session has errors or specific behavior.
    // For now, let's just try to call the destroy route.

    $response = $this->delete(route('admin.categories.destroy', $category));

    // After fix, this should be true:
    $response->assertSessionHas('error', 'Cannot delete category containing 1 products (including trash).');

    // And category should still exist
    expect(Category::find($category->id))->not->toBeNull();
});

test('can delete category with no products', function () {
    // Authenticate as admin
    $admin = User::factory()->create(['role_id' => 1]);
    $this->actingAs($admin);

    // Create category
    $category = Category::factory()->create();

    $response = $this->delete(route('admin.categories.destroy', $category));

    $response->assertSessionHas('success', 'Category deleted successfully.');
    expect(Category::find($category->id))->toBeNull();
});
