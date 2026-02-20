<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_multiple_product_images(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role_id' => 1]);
        $category = Category::create(['name' => 'Test Category', 'slug' => 'test-category']);

        $mainImage = UploadedFile::fake()->image('main.jpg');
        $gallery1 = UploadedFile::fake()->image('gallery1.jpg');
        $gallery2 = UploadedFile::fake()->image('gallery2.jpg');

        $response = $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Test Product',
                'description' => 'Test Description',
                'price' => 100,
                'quantity' => 10,
                'category_id' => $category->id,
                'image' => $mainImage,
                'gallery' => [$gallery1, $gallery2],
            ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $product = Product::first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->getRawOriginal('image_url'));

        // Verify file was stored in the public disk
        Storage::disk('public')->assertExists($product->getRawOriginal('image_url'));

        // Check gallery images
        $this->assertCount(2, $product->images);

        foreach ($product->images as $image) {
            Storage::disk('public')->assertExists($image->getRawOriginal('image_path'));
        }
    }

    public function test_vendor_can_upload_multiple_product_images(): void
    {
        Storage::fake('public');

        $vendor = User::factory()->create(['role_id' => 4]);
        $category = Category::create(['name' => 'Test Category', 'slug' => 'test-category']);

        $mainImage = UploadedFile::fake()->image('main.jpg');
        $gallery1 = UploadedFile::fake()->image('gallery1.jpg');

        $response = $this->actingAs($vendor)
            ->post(route('vendor.products.store'), [
                'name' => 'Vendor Product',
                'description' => 'Vendor Description',
                'price' => 50,
                'quantity' => 5,
                'category_id' => $category->id,
                'image' => $mainImage,
                'gallery' => [$gallery1],
            ]);

        $response->assertRedirect(route('vendor.products.index'));

        $product = Product::where('name', 'Vendor Product')->first();
        $this->assertNotNull($product);
        $this->assertCount(1, $product->images);
        $this->assertEquals($vendor->id, $product->vendor_id);

        // Verify storage
        Storage::disk('public')->assertExists($product->getRawOriginal('image_url'));
    }
}
