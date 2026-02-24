<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'discount_type' => ['required', 'in:percent,fixed,bogo,flash'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            'apply_scope' => ['required', 'in:product,category,vendor,global'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'status' => ['nullable', 'in:draft,pending,active,expired'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['exists:products,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Deal name is required.',
            'discount_type.required' => 'Discount type is required.',
            'discount_type.in' => 'Discount type must be: percent, fixed, bogo, or flash.',
            'discount_value.required' => 'Discount value is required.',
            'discount_value.min' => 'Discount value cannot be negative.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after' => 'End date must be after the start date.',
            'apply_scope.required' => 'Apply scope is required.',
            'apply_scope.in' => 'Apply scope must be: product, category, vendor, or global.',
        ];
    }
}
