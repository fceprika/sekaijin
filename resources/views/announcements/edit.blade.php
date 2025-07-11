@extends('layout')

@section('title', 'Modifier l\'annonce')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Modifier l'annonce</h1>
                    <a href="{{ route('announcements.show', $announcement) }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                @if($announcement->status == 'refused')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Annonce refusée</h3>
                                <p class="mt-1 text-sm text-red-700">
                                    Votre annonce a été refusée par la modération. En la modifiant, elle sera soumise à nouveau pour validation.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($announcement->status == 'pending')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Annonce en attente</h3>
                                <p class="mt-1 text-sm text-yellow-700">
                                    Votre annonce est en cours de validation par notre équipe.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Type d'annonce -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Type d'annonce</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative">
                                <input type="radio" name="type" value="vente" {{ $announcement->type == 'vente' ? 'checked' : '' }} class="peer sr-only" required>
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                    <div class="text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Vente</h3>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="type" value="location" {{ $announcement->type == 'location' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                    <div class="text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Location</h3>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="type" value="colocation" {{ $announcement->type == 'colocation' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                    <div class="text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Colocation</h3>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="type" value="service" {{ $announcement->type == 'service' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                    <div class="text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Service</h3>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Localisation -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                            <select name="country" id="country" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">Sélectionnez un pays</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->name_fr }}" {{ $announcement->country == $country->name_fr ? 'selected' : '' }}>
                                        {{ $country->emoji }} {{ $country->name_fr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                            <input type="text" name="city" id="city" value="{{ $announcement->city }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="Ex: Bangkok, Paris, Tokyo..." required>
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse (optionnel)</label>
                        <input type="text" name="address" id="address" value="{{ $announcement->address }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Quartier ou adresse précise">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Détails -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre de l'annonce</label>
                        <input type="text" name="title" id="title" value="{{ $announcement->title }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Un titre clair et descriptif" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="6" 
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                  placeholder="Décrivez votre annonce en détail..." required>{{ $announcement->description }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Prix</label>
                            <input type="number" name="price" id="price" value="{{ $announcement->price }}" 
                                   step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0.00">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
                            <select name="currency" id="currency" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="EUR" {{ $announcement->currency == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="USD" {{ $announcement->currency == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="THB" {{ $announcement->currency == 'THB' ? 'selected' : '' }}>THB (฿)</option>
                                <option value="JPY" {{ $announcement->currency == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
                                <option value="GBP" {{ $announcement->currency == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                <option value="CHF" {{ $announcement->currency == 'CHF' ? 'selected' : '' }}>CHF (CHF)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="expiration_date" class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration (optionnel)</label>
                        <input type="date" name="expiration_date" id="expiration_date" 
                               value="{{ $announcement->expiration_date ? $announcement->expiration_date->format('Y-m-d') : '' }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Laissez vide pour une annonce sans expiration</p>
                        @error('expiration_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Images existantes -->
                    @php
                        // Traitement sécurisé des images
                        $rawImages = $announcement->images;
                        
                        if (is_string($rawImages)) {
                            $images = json_decode($rawImages, true) ?: [];
                        } elseif (is_array($rawImages)) {
                            $images = $rawImages;
                        } else {
                            $images = [];
                        }
                        
                        // S'assurer que chaque élément du tableau est une chaîne
                        $images = array_filter($images, function($image) {
                            return is_string($image) || (is_array($image) && isset($image['path']));
                        });
                    @endphp
                    @if($images && count($images) > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Images actuelles</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="existing-images">
                                @foreach($images as $index => $image)
                                    @php
                                        $imagePath = is_string($image) ? $image : (is_array($image) && isset($image['path']) ? $image['path'] : '');
                                    @endphp
                                    @if($imagePath)
                                        <div class="relative group" data-image="{{ $imagePath }}">
                                            <img src="{{ Storage::url($imagePath) }}" alt="Image {{ $index + 1 }}" class="w-full h-32 object-cover rounded-lg">
                                            <button type="button" onclick="removeExistingImage('{{ $imagePath }}')" 
                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Nouvelles images -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ajouter de nouvelles images</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Télécharger des images</span>
                                        <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP jusqu'à 2MB chacune</p>
                            </div>
                        </div>
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Aperçu des nouvelles images -->
                    <div id="new-image-preview" class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 hidden">
                    </div>

                    <!-- Champs cachés pour les images à supprimer -->
                    <div id="remove-images-inputs"></div>

                    <!-- Boutons -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('announcements.show', $announcement) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Annuler
                        </a>
                        
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('images');
    const newImagePreview = document.getElementById('new-image-preview');
    const removeImagesInputs = document.getElementById('remove-images-inputs');
    
    // Gestion des nouvelles images
    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        newImagePreview.innerHTML = '';
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded-lg';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity';
                    removeBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                    
                    removeBtn.addEventListener('click', function() {
                        div.remove();
                        if (newImagePreview.children.length === 0) {
                            newImagePreview.classList.add('hidden');
                        }
                    });
                    
                    div.appendChild(img);
                    div.appendChild(removeBtn);
                    newImagePreview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
                newImagePreview.classList.remove('hidden');
            }
        });
    });

    // Fonction globale pour supprimer les images existantes
    window.removeExistingImage = function(imagePath) {
        // Ajouter l'input hidden pour marquer l'image à supprimer
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_images[]';
        input.value = imagePath;
        removeImagesInputs.appendChild(input);
        
        // Cacher l'image dans l'interface
        const imageDiv = document.querySelector(`[data-image="${imagePath}"]`);
        if (imageDiv) {
            imageDiv.style.display = 'none';
        }
    };
});
</script>
@endsection