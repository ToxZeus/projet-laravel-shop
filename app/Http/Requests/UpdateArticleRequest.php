<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'id'          => 'required|integer|exists:articles,id_article',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'category_id' => 'required|integer|exists:categories,id_category',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Le titre est obligatoire.',
            'category_id.required' => 'Veuillez choisir une catégorie.',
            'category_id.exists'   => 'La catégorie sélectionnée n\'existe pas.',
            'price.min'            => 'Le prix doit être positif.',
            'image.image'          => 'Le fichier doit être une image.',
            'image.max'            => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }
}
