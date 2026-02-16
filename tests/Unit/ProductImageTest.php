<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    public function test_image_url_accessor_converts_relative_path()
    {
        $product = new Product(['image_url' => 'img/test.png']);

        // asset() helper uses app.url content, usually http://localhost in tests
        $this->assertStringStartsWith('http', $product->image_url);
        $this->assertStringContainsString('img/test.png', $product->image_url);
    }

    public function test_image_url_accessor_keeps_absolute_path()
    {
        $url = 'https://example.com/img/test.png';
        $product = new Product(['image_url' => $url]);

        $this->assertEquals($url, $product->image_url);
    }

    public function test_image_url_accessor_handles_null()
    {
        $product = new Product(['image_url' => null]);

        $this->assertNull($product->image_url);
    }
}
