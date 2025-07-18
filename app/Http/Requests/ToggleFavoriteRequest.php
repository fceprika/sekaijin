<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleFavoriteRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::in(['article', 'news'])],
            'id' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $type = $this->input('type');

                    if ($type === 'article') {
                        $exists = \App\Models\Article::where('id', $value)
                            ->where('is_published', true)
                            ->exists();
                    } elseif ($type === 'news') {
                        $exists = \App\Models\News::where('id', $value)
                            ->where('is_published', true)
                            ->exists();
                    } else {
                        $exists = false;
                    }

                    if (! $exists) {
                        $fail('Le contenu sélectionné n\'existe pas ou n\'est pas publié.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Le type de contenu est requis.',
            'type.in' => 'Le type de contenu doit être article ou actualité.',
            'id.required' => 'L\'identifiant du contenu est requis.',
            'id.integer' => 'L\'identifiant doit être un nombre entier.',
            'id.min' => 'L\'identifiant doit être positif.',
        ];
    }

    /**
     * Get the model class based on the type.
     */
    public function getModelClass(): string
    {
        return $this->input('type') === 'article'
            ? \App\Models\Article::class
            : \App\Models\News::class;
    }

    /**
     * Get the validated content item.
     */
    public function getContentItem()
    {
        $modelClass = $this->getModelClass();

        return $modelClass::where('id', $this->input('id'))
            ->where('is_published', true)
            ->firstOrFail();
    }
}
