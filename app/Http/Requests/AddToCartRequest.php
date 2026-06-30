<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'article_id' => 'required|integer|exists:articles,id_article',
            'quantity'   => 'required|integer|min:1|max:999',
        ];
    }

    public function messages(): array
    {
        return [
            'article_id.exists' => 'Cet article n\'existe pas.',
            'quantity.min'      => 'La quantité doit être d\'au moins 1.',
            'quantity.max'      => 'La quantité ne peut pas dépasser 999.',
        ];
    }
}
