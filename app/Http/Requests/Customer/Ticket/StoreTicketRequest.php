<?php

namespace App\Http\Requests\Customer\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:order_issue,account,technical,billing,other'],
            'order_id' => ['nullable', 'exists:orders,id'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'message' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Please provide a subject for your ticket.',
            'category.required' => 'Please select a category.',
            'category.in' => 'Invalid category selected.',
            'order_id.exists' => 'The selected order does not exist.',
            'message.required' => 'Please describe your issue.',
        ];
    }
}
