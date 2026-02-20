<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        Storage::fake('public');

        $this->admin = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();

        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'vendor_id' => $this->admin->id,
        ]);
    }

    /** @test */
    public function admin_can_delete_a_product_gallery_image(): void
    {
        $filename = 'test_image_'.uniqid().'.jpg';
        Storage::disk('public')->put('products/gallery/'.$filename, 'fake image content');

        $image = ProductImage::create([
            'product_id' => $this->product->id,
            'image_path' => 'products/gallery/'.$filename,
        ]);

        $this->assertDatabaseHas('product_images', ['id' => $image->id]);
        Storage::disk('public')->assertExists('products/gallery/'.$filename);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.images.destroy', $image->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Image deleted successfully.');

        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing('products/gallery/'.$filename);
    }
}
