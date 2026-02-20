<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    $this->admin = User::factory()->create(['role_id' => 1]);
    $category = Category::factory()->create();

    $this->product = Product::factory()->create([
        'category_id' => $category->id,
        'vendor_id' => $this->admin->id,
    ]);
});

test('admin can delete a product gallery image', function () {
    $filename = 'test_image_'.uniqid().'.jpg';
    Storage::disk('public')->put('products/gallery/'.$filename, 'fake image content');

    $image = ProductImage::create([
        'product_id' => $this->product->id,
        'image_path' => 'products/gallery/'.$filename,
    ]);

    $this->assertDatabaseHas('product_images', ['id' => $image->id]);
    Storage::disk('public')->assertExists('products/gallery/'.$filename);

    $this->actingAs($this->admin)
        ->delete(route('admin.products.images.destroy', $image->id))
        ->assertRedirect()
        ->assertSessionHas('success', 'Image deleted successfully.');

    $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
    Storage::disk('public')->assertMissing('products/gallery/'.$filename);
});
