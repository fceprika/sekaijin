@extends('layout')

@section('title', 'Créer une annonce - ' . $countryModel->name_fr)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <div class="flex items-center mb-2">
                            <span class="text-2xl mr-2">{{ $countryModel->emoji }}</span>
                            <h1 class="text-2xl font-bold text-gray-900">Créer une annonce</h1>
                        </div>
                        <p class="text-gray-600">Publiez votre annonce pour la communauté française en {{ $countryModel->name_fr }}</p>
                    </div>
                    <a href="{{ route('country.annonces', $countryModel->slug) }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <!-- Indicateur de progression -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div id="step-1-indicator" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Type d'annonce</span>
                        </div>
                        <div class="flex items-center">
                            <div id="step-2-indicator" class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">2</div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Localisation</span>
                        </div>
                        <div class="flex items-center">
                            <div id="step-3-indicator" class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">3</div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Détails</span>
                        </div>
                        <div class="flex items-center">
                            <div id="step-4-indicator" class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">4</div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Images</span>
                        </div>
                        <div class="flex items-center">
                            <div id="step-5-indicator" class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">5</div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Aperçu</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progress-bar" class="bg-blue-600 h-2 rounded-full" style="width: 20%"></div>
                    </div>
                </div>

                <form id="announcement-form" action="{{ route('country.announcements.store', $countryModel->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Étape 1: Type d'annonce -->
                    <div id="step-1" class="step">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Choisissez le type d'annonce</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative">
                                <input type="radio" name="type" value="vente" class="peer sr-only" required>
                                <div class="p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Vente</h3>
                                        <p class="text-sm text-gray-600 mt-1">Vendez vos objets</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="type" value="location" class="peer sr-only">
                                <div class="p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Location</h3>
                                        <p class="text-sm text-gray-600 mt-1">Proposez un logement</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="type" value="colocation" class="peer sr-only">
                                <div class="p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Colocation</h3>
                                        <p class="text-sm text-gray-600 mt-1">Partagez un logement</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="type" value="service" class="peer sr-only">
                                <div class="p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Service</h3>
                                        <p class="text-sm text-gray-600 mt-1">Proposez vos services</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Étape 2: Localisation -->
                    <div id="step-2" class="step hidden">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Où se trouve votre annonce ?</h2>
                        
                        <div class="space-y-4">
                            <!-- Pays pré-sélectionné -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                <div class="w-full p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-xl mr-2">{{ $countryModel->emoji }}</span>
                                        <span class="font-medium text-blue-900">{{ $countryModel->name_fr }}</span>
                                        <span class="ml-2 text-sm text-blue-600">(pré-sélectionné)</span>
                                    </div>
                                </div>
                                <input type="hidden" name="country" value="{{ $countryModel->name_fr }}">
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="Ex: Bangkok, Tokyo, Phnom Penh..." required>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse (optionnel)</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="Quartier ou adresse précise">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Étape 3: Détails -->
                    <div id="step-3" class="step hidden">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Détails de l'annonce</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre de l'annonce</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="Un titre clair et descriptif" required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="6" 
                                          class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                          placeholder="Décrivez votre annonce en détail..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Prix</label>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" 
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
                                        <option value="EUR" {{ old('currency', 'EUR') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="THB" {{ old('currency') == 'THB' ? 'selected' : '' }}>THB (฿)</option>
                                        <option value="JPY" {{ old('currency') == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
                                        <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                        <option value="CHF" {{ old('currency') == 'CHF' ? 'selected' : '' }}>CHF (CHF)</option>
                                    </select>
                                    @error('currency')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="expiration_date" class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration (optionnel)</label>
                                <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Laissez vide pour une annonce sans expiration</p>
                                @error('expiration_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Étape 4: Images -->
                    <div id="step-4" class="step hidden">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ajoutez des images</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Images (optionnel)</label>
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

                            <!-- Aperçu des images -->
                            <div id="image-preview" class="grid grid-cols-2 sm:grid-cols-3 gap-4 hidden">
                            </div>
                        </div>
                    </div>

                    <!-- Étape 5: Aperçu -->
                    <div id="step-5" class="step hidden">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aperçu de votre annonce</h2>
                        
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Type</h3>
                                    <p id="preview-type" class="mt-1 text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Localisation</h3>
                                    <p id="preview-location" class="mt-1 text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Titre</h3>
                                    <p id="preview-title" class="mt-1 text-gray-900 font-semibold"></p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Description</h3>
                                    <p id="preview-description" class="mt-1 text-gray-900 whitespace-pre-line"></p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Prix</h3>
                                    <p id="preview-price" class="mt-1 text-gray-900 font-semibold"></p>
                                </div>
                                
                                <div id="preview-images-container" class="hidden">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Images</h3>
                                    <div id="preview-images" class="grid grid-cols-3 gap-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-800">
                                <strong>Note :</strong> Votre annonce sera publiée pour la communauté française en {{ $countryModel->name_fr }} et soumise à modération avant publication. 
                                Vous recevrez un email de confirmation une fois qu'elle sera approuvée.
                            </p>
                        </div>
                    </div>

                    <!-- Boutons de navigation -->
                    <div class="mt-8 flex justify-between">
                        <button type="button" id="prev-btn" class="hidden px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Précédent
                        </button>
                        
                        <button type="button" id="next-btn" class="ml-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Suivant
                        </button>
                        
                        <button type="submit" id="submit-btn" class="hidden ml-auto px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Publier l'annonce
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 5;
    const form = document.getElementById('announcement-form');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    const progressBar = document.getElementById('progress-bar');

    // Gestion des étapes
    function showStep(step) {
        // Cacher toutes les étapes
        document.querySelectorAll('.step').forEach(s => s.classList.add('hidden'));
        
        // Afficher l'étape courante
        document.getElementById(`step-${step}`).classList.remove('hidden');
        
        // Mettre à jour les indicateurs
        for (let i = 1; i <= totalSteps; i++) {
            const indicator = document.getElementById(`step-${i}-indicator`);
            if (i < step) {
                indicator.classList.remove('bg-gray-300', 'text-gray-600');
                indicator.classList.add('bg-green-600', 'text-white');
            } else if (i === step) {
                indicator.classList.remove('bg-gray-300', 'text-gray-600', 'bg-green-600');
                indicator.classList.add('bg-blue-600', 'text-white');
            } else {
                indicator.classList.remove('bg-blue-600', 'bg-green-600', 'text-white');
                indicator.classList.add('bg-gray-300', 'text-gray-600');
            }
        }
        
        // Mettre à jour la barre de progression
        progressBar.style.width = `${(step / totalSteps) * 100}%`;
        
        // Gérer l'affichage des boutons
        prevBtn.classList.toggle('hidden', step === 1);
        nextBtn.classList.toggle('hidden', step === totalSteps);
        submitBtn.classList.toggle('hidden', step !== totalSteps);
        
        // Mettre à jour l'aperçu si on est à la dernière étape
        if (step === totalSteps) {
            updatePreview();
        }
    }

    // Valider l'étape courante
    function validateStep(step) {
        switch(step) {
            case 1:
                return document.querySelector('input[name="type"]:checked') !== null;
            case 2:
                return document.getElementById('city').value;
            case 3:
                return document.getElementById('title').value && 
                       document.getElementById('description').value &&
                       document.getElementById('currency').value;
            case 4:
                return true; // Les images sont optionnelles
            default:
                return true;
        }
    }

    // Navigation
    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        } else {
            alert('Veuillez remplir tous les champs obligatoires.');
        }
    });

    prevBtn.addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
    });

    // Gestion des images
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');
    const uploadedImages = [];

    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded-lg';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600';
                    removeBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                    
                    removeBtn.addEventListener('click', function() {
                        div.remove();
                        if (imagePreview.children.length === 0) {
                            imagePreview.classList.add('hidden');
                        }
                    });
                    
                    div.appendChild(img);
                    div.appendChild(removeBtn);
                    imagePreview.appendChild(div);
                    
                    uploadedImages.push(file);
                };
                
                reader.readAsDataURL(file);
                imagePreview.classList.remove('hidden');
            }
        });
    });

    // Mettre à jour l'aperçu
    function updatePreview() {
        // Type
        const typeValue = document.querySelector('input[name="type"]:checked')?.value || '';
        const typeLabels = {
            'vente': 'Vente',
            'location': 'Location',
            'colocation': 'Colocation',
            'service': 'Service'
        };
        document.getElementById('preview-type').textContent = typeLabels[typeValue] || '';
        
        // Localisation
        const city = document.getElementById('city').value;
        const address = document.getElementById('address').value;
        let location = `${city}, {{ $countryModel->name_fr }}`;
        if (address) location += ` - ${address}`;
        document.getElementById('preview-location').textContent = location;
        
        // Titre et description
        document.getElementById('preview-title').textContent = document.getElementById('title').value;
        document.getElementById('preview-description').textContent = document.getElementById('description').value;
        
        // Prix
        const price = document.getElementById('price').value;
        const currency = document.getElementById('currency').value;
        if (price) {
            const currencies = {
                'EUR': '€',
                'USD': '$',
                'THB': '฿',
                'JPY': '¥',
                'GBP': '£',
                'CHF': 'CHF'
            };
            document.getElementById('preview-price').textContent = `${price} ${currencies[currency]}`;
        } else {
            document.getElementById('preview-price').textContent = 'Gratuit';
        }
        
        // Images
        const previewImagesContainer = document.getElementById('preview-images-container');
        const previewImages = document.getElementById('preview-images');
        if (imagePreview.children.length > 0) {
            previewImagesContainer.classList.remove('hidden');
            previewImages.innerHTML = '';
            Array.from(imagePreview.children).forEach(child => {
                const img = child.querySelector('img').cloneNode();
                img.className = 'w-full h-20 object-cover rounded';
                previewImages.appendChild(img);
            });
        } else {
            previewImagesContainer.classList.add('hidden');
        }
    }

    // Initialiser
    showStep(currentStep);
});
</script>
@endsection