@extends('layout')

@section('title', 'Modifier l\'article - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">✏️ Modifier l'article</h1>
                    <p class="text-gray-600">Modifiez votre article et prévisualisez les changements</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($article->is_published)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            Publié
                        </span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                            Brouillon
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Actions Bar -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('articles.preview', $article) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Prévisualiser
                    </a>
                    @if(auth()->user()->isAdmin() && !$article->is_published)
                        <form action="{{ route('admin.articles.publish', $article) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir publier cet article ?')"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Publier
                            </button>
                        </form>
                    @endif
                </div>
                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.')"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('articles.update', $article) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre de l'article <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $article->title) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Mon expérience en tant qu'expatrié..."
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Country and Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Country -->
                <div>
                    <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pays <span class="text-red-500">*</span>
                    </label>
                    <select id="country_id" 
                            name="country_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Sélectionnez un pays</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $article->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->emoji }} {{ $country->name_fr }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category', $article->category) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Excerpt -->
            <div class="mb-6">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                    Résumé <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-500">(500 caractères max)</span>
                </label>
                <textarea id="excerpt" 
                          name="excerpt" 
                          rows="3"
                          maxlength="500"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Un court résumé de votre article qui donnera envie de le lire..."
                          required>{{ old('excerpt', $article->excerpt) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">
                    <span id="excerpt-count">0</span>/500 caractères
                </p>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Contenu de l'article <span class="text-red-500">*</span>
                </label>
                <textarea id="content" 
                          name="content" 
                          rows="15"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Écrivez votre article ici..."
                          required>{{ old('content', $article->content) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">
                    Conseil : Utilisez des paragraphes courts pour une meilleure lisibilité
                </p>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Reading Time (Optional) -->
            <div class="mb-6">
                <label for="reading_time" class="block text-sm font-medium text-gray-700 mb-2">
                    Temps de lecture estimé (minutes)
                    <span class="text-xs text-gray-500">(optionnel - calculé automatiquement si vide)</span>
                </label>
                <input type="number" 
                       id="reading_time" 
                       name="reading_time" 
                       value="{{ old('reading_time', $article->reading_time) }}"
                       min="1"
                       max="60"
                       class="w-full md:w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="5">
                @error('reading_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Metadata -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Créé le :</span> {{ $article->created_at->format('d/m/Y à H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Modifié le :</span> {{ $article->updated_at->format('d/m/Y à H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Vues :</span> {{ $article->views ?? 0 }}
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center">
                <a href="{{ route('articles.my-articles') }}" 
                   class="text-gray-600 hover:text-gray-800 font-medium">
                    ← Retour à mes articles
                </a>
                <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Character counter for excerpt
const excerptTextarea = document.getElementById('excerpt');
const excerptCount = document.getElementById('excerpt-count');

function updateExcerptCount() {
    const count = excerptTextarea.value.length;
    excerptCount.textContent = count;
    
    if (count > 450) {
        excerptCount.classList.add('text-orange-500');
        excerptCount.classList.remove('text-gray-500', 'text-red-500');
    } else if (count >= 500) {
        excerptCount.classList.add('text-red-500');
        excerptCount.classList.remove('text-gray-500', 'text-orange-500');
    } else {
        excerptCount.classList.add('text-gray-500');
        excerptCount.classList.remove('text-orange-500', 'text-red-500');
    }
}

excerptTextarea.addEventListener('input', updateExcerptCount);
updateExcerptCount(); // Initial count
</script>
@endsection