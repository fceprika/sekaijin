@extends('layout')

@section('title', 'Écrire un nouvel article - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">✍️ Écrire un nouvel article</h1>
            <p class="text-gray-600">Partagez votre expérience avec la communauté française expatriée</p>
        </div>

        <!-- Form -->
        <form action="{{ route('articles.store') }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre de l'article <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
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
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
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
                            <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>
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
                          required>{{ old('excerpt') }}</textarea>
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
                          required>{{ old('content') }}</textarea>
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
                       value="{{ old('reading_time') }}"
                       min="1"
                       max="60"
                       class="w-full md:w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="5">
                @error('reading_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">À propos des brouillons</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>• Votre article sera sauvegardé en mode brouillon</p>
                            <p>• Vous pourrez le modifier et le prévisualiser à tout moment</p>
                            <p>• Un administrateur devra le valider avant publication</p>
                        </div>
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
                    Créer le brouillon
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