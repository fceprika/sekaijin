@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">✍️ Écrire un nouvel article</h1>
            <p class="text-gray-600">Partagez votre expérience d'expatrié avec la communauté Sekaijin.</p>
        </div>

        <form action="{{ route('articles.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Titre -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre de l'article *
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                       placeholder="Ex: Mon premier mois en Thaïlande"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pays et Catégorie -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pays *
                    </label>
                    <select id="country_id" 
                            name="country_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            required>
                        <option value="">Sélectionnez un pays</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->emoji }} {{ $country->name_fr }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie *
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Résumé -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                    Résumé de l'article *
                </label>
                <textarea id="excerpt" 
                          name="excerpt" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                          placeholder="Décrivez brièvement le contenu de votre article..."
                          required>{{ old('excerpt') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Maximum 500 caractères</p>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contenu -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Contenu de l'article *
                </label>
                <textarea id="content" 
                          name="content" 
                          rows="15"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                          placeholder="Racontez votre expérience, partagez vos conseils..."
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Temps de lecture -->
            <div>
                <label for="reading_time" class="block text-sm font-medium text-gray-700 mb-2">
                    Temps de lecture (optionnel)
                </label>
                <input type="number" 
                       id="reading_time" 
                       name="reading_time" 
                       value="{{ old('reading_time') }}"
                       min="1" 
                       max="60"
                       class="w-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                       placeholder="5">
                <span class="ml-2 text-sm text-gray-500">minutes (calculé automatiquement si vide)</span>
                @error('reading_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Votre article sera sauvegardé en mode brouillon et nécessitera l'approbation d'un administrateur avant publication.
                </div>
                
                <div class="flex space-x-4">
                    <a href="{{ url()->previous() }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Créer l'article
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection