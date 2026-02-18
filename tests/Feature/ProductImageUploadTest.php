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

    public function test_admin_can_upload_multiple_product_images()
    {
        Storage::fake('public_uploads'); // Use the disk defined in filesystems.php if custom, or just 'public' if using default link

        // Assuming standard 'public' disk usage in controller: public_path('img/products')
        // We'll mock the filesystem structure or just rely on the controller logic which uses File::move
        // Since the controller uses `public_path` and `File::move`, testing with Storage::fake might be tricky 
        // because `public_path` points to real directory.
        // However, for testing purposes, we can try to verify the DB records mostly.

        // Let's create a partial mock or just verify DB records for now to avoid permission issues in test env

        $admin = User::factory()->create(['role_id' => 1]); // Admin
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
        $this->assertNotNull($product->image_url);

        // Check gallery images
        $this->assertCount(2, $product->images);

        // Clean up real files if any created (optional, but good practice if using real fs)
    }

    public function test_vendor_can_upload_multiple_product_images()
    {
        $vendor = User::factory()->create(['role_id' => 4]); // Vendor
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
    }
}
