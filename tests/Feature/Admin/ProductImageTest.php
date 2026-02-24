<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Create an admin user
    $this->admin = User::factory()->create(['role_id' => 1]); // Assuming role_id 1 is Admin

    // Create a category
    $category = Category::factory()->create();

    // Create a product
    $this->product = Product::factory()->create([
        'category_id' => $category->id,
        'vendor_id' => $this->admin->id,
    ]);
});

test('admin can delete a product gallery image', function () {
    // Fake storage
    $filename = 'test_image_' . uniqid() . '.jpg';
    $directory = public_path('img/products/gallery');

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    $path = $directory . '/' . $filename;
    file_put_contents($path, 'fake image content');

    // Create ProductImage record
    $image = ProductImage::create([
        'product_id' => $this->product->id,
        'image_path' => 'img/products/gallery/' . $filename,
    ]);

    $this->assertDatabaseHas('product_images', ['id' => $image->id]);
    $this->assertTrue(File::exists($path));

    // Act: Send DELETE request
    $this->actingAs($this->admin)
        ->delete(route('admin.products.images.destroy', $image->id))
        ->assertRedirect()
        ->assertSessionHas('success', 'Image deleted successfully.');

    $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
    $this->assertFalse(File::exists($path));
});
