@extends('admin.layout')

@section('title', 'Créer un Article')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Créer un Article</h1>
                <p class="text-gray-600 mt-2">Rédigez un nouvel article pour la communauté</p>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="previewArticle()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-eye mr-2"></i>
                    Prévisualisation
                </button>
                <a href="{{ route('admin.articles') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
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
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="Saisissez le titre de votre article...">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug URL
                        </label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="auto-généré depuis le titre">
                        <p class="text-xs text-gray-500 mt-1">Laissez vide pour générer automatiquement depuis le titre</p>
                    </div>

                    <!-- Temps de lecture -->
                    <div>
                        <label for="reading_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Temps de lecture (minutes)
                        </label>
                        <input type="number" id="reading_time" name="reading_time" value="{{ old('reading_time') }}" min="1" max="120"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="Auto-calculé">
                        <p class="text-xs text-gray-500 mt-1">Laissez vide pour calculer automatiquement</p>
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
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
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
                                <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>
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
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Télécharger une image</span>
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
                                  placeholder="Rédigez un résumé accrocheur de votre article...">{{ old('excerpt') }}</textarea>
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
                <textarea id="content" name="content" class="wysiwyg" required>{{ old('content') }}</textarea>
                <p class="text-xs text-gray-500 mt-2">Minimum 200 caractères. Utilisez l'éditeur pour formater votre contenu.</p>
            </div>
        </div>

        <!-- Options de publication -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Options de publication</h2>
            
            <div class="space-y-4">
                <!-- Article en vedette -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_featured" name="is_featured" type="checkbox" value="1" 
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    </div>
                    <div class="ml-3">
                        <label for="is_featured" class="text-sm font-medium text-gray-700">
                            Article en vedette
                        </label>
                        <p class="text-xs text-gray-500">Mettre cet article en avant sur la page d'accueil</p>
                    </div>
                </div>

                <!-- Publier immédiatement -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_published" name="is_published" type="checkbox" value="1" 
                               {{ old('is_published') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    </div>
                    <div class="ml-3">
                        <label for="is_published" class="text-sm font-medium text-gray-700">
                            Publier immédiatement
                        </label>
                        <p class="text-xs text-gray-500">Décochez pour sauvegarder en brouillon</p>
                    </div>
                </div>

                <!-- Date de publication -->
                <div id="publish-date-container" style="display: none;">
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de publication
                    </label>
                    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at') }}"
                           class="w-full max-w-md px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour publier maintenant</p>
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
                        Créer l'article
                    </button>
                    <button type="button" onclick="previewArticle()" 
                            class="inline-flex items-center px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Prévisualiser
                    </button>
                </div>
                <a href="{{ route('admin.articles') }}" 
                   class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
            </div>
        </div>
    </form>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        
        titleInput.addEventListener('input', function() {
            if (!slugInput.value) {
                const slug = this.value
                    .toLowerCase()
                    .normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, "")
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-');
                slugInput.value = slug;
            }
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