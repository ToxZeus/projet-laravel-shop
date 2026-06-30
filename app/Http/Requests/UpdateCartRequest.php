<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1|max:999',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.min' => 'La quantité doit être d\'au moins 1.',
            'quantity.max' => 'La quantité ne peut pas dépasser 999.',
        ];
    }
}
