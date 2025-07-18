<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Any authenticated user can create articles
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('articles', 'slug')->ignore($this->route('article')),
            ],
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string|min:200',
            'category' => 'required|in:témoignage,guide-pratique,travail,lifestyle,cuisine',
            'country_id' => 'required|exists:countries,id',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'reading_time' => 'nullable|integer|min:1|max:120',
            'image_url' => 'nullable|url|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:512', // 512KB max
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'slug.required' => 'Le slug est obligatoire.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            'slug.regex' => 'Le slug ne peut contenir que des lettres minuscules, chiffres et tirets.',
            'excerpt.required' => 'Le résumé est obligatoire.',
            'excerpt.max' => 'Le résumé ne peut pas dépasser 500 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins 200 caractères.',
            'category.required' => 'La catégorie est obligatoire.',
            'category.in' => 'La catégorie doit être : témoignage, guide-pratique, travail, lifestyle ou cuisine.',
            'country_id.required' => 'Le pays est obligatoire.',
            'country_id.exists' => 'Le pays sélectionné n\'existe pas.',
            'reading_time.integer' => 'Le temps de lecture doit être un nombre entier.',
            'reading_time.min' => 'Le temps de lecture doit être d\'au moins 1 minute.',
            'reading_time.max' => 'Le temps de lecture ne peut pas dépasser 120 minutes.',
            'image_url.url' => 'L\'URL de l\'image doit être valide.',
            'image_url.max' => 'L\'URL de l\'image ne peut pas dépasser 2048 caractères.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG, GIF ou WebP.',
            'image.max' => 'L\'image ne doit pas dépasser 500KB.',
        ];
    }
}
