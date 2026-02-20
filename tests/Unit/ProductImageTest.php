<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    public function test_image_url_accessor_converts_relative_path_with_storage_prefix(): void
    {
        $product = new Product(['image_url' => 'products/test.png']);

        $this->assertStringStartsWith('http', $product->image_url);
        $this->assertStringContainsString('storage/products/test.png', $product->image_url);
    }

    public function test_image_url_accessor_handles_old_img_path(): void
    {
        $product = new Product(['image_url' => 'img/products/test.png']);

        $this->assertStringStartsWith('http', $product->image_url);
        $this->assertStringContainsString('img/products/test.png', $product->image_url);
        $this->assertStringNotContainsString('storage/', $product->image_url);
    }

    public function test_image_url_accessor_keeps_absolute_path(): void
    {
        $url = 'https://example.com/img/test.png';
        $product = new Product(['image_url' => $url]);

        $this->assertEquals($url, $product->image_url);
    }

    public function test_image_url_accessor_handles_null(): void
    {
        $product = new Product(['image_url' => null]);

        $this->assertNull($product->image_url);
    }
}
