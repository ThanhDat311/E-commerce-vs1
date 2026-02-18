<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = $product->id ?? $product;

        return [
            'name'           => ['required', 'string', 'max:255'],
            'sku'            => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($productId)],
            'price'          => ['required', 'numeric', 'min:0.01'],
            'quantity'       => ['required', 'integer', 'min:0'],
            'category_id'    => ['nullable', 'exists:categories,id'],
            'description'    => ['required', 'string'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'gallery.*'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_new'         => ['nullable', 'boolean'],
            'is_featured'    => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Product name is required.',
            'name.string'        => 'Product name must be a string.',
            'name.max'           => 'Product name cannot exceed 255 characters.',

            'sku.unique'         => 'This SKU already exists. Please use a different SKU.',
            'sku.max'            => 'SKU cannot exceed 50 characters.',

            'price.required'     => 'Price is required.',
            'price.numeric'      => 'Price must be a number.',
            'price.min'          => 'Price must be greater than 0.',

            'quantity.required'  => 'Stock quantity is required.',
            'quantity.integer'   => 'Stock quantity must be an integer.',
            'quantity.min'       => 'Stock quantity cannot be negative.',

            'description.required' => 'Description is required.',
            'description.string'   => 'Description must be text.',

            'image.image'        => 'File must be an image.',
            'image.mimes'        => 'Image must be JPG, PNG, or GIF.',
            'image.max'          => 'Image cannot exceed 2MB.',
        ];
    }
}
