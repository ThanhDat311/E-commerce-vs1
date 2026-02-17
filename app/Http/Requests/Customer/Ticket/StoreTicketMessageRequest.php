<?php

namespace App\Http\Requests\Customer\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization handled in controller via policy
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'max:2048'], // 2MB max
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Please enter a message.',
            'attachment.max' => 'Attachment must not exceed 2MB.',
        ];
    }
}
