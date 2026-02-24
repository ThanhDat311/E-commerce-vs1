<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::factory()->create(['role_id' => 1]); // Assuming role_id 1 is Admin

        // Create a category
        $category = Category::factory()->create();

        // Create a product
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'vendor_id' => $this->admin->id,
        ]);
    }

    /** @test */
    public function admin_can_delete_a_product_gallery_image()
    {
        // Fake storage (public disk usually, but here we use direct File facade as per controller)
        // Controller uses: File::exists(public_path($image->image_path))

        // Let's mock a file in public path
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
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.images.destroy', $image->id));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Image deleted successfully.');

        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        $this->assertFalse(File::exists($path));
    }
}
