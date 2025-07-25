@extends('admin.layout')

@section('title', 'Modifier l\'Article')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Modifier l'Article</h1>
                <p class="text-gray-600 mt-2">{{ $article->title }}</p>
                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                    <span>
                        <i class="fas fa-user mr-1"></i>
                        {{ $article->author->name }}
                    </span>
                    <span>
                        <i class="fas fa-calendar mr-1"></i>
                        Créé le {{ $article->created_at->format('d/m/Y à H:i') }}
                    </span>
                    @if($article->updated_at != $article->created_at)
                        <span>
                            <i class="fas fa-edit mr-1"></i>
                            Modifié le {{ $article->updated_at->format('d/m/Y à H:i') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="previewArticle()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-eye mr-2"></i>
                    Prévisualisation
                </button>
                @if($article->is_published)
                    <a href="{{ route('country.article.show', [$article->country->slug, $article->slug]) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir en ligne
                    </a>
                @endif
                <a href="{{ route('admin.articles') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.articles.update', $article->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Options de publication et actions en haut -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Publication et actions rapides</h2>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Article en vedette -->
                        <div class="flex items-center">
                            <input id="is_featured_top" name="is_featured" type="checkbox" value="1" 
                                   {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            <label for="is_featured_top" class="ml-2 text-sm font-medium text-gray-700">
                                Article en vedette
                            </label>
                        </div>

                        <!-- Article publié -->
                        <div class="flex items-center">
                            <input id="is_published_top" name="is_published" type="checkbox" value="1" 
                                   {{ old('is_published', $article->is_published) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            <label for="is_published_top" class="ml-2 text-sm font-medium text-gray-700">
                                Article publié
                            </label>
                        </div>

                        <!-- Date de publication -->
                        <div class="flex items-center space-x-2">
                            <label for="published_at_top" class="text-sm font-medium text-gray-700">
                                Date de publication:
                            </label>
                            <input type="datetime-local" id="published_at_top" name="published_at" 
                                   value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action en haut -->
                <div class="flex items-center space-x-3">
                    <button type="submit" 
                            onclick="saveTinyMCEContent()"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour
                    </button>
                    <button type="button" onclick="previewArticle()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Prévisualiser
                    </button>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Informations de base -->
            <div class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de base</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de l'article *
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="Saisissez le titre de votre article...">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug URL *
                        </label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $article->slug) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="url-de-larticle">
                        <p class="text-xs text-gray-500 mt-1">Utilisé dans l'URL de l'article</p>
                    </div>

                    <!-- Temps de lecture -->
                    <div>
                        <label for="reading_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Temps de lecture (minutes)
                        </label>
                        <input type="number" id="reading_time" name="reading_time" value="{{ old('reading_time', $article->reading_time) }}" min="1" max="120"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="Auto-calculé">
                        <p class="text-xs text-gray-500 mt-1">Laissez vide pour recalculer automatiquement</p>
                    </div>

                    <!-- Pays -->
                    <div>
                        <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pays *
                        </label>
                        <select id="country_id" name="country_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">Sélectionnez un pays</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $article->country_id) == $country->id ? 'selected' : '' }}>
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $article->category) === $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Upload d'image -->
                    <div class="lg:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Image de l'article
                        </label>
                        
                        @if($article->image_url)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                                <img src="{{ $article->image_url }}" alt="Image actuelle" class="h-32 w-auto rounded-lg shadow-sm">
                            </div>
                        @endif
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ $article->image_url ? 'Remplacer l\'image' : 'Télécharger une image' }}</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 500KB</p>
                            </div>
                        </div>
                        <div id="image-preview" class="mt-4 hidden">
                            <img class="h-32 w-auto rounded-lg shadow-sm" alt="Aperçu de l'image">
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="lg:col-span-2">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                            Résumé de l'article *
                        </label>
                        <textarea id="excerpt" name="excerpt" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                  placeholder="Rédigez un résumé accrocheur de votre article...">{{ old('excerpt', $article->excerpt) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 500 caractères</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Contenu de l'article</h2>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Contenu *
                </label>
                <textarea id="content" name="content" class="wysiwyg" required>{{ old('content', $article->content) }}</textarea>
                <p class="text-xs text-gray-500 mt-2">Minimum 200 caractères. Utilisez l'éditeur pour formater votre contenu.</p>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistiques</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Vues</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $article->views ?? 0 }}</p>
                        </div>
                        <i class="fas fa-eye text-blue-600 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Temps de lecture</p>
                            <p class="text-2xl font-bold text-purple-800">{{ $article->reading_time ?? 0 }} min</p>
                        </div>
                        <i class="fas fa-clock text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Options de publication (informations seulement) -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de publication</h2>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Status actuel -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Statut actuel</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600">Publication:</span>
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $article->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $article->is_published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600">En vedette:</span>
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $article->is_featured ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $article->is_featured ? 'Oui' : 'Non' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Date de publication -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Dates importantes</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            @if($article->published_at)
                                <div>Publié le {{ $article->published_at->format('d/m/Y à H:i') }}</div>
                            @endif
                            <div>Créé le {{ $article->created_at->format('d/m/Y à H:i') }}</div>
                            @if($article->updated_at != $article->created_at)
                                <div>Modifié le {{ $article->updated_at->format('d/m/Y à H:i') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="text-xs text-gray-500 bg-blue-50 rounded-lg p-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    Les options de publication peuvent être modifiées dans la section "Publication et actions rapides" en haut de cette page.
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button type="submit" 
                            onclick="saveTinyMCEContent()"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour
                    </button>
                    <button type="button" onclick="previewArticle()" 
                            class="inline-flex items-center px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Prévisualiser
                    </button>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.articles') }}" 
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
            <form method="POST" action="{{ route('admin.articles.destroy', $article->id) }}" 
                  class="inline" 
                  onsubmit="return confirmDelete('Êtes-vous sûr de vouloir supprimer définitivement cet article ?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                    <i class="fas fa-trash mr-2"></i>
                    Supprimer l'article
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

        // Sync checkboxes between top and bottom sections (removed - now only one set of controls)

        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = imagePreview.querySelector('img');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Check file size (500KB = 512000 bytes)
                if (file.size > 512000) {
                    alert('La taille de l\'image ne doit pas dépasser 500KB. Taille actuelle: ' + Math.round(file.size / 1024) + 'KB');
                    e.target.value = '';
                    imagePreview.classList.add('hidden');
                    return;
                }

                // Check file type
                if (!file.type.startsWith('image/')) {
                    alert('Veuillez sélectionner un fichier image valide.');
                    e.target.value = '';
                    imagePreview.classList.add('hidden');
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
            }
        });
    });

    function previewArticle() {
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
        fetch('{{ route("admin.articles.preview") }}', {
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

    function saveTinyMCEContent() {
        // Force TinyMCE to save content to the textarea before form submission
        if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
            tinymce.get('content').save();
        }
        return true;
    }
</script>
@endsection