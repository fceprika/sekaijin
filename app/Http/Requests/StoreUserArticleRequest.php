<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'title' => [
                'required',
                'string',
                'max:' . config('content.settings.title_max_length', 255),
            ],
            'excerpt' => [
                'required',
                'string',
                'max:' . config('content.settings.excerpt_max_length', 500),
            ],
            'content' => [
                'required',
                'string',
                'min:100', // Minimum content length for quality
            ],
            'category' => [
                'required',
                'string',
                'in:' . implode(',', array_keys(config('content.article_categories'))),
            ],
            'country_id' => [
                'required',
                'exists:countries,id',
            ],
            'reading_time' => [
                'nullable',
                'integer',
                'min:1',
                'max:' . config('content.settings.max_reading_time', 60),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de l\'article est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser :max caractères.',
            'excerpt.required' => 'Un résumé de l\'article est obligatoire.',
            'excerpt.max' => 'Le résumé ne peut pas dépasser :max caractères.',
            'content.required' => 'Le contenu de l\'article est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins :min caractères pour assurer la qualité.',
            'category.required' => 'Veuillez sélectionner une catégorie.',
            'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'country_id.required' => 'Veuillez sélectionner un pays.',
            'country_id.exists' => 'Le pays sélectionné n\'existe pas.',
            'reading_time.integer' => 'Le temps de lecture doit être un nombre entier.',
            'reading_time.min' => 'Le temps de lecture doit être d\'au moins :min minute.',
            'reading_time.max' => 'Le temps de lecture ne peut pas dépasser :max minutes.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'titre',
            'excerpt' => 'résumé',
            'content' => 'contenu',
            'category' => 'catégorie',
            'country_id' => 'pays',
            'reading_time' => 'temps de lecture',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from text fields (excluding content to prevent corruption)
        $this->merge([
            'title' => trim($this->title ?? ''),
            'excerpt' => trim($this->excerpt ?? ''),
            // Note: content field is excluded from trimming to prevent data corruption
        ]);
    }
}