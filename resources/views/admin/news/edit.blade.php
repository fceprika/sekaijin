@extends('admin.layout')

@section('title', 'Modifier l\'Actualité')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Modifier l'Actualité</h1>
                <p class="text-gray-600 mt-2">{{ $news->title }}</p>
                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                    <span>
                        <i class="fas fa-user mr-1"></i>
                        {{ $news->author->name }}
                    </span>
                    <span>
                        <i class="fas fa-calendar mr-1"></i>
                        Créée le {{ $news->created_at->format('d/m/Y à H:i') }}
                    </span>
                    @if($news->updated_at != $news->created_at)
                        <span>
                            <i class="fas fa-edit mr-1"></i>
                            Modifiée le {{ $news->updated_at->format('d/m/Y à H:i') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="previewNews()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-eye mr-2"></i>
                    Prévisualisation
                </button>
                @if($news->is_published)
                    <a href="{{ route('country.news.show', [$news->country->slug, $news->slug]) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir en ligne
                    </a>
                @endif
                <a href="{{ route('admin.news') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.news.update', $news->id) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Informations de base -->
            <div class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de base</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de l'actualité *
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $news->title) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                               placeholder="Saisissez le titre de votre actualité...">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug URL *
                        </label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $news->slug) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                               placeholder="url-de-lactualite">
                        <p class="text-xs text-gray-500 mt-1">Utilisé dans l'URL de l'actualité</p>
                    </div>

                    <!-- Pays -->
                    <div>
                        <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pays *
                        </label>
                        <select id="country_id" name="country_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">
                            <option value="">Sélectionnez un pays</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $news->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->emoji }} {{ $country->name_fr }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Catégorie *
                        </label>
                        <select id="category" name="category" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $news->category) === $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- URL d'image -->
                    <div class="lg:col-span-2">
                        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                            URL de l'image
                        </label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $news->image_url) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                               placeholder="https://exemple.com/image.jpg">
                    </div>

                    <!-- Résumé -->
                    <div class="lg:col-span-2">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                            Résumé de l'actualité *
                        </label>
                        <textarea id="excerpt" name="excerpt" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                                  placeholder="Rédigez un résumé accrocheur de votre actualité...">{{ old('excerpt', $news->excerpt) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 500 caractères</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Contenu de l'actualité</h2>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Contenu *
                </label>
                <textarea id="content" name="content" class="wysiwyg" required>{{ old('content', $news->content) }}</textarea>
                <p class="text-xs text-gray-500 mt-2">Minimum 100 caractères. Utilisez l'éditeur pour formater votre contenu.</p>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistiques</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Vues</p>
                            <p class="text-2xl font-bold text-purple-800">{{ $news->views ?? 0 }}</p>
                        </div>
                        <i class="fas fa-eye text-purple-600 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Statut</p>
                            <p class="text-lg font-bold text-blue-800">
                                @if($news->is_published)
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Publiée
                                @else
                                    <i class="fas fa-edit mr-1"></i>
                                    Brouillon
                                @endif
                            </p>
                        </div>
                        <i class="fas fa-{{ $news->is_published ? 'globe' : 'lock' }} text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Options de publication -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Options de publication</h2>
            
            <div class="space-y-4">
                <!-- Actualité en vedette -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_featured" name="is_featured" type="checkbox" value="1" 
                               {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                    </div>
                    <div class="ml-3">
                        <label for="is_featured" class="text-sm font-medium text-gray-700">
                            Actualité en vedette
                        </label>
                        <p class="text-xs text-gray-500">Mettre cette actualité en avant sur la page d'accueil</p>
                    </div>
                </div>

                <!-- Publiée -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_published" name="is_published" type="checkbox" value="1" 
                               {{ old('is_published', $news->is_published) ? 'checked' : '' }}
                               class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                    </div>
                    <div class="ml-3">
                        <label for="is_published" class="text-sm font-medium text-gray-700">
                            Actualité publiée
                        </label>
                        <p class="text-xs text-gray-500">
                            @if($news->is_published)
                                Actualité actuellement visible publiquement
                            @else
                                Actualité actuellement en brouillon
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Date de publication -->
                <div id="publish-date-container" style="{{ old('is_published', $news->is_published) ? 'display: block;' : 'display: none;' }}">
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de publication
                    </label>
                    <input type="datetime-local" id="published_at" name="published_at" 
                           value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full max-w-md px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">
                    <p class="text-xs text-gray-500 mt-1">
                        @if($news->published_at)
                            Publiée le {{ $news->published_at->format('d/m/Y à H:i') }}
                        @else
                            Définir la date de publication
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour
                    </button>
                    <button type="button" onclick="previewNews()" 
                            class="inline-flex items-center px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Prévisualiser
                    </button>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.news') }}" 
                       class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Formulaire de suppression séparé -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Zone de danger</h2>
                <p class="text-gray-600 mt-2">Cette action est irréversible.</p>
            </div>
            <form method="POST" action="{{ route('admin.news.destroy', $news->id) }}" 
                  class="inline" 
                  onsubmit="return confirmDelete('Êtes-vous sûr de vouloir supprimer définitivement cette actualité ?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                    <i class="fas fa-trash mr-2"></i>
                    Supprimer l'actualité
                </button>
            </form>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        
        titleInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
            slugInput.value = slug;
        });

        // Show/hide publish date based on checkbox
        const isPublishedCheckbox = document.getElementById('is_published');
        const publishDateContainer = document.getElementById('publish-date-container');
        
        isPublishedCheckbox.addEventListener('change', function() {
            if (this.checked) {
                publishDateContainer.style.display = 'block';
            } else {
                publishDateContainer.style.display = 'none';
            }
        });
    });

    function previewNews() {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        
        // Add current content from TinyMCE
        const content = tinymce.get('content').getContent();
        formData.set('content', content);
        
        // Ensure title, excerpt and slug are included
        const title = document.getElementById('title').value;
        const excerpt = document.getElementById('excerpt').value;
        const slug = document.getElementById('slug').value;
        formData.set('title', title);
        formData.set('excerpt', excerpt);
        formData.set('slug', slug);
        
        // Open preview in new tab
        const previewWindow = window.open('', '_blank');
        previewWindow.document.write('<div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">Chargement de la prévisualisation...</div>');
        
        // Submit form to preview route
        fetch('{{ route("admin.news.preview") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.text())
        .then(html => {
            previewWindow.document.open();
            previewWindow.document.write(html);
            previewWindow.document.close();
        })
        .catch(error => {
            console.error('Erreur lors de la prévisualisation:', error);
            previewWindow.document.open();
            previewWindow.document.write('<div style="text-align: center; padding: 50px; font-family: Arial, sans-serif; color: red;">Erreur lors du chargement de la prévisualisation</div>');
            previewWindow.document.close();
        });
    }
</script>
@endsection