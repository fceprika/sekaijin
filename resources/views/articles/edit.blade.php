@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">✏️ Modifier l'article</h1>
            <p class="text-gray-600">Apportez les modifications nécessaires à votre article.</p>
        </div>

        <form action="{{ route('articles.update', $article) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Titre -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre de l'article *
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $article->title) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
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
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ (old('country_id', $article->country_id) == $country->id) ? 'selected' : '' }}>
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
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ (old('category', $article->category) == $key) ? 'selected' : '' }}>
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
                          required>{{ old('excerpt', $article->excerpt) }}</textarea>
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
                          required>{{ old('content', $article->content) }}</textarea>
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
                       value="{{ old('reading_time', $article->reading_time) }}"
                       min="1" 
                       max="60"
                       class="w-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                <span class="ml-2 text-sm text-gray-500">minutes</span>
                @error('reading_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    @if($article->is_published)
                        Cet article est publié et visible par tous.
                    @else
                        Cet article est en mode brouillon et nécessite l'approbation d'un administrateur.
                    @endif
                </div>
                
                <div class="flex space-x-4">
                    <a href="{{ route('articles.my-articles') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                        Retour aux articles
                    </a>
                    <a href="{{ route('articles.preview', $article) }}" 
                       class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200 flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        Aperçu
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection