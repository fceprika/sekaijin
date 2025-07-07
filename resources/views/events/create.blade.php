@extends('layout')

@section('title', 'Créer un événement - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Créer un événement</h1>
                    <p class="text-gray-600">Organisez un événement pour votre communauté d'expatriés</p>
                </div>
            </div>
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Form -->
        <form action="{{ route('events.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations générales</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre de l'événement *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Apéro français mensuel">
                    </div>
                    
                    <!-- Country -->
                    <div>
                        <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">Pays *</label>
                        <select id="country_id" name="country_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choisir un pays</option>
                            @foreach($countries as $countryOption)
                                <option value="{{ $countryOption->id }}" 
                                    {{ (old('country_id', $country?->id) == $countryOption->id) ? 'selected' : '' }}>
                                    {{ $countryOption->emoji }} {{ $countryOption->name_fr }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                        <select id="category" name="category" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choisir une catégorie</option>
                            <option value="networking" {{ old('category') == 'networking' ? 'selected' : '' }}>🤝 Networking</option>
                            <option value="culture" {{ old('category') == 'culture' ? 'selected' : '' }}>🎭 Culture</option>
                            <option value="sport" {{ old('category') == 'sport' ? 'selected' : '' }}>⚽ Sport</option>
                            <option value="voyage" {{ old('category') == 'voyage' ? 'selected' : '' }}>✈️ Voyage</option>
                            <option value="gastronomie" {{ old('category') == 'gastronomie' ? 'selected' : '' }}>🍽️ Gastronomie</option>
                            <option value="conférence" {{ old('category') == 'conférence' ? 'selected' : '' }}>🎤 Conférence</option>
                            <option value="apprentissage" {{ old('category') == 'apprentissage' ? 'selected' : '' }}>📚 Apprentissage</option>
                            <option value="autre" {{ old('category') == 'autre' ? 'selected' : '' }}>🎯 Autre</option>
                        </select>
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description courte *</label>
                        <textarea id="description" name="description" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Une brève description de votre événement">{{ old('description') }}</textarea>
                    </div>
                    
                    <!-- Full Description -->
                    <div class="md:col-span-2">
                        <label for="full_description" class="block text-sm font-medium text-gray-700 mb-2">Description complète</label>
                        <textarea id="full_description" name="full_description" rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Décrivez votre événement en détail...">{{ old('full_description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Date and Time -->
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Date et horaires</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date et heure de début *</label>
                        <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Date et heure de fin</label>
                        <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Lieu de l'événement</h2>
                
                <!-- Online Event Toggle -->
                <div class="mb-6">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" id="is_online" name="is_online" value="1" {{ old('is_online') ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">🌐 Événement en ligne</span>
                    </label>
                </div>
                
                <div id="location-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Physical Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Restaurant Le Bistrot">
                    </div>
                    
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse complète</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="123 Rue de la Paix, Bangkok">
                    </div>
                </div>
                
                <!-- Online Link -->
                <div id="online-field" class="hidden">
                    <label for="online_link" class="block text-sm font-medium text-gray-700 mb-2">Lien de l'événement en ligne</label>
                    <input type="url" id="online_link" name="online_link" value="{{ old('online_link') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://zoom.us/j/123456789">
                </div>
            </div>

            <!-- Pricing and Participants -->
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Tarifs et participants</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Prix (€)</label>
                        <input type="number" id="price" name="price" value="{{ old('price', 0) }}" min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00">
                        <p class="text-sm text-gray-500 mt-1">Laissez 0 pour un événement gratuit</p>
                    </div>
                    
                    <!-- Max Participants -->
                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Nombre maximum de participants</label>
                        <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="50">
                        <p class="text-sm text-gray-500 mt-1">Laissez vide pour un nombre illimité</p>
                    </div>
                </div>
            </div>

            <!-- Publication Options -->
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Options de publication</h2>
                
                <div class="space-y-4">
                    <!-- Published -->
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Publier immédiatement</span>
                            <p class="text-sm text-gray-500">L'événement sera visible par tous les membres</p>
                        </div>
                    </label>
                    
                    <!-- Featured -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isAmbassador())
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Mettre en avant</span>
                            <p class="text-sm text-gray-500">L'événement apparaîtra en priorité sur la page événements</p>
                        </div>
                    </label>
                    @endif
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ url()->previous() }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 text-center">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-purple-500 to-blue-600 text-white rounded-lg hover:from-purple-600 hover:to-blue-700 transition duration-200 font-medium">
                    Créer l'événement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isOnlineCheckbox = document.getElementById('is_online');
    const locationFields = document.getElementById('location-fields');
    const onlineField = document.getElementById('online-field');
    const locationInput = document.getElementById('location');
    const addressInput = document.getElementById('address');
    const onlineLinkInput = document.getElementById('online_link');
    
    function toggleLocationFields() {
        if (isOnlineCheckbox.checked) {
            locationFields.classList.add('hidden');
            onlineField.classList.remove('hidden');
            locationInput.required = false;
            onlineLinkInput.required = true;
        } else {
            locationFields.classList.remove('hidden');
            onlineField.classList.add('hidden');
            locationInput.required = true;
            onlineLinkInput.required = false;
        }
    }
    
    isOnlineCheckbox.addEventListener('change', toggleLocationFields);
    toggleLocationFields(); // Initial state
});
</script>
@endsection