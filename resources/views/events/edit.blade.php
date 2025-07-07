@extends('layout')

@section('title', 'Modifier l\'événement - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Modifier l'événement</h1>
                    <p class="text-gray-600">{{ $event->title }}</p>
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
        <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations générales</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre de l'événement *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required
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
                                    {{ (old('country_id', $event->country_id) == $countryOption->id) ? 'selected' : '' }}>
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
                            <option value="networking" {{ old('category', $event->category) == 'networking' ? 'selected' : '' }}>🤝 Networking</option>
                            <option value="culture" {{ old('category', $event->category) == 'culture' ? 'selected' : '' }}>🎭 Culture</option>
                            <option value="sport" {{ old('category', $event->category) == 'sport' ? 'selected' : '' }}>⚽ Sport</option>
                            <option value="voyage" {{ old('category', $event->category) == 'voyage' ? 'selected' : '' }}>✈️ Voyage</option>
                            <option value="gastronomie" {{ old('category', $event->category) == 'gastronomie' ? 'selected' : '' }}>🍽️ Gastronomie</option>
                            <option value="conférence" {{ old('category', $event->category) == 'conférence' ? 'selected' : '' }}>🎤 Conférence</option>
                            <option value="apprentissage" {{ old('category', $event->category) == 'apprentissage' ? 'selected' : '' }}>📚 Apprentissage</option>
                            <option value="autre" {{ old('category', $event->category) == 'autre' ? 'selected' : '' }}>🎯 Autre</option>
                        </select>
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description courte *</label>
                        <textarea id="description" name="description" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Une brève description de votre événement">{{ old('description', $event->description) }}</textarea>
                    </div>
                    
                    <!-- Full Description -->
                    <div class="md:col-span-2">
                        <label for="full_description" class="block text-sm font-medium text-gray-700 mb-2">Description complète</label>
                        <textarea id="full_description" name="full_description" rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Décrivez votre événement en détail...">{{ old('full_description', $event->full_description) }}</textarea>
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
                        <input type="datetime-local" id="start_date" name="start_date" 
                               value="{{ old('start_date', $event->start_date?->format('Y-m-d\TH:i')) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Date et heure de fin</label>
                        <input type="datetime-local" id="end_date" name="end_date" 
                               value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}"
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
                        <input type="checkbox" id="is_online" name="is_online" value="1" 
                               {{ old('is_online', $event->is_online) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">🌐 Événement en ligne</span>
                    </label>
                </div>
                
                <div id="location-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Physical Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Restaurant Le Bistrot">
                    </div>
                    
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse complète</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $event->address) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="123 Rue de la Paix, Bangkok">
                    </div>
                </div>
                
                <!-- Online Link -->
                <div id="online-field" class="hidden">
                    <label for="online_link" class="block text-sm font-medium text-gray-700 mb-2">Lien de l'événement en ligne</label>
                    <input type="url" id="online_link" name="online_link" value="{{ old('online_link', $event->online_link) }}"
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
                        <input type="number" id="price" name="price" value="{{ old('price', $event->price) }}" min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00">
                        <p class="text-sm text-gray-500 mt-1">Laissez 0 pour un événement gratuit</p>
                    </div>
                    
                    <!-- Max Participants -->
                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Nombre maximum de participants</label>
                        <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="50">
                        <p class="text-sm text-gray-500 mt-1">Laissez vide pour un nombre illimité</p>
                    </div>
                    
                    <!-- Current Participants (Read-only info) -->
                    <div class="md:col-span-2">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-900">
                                    Participants actuels: {{ $event->current_participants ?? 0 }}
                                    @if($event->max_participants)
                                        / {{ $event->max_participants }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publication Options -->
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Options de publication</h2>
                
                <div class="space-y-4">
                    <!-- Published -->
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_published" value="1" 
                               {{ old('is_published', $event->is_published) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Publier immédiatement</span>
                            <p class="text-sm text-gray-500">L'événement sera visible par tous les membres</p>
                        </div>
                    </label>
                    
                    <!-- Featured -->
                    @can('feature', $event)
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_featured" value="1" 
                               {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Mettre en avant</span>
                            <p class="text-sm text-gray-500">L'événement apparaîtra en priorité sur la page événements</p>
                        </div>
                    </label>
                    @endcan
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <div class="flex gap-4">
                    <a href="{{ route('country.event.show', [$event->country->slug, $event->id]) }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 text-center">
                        Annuler
                    </a>
                    @can('delete', $event)
                    <button type="button" onclick="confirmDelete()" 
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 font-medium">
                        Supprimer
                    </button>
                    @endcan
                </div>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-lg hover:from-orange-600 hover:to-red-700 transition duration-200 font-medium">
                    Mettre à jour l'événement
                </button>
            </div>
        </form>

        <!-- Hidden Delete Form -->
        @can('delete', $event)
        <form id="delete-form" action="{{ route('events.destroy', $event) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endcan
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

function confirmDelete() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection