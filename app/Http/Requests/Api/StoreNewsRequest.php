<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by API middleware
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
            'summary' => 'required|string|max:1000',
            'content' => 'required|string',
            'thumbnail_url' => 'nullable|string|url',
            'author_id' => 'required|integer|exists:users,id',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'country_id' => 'nullable|integer|exists:countries,id',
            'category' => 'nullable|string|in:general,administrative,vie-pratique,culture,economie,lifestyle,cuisine',
            'is_featured' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'summary.required' => 'Le résumé est obligatoire.',
            'summary.max' => 'Le résumé ne peut pas dépasser 1000 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'thumbnail_url.url' => 'L\'URL de l\'image doit être valide.',
            'author_id.required' => 'L\'ID de l\'auteur est obligatoire.',
            'author_id.exists' => 'L\'auteur spécifié n\'existe pas.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être "draft" ou "published".',
            'tags.array' => 'Les tags doivent être sous forme de tableau.',
            'tags.*.max' => 'Chaque tag ne peut pas dépasser 50 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'titre',
            'summary' => 'résumé',
            'content' => 'contenu',
            'thumbnail_url' => 'URL de l\'image',
            'author_id' => 'ID de l\'auteur',
            'status' => 'statut',
            'tags' => 'tags',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
