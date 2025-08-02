<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // API authentication is handled by Sanctum middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $articleId = $this->route('article')->id ?? null;

        return [
            'title' => 'sometimes|required|string|max:' . config('content.settings.title_max_length', 255),
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('articles', 'slug')->ignore($articleId),
            ],
            'excerpt' => 'sometimes|required|string|max:' . config('content.settings.excerpt_max_length', 500),
            'content' => 'sometimes|required|string|min:100',
            'category' => 'sometimes|nullable|string|in:' . implode(',', array_keys(config('content.article_categories'))),
            'image_url' => [
                'sometimes',
                'nullable',
                'string',
                'max:2048',
                'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            ],
            'country_id' => 'sometimes|nullable|integer|exists:countries,id',
            'author_id' => 'sometimes|required|integer|exists:users,id',
            'is_featured' => 'sometimes|nullable|boolean',
            'is_published' => 'sometimes|nullable|boolean',
            'published_at' => 'sometimes|nullable|date',
            'reading_time' => 'sometimes|nullable|integer|min:1|max:' . config('content.settings.max_reading_time', 60),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser :max caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            'excerpt.required' => 'Le résumé est obligatoire.',
            'excerpt.max' => 'Le résumé ne peut pas dépasser :max caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins :min caractères.',
            'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'image_url.regex' => 'L\'URL de l\'image n\'est pas valide.',
            'image_url.max' => 'L\'URL de l\'image est trop longue.',
            'country_id.exists' => 'Le pays sélectionné n\'existe pas.',
            'author_id.required' => 'L\'auteur est obligatoire.',
            'author_id.exists' => 'L\'auteur spécifié n\'existe pas.',
            'is_featured.boolean' => 'La valeur de mise en avant doit être vrai ou faux.',
            'is_published.boolean' => 'La valeur de publication doit être vrai ou faux.',
            'published_at.date' => 'La date de publication n\'est pas valide.',
            'reading_time.integer' => 'Le temps de lecture doit être un nombre entier.',
            'reading_time.min' => 'Le temps de lecture doit être d\'au moins :min minute.',
            'reading_time.max' => 'Le temps de lecture ne peut pas dépasser :max minutes.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $data = [];

        // Clean title and excerpt if provided
        if ($this->has('title')) {
            $data['title'] = trim($this->title);
        }

        if ($this->has('excerpt')) {
            $data['excerpt'] = trim($this->excerpt);
        }

        // Clean and normalize slug if provided
        if ($this->has('slug') && $this->slug) {
            $data['slug'] = \Illuminate\Support\Str::slug($this->slug);
        }

        // Handle YouTube URLs if image_url is provided
        if ($this->has('image_url') && $this->image_url) {
            $imageUrl = trim($this->image_url);

            // Check if it's a YouTube video URL
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $imageUrl, $matches)) {
                // Convert to YouTube thumbnail URL
                $videoId = $matches[1];
                $data['image_url'] = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
            }
        }

        if (! empty($data)) {
            $this->merge($data);
        }
    }
}
