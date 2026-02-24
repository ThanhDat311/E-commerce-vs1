<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = User::factory()->create(['role_id' => 1]);
    $this->category = Category::factory()->create();
});

it('can upload a product with image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('test-product.jpg');

    $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
        'name' => 'Test Product',
        'sku' => 'TEST-001',
        'price' => 100,
        'quantity' => 10,
        'category_id' => $this->category->id,
        'description' => 'Test Description',
        'image' => $file,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'name' => 'Test Product',
    ]);
});
