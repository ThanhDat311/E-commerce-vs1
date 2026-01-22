<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Quan trọng để dùng Rule::unique

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Lấy ID sản phẩm đang được edit từ route
        // Ví dụ route: admin/products/{product} -> tham số là 'product'
        $product = $this->route('product'); 
        $productId = $product->id ?? $product; // Lấy ID an toàn

        return [
            // Name: Bắt buộc, Unique trong bảng products, nhưng BỎ QUA dòng có id = $productId
            'name'           => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('products', 'name')->ignore($productId)
            ],
            
            // SKU: Tương tự Name
            'sku'            => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('products', 'sku')->ignore($productId)
            ],
            
            'price'          => ['required', 'numeric', 'min:0'],
            'sale_price'     => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'category_id'    => ['required', 'exists:categories,id'],
            'description'    => ['nullable', 'string'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_featured'    => ['sometimes', 'boolean'],
            'is_new'         => ['sometimes', 'boolean'],
        ];
    }
}