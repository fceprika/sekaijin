<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Any authenticated user can create events
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
                Rule::unique('events', 'slug')->ignore($this->event)
            ],
            'description' => 'required|string|max:500',
            'full_description' => 'nullable|string|min:100',
            'category' => 'required|in:networking,conférence,culturel,gastronomie',
            'country_id' => 'required|exists:countries,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'required_unless:is_online,true|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_online' => 'boolean',
            'online_link' => 'required_if:is_online,true|nullable|url|max:2048',
            'price' => 'required|numeric|min:0|max:999999.99',
            'max_participants' => 'nullable|integer|min:1|max:10000',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'image_url' => 'nullable|url|max:2048',
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
            'description.required' => 'La description est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 500 caractères.',
            'full_description.min' => 'La description complète doit contenir au moins 100 caractères.',
            'category.required' => 'La catégorie est obligatoire.',
            'category.in' => 'La catégorie doit être : networking, conférence, culturel ou gastronomie.',
            'country_id.required' => 'Le pays est obligatoire.',
            'country_id.exists' => 'Le pays sélectionné n\'existe pas.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'start_date.after' => 'La date de début doit être dans le futur.',
            'end_date.date' => 'La date de fin doit être une date valide.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'location.required_unless' => 'Le lieu est obligatoire sauf pour les événements en ligne.',
            'location.max' => 'Le lieu ne peut pas dépasser 255 caractères.',
            'address.max' => 'L\'adresse ne peut pas dépasser 500 caractères.',
            'online_link.required_if' => 'Le lien en ligne est obligatoire pour les événements en ligne.',
            'online_link.url' => 'Le lien en ligne doit être une URL valide.',
            'online_link.max' => 'Le lien en ligne ne peut pas dépasser 2048 caractères.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'price.max' => 'Le prix ne peut pas dépasser 999999.99€.',
            'max_participants.integer' => 'Le nombre maximum de participants doit être un nombre entier.',
            'max_participants.min' => 'Le nombre maximum de participants doit être d\'au moins 1.',
            'max_participants.max' => 'Le nombre maximum de participants ne peut pas dépasser 10000.',
            'image_url.url' => 'L\'URL de l\'image doit être valide.',
            'image_url.max' => 'L\'URL de l\'image ne peut pas dépasser 2048 caractères.',
        ];
    }
}
