@extends('layout')

@section('title', 'Mon profil - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <!-- En-tête du profil -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-4 sm:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Mon Profil</h1>
                        <p class="text-blue-100 mt-2 text-sm sm:text-base">Gérez vos informations personnelles</p>
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        <a href="{{ $user->getPublicProfileUrl() }}" target="_blank" 
                           class="inline-flex items-center px-4 py-3 bg-white bg-opacity-25 hover:bg-opacity-35 text-white rounded-lg font-semibold transition duration-200 backdrop-blur-sm border border-white border-opacity-30 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="sm:hidden">Profil public</span>
                            <span class="hidden sm:inline">Voir mon profil public</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Messages de succès/erreur/info -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 m-6 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-6 py-4 m-6 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('info') }}
                    </div>
                </div>
            @endif

            @if (session('location_warning'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-6 py-4 m-6 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        {{ session('location_warning') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 m-6 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulaire de profil -->
            <div class="p-8">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Bouton de soumission en haut -->
                    <div class="flex justify-end">
                        <button type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                            Mettre à jour mon profil
                        </button>
                    </div>

                    <!-- Informations de base -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informations de base
                        </h2>
                        
                        <!-- Avatar Section -->
                        <div class="mb-6">
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
                            <!-- Layout responsive: vertical sur mobile, horizontal sur desktop -->
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                                <!-- Avatar centré sur mobile -->
                                <div class="flex-shrink-0 flex justify-center sm:justify-start">
                                    <img id="avatar-preview" class="h-24 w-24 sm:h-20 sm:w-20 rounded-full object-cover border-2 border-gray-300" 
                                         src="{{ $user->getAvatarUrl() }}" 
                                         alt="Avatar de {{ $user->name }}">
                                </div>
                                <div class="flex-1 w-full">
                                    <div class="relative">
                                        <input type="file" id="avatar" name="avatar" accept="image/*" 
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="flex items-center justify-center w-full px-3 py-4 sm:px-4 sm:py-3 border-2 border-dashed border-blue-300 rounded-lg bg-blue-50 hover:bg-blue-100 hover:border-blue-400 transition duration-200 cursor-pointer min-h-[80px] sm:min-h-[60px]">
                                            <div class="text-center">
                                                <svg class="mx-auto h-6 w-6 sm:h-8 sm:w-8 text-blue-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <p class="text-sm font-medium text-blue-600">Choisir une image</p>
                                                <p class="text-xs text-gray-500 hidden sm:block">ou glisser-déposer ici</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 text-center sm:text-left">
                                        JPG, PNG ou WebP. Maximum <span class="font-bold text-red-600">500KB</span>. Laissez vide pour conserver l'avatar actuel.
                                    </p>
                                    @if($user->avatar)
                                        <label class="flex items-center justify-center sm:justify-start mt-3">
                                            <input type="checkbox" name="remove_avatar" value="1" class="mr-2">
                                            <span class="text-sm text-red-600">Supprimer l'avatar actuel</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Pseudo *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                            
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Informations personnelles -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informations personnelles
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    placeholder="+33 1 23 45 67 89">
                            </div>
                        </div>
                    </div>

                    <!-- Localisation -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Localisation
                        </h2>
                        
                        <!-- Champs cachés pour préserver les valeurs actuelles -->
                        <input type="hidden" name="current_country_residence" value="{{ $user->country_residence }}">
                        <input type="hidden" name="current_city_residence" value="{{ $user->city_residence }}">
                        
                        <!-- Affichage de la localisation actuelle si elle existe -->
                        @if($user->country_residence || $user->city_residence)
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">📍 Localisation actuelle :</p>
                                        <p class="text-blue-700 mt-1">
                                            @if($user->city_residence)
                                                {{ $user->city_residence }}, 
                                            @endif
                                            {{ $user->country_residence ?? 'Non défini' }}
                                        </p>
                                    </div>
                                    <p class="text-xs text-blue-600">
                                        Cliquez sur un bouton ci-dessous pour modifier
                                    </p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Message de géolocalisation -->
                        <div id="geolocation-message" class="hidden mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-green-600 text-xl mr-3">📍</span>
                                <div>
                                    <p class="text-green-800 font-medium">Localisation détectée</p>
                                    <p class="text-green-700 text-sm mt-1" id="detected-location"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Choix du mode de saisie -->
                        <div class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button type="button" id="auto-location-btn" 
                                    class="w-full bg-gradient-to-r from-blue-500 to-green-500 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-green-600 transition duration-200 flex items-center justify-center shadow-md">
                                    <span id="auto-location-icon" class="mr-2">🌍</span>
                                    <span id="auto-location-text">Détecter automatiquement</span>
                                </button>
                                
                                <button type="button" id="manual-location-btn" 
                                    class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 px-4 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition duration-200 flex items-center justify-center shadow-md">
                                    <span class="mr-2">✏️</span>
                                    <span>Saisir manuellement</span>
                                </button>
                            </div>
                        </div>

                        <!-- Mode Manuel (masqué par défaut) -->
                        <div id="manual-location-section" class="hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">Pays de résidence *</label>
                                    <select id="country_residence" name="country_residence"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                        <option value="">Sélectionnez un pays</option>
                                        @include('partials.countries', ['selected' => old('country_residence', $user->country_residence), 'filter' => 'europe_asia'])
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">🌍 Actuellement disponible pour l'Europe et l'Asie uniquement</p>
                                </div>
                                
                                <div>
                                    <label for="city_residence" class="block text-sm font-medium text-gray-700 mb-2">Ville de résidence</label>
                                    <select id="city_residence" name="city_residence" disabled
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                        <option value="">Sélectionnez d'abord un pays</option>
                                        @if(old('city_residence', $user->city_residence))
                                            <option value="{{ old('city_residence', $user->city_residence) }}" selected>{{ old('city_residence', $user->city_residence) }}</option>
                                        @endif
                                    </select>
                                    <div id="city-loading" class="hidden mt-2 text-sm text-blue-600 flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Chargement des villes...
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">💡 Les villes sont chargées automatiquement selon votre pays. La géolocalisation est approximative (précision ~10km).</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mode Automatique (masqué par défaut) -->
                        <div id="auto-location-section" class="hidden">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-green-800 font-medium">📍 Localisation détectée automatiquement</span>
                                    <button type="button" id="edit-manual-location" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        ✏️ Modifier manuellement
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">🗺️ Pays détecté</label>
                                        <input type="text" id="detected-country-display" readonly 
                                            class="w-full px-4 py-3 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">🏙️ Ville détectée</label>
                                        <input type="text" id="detected-city-display" readonly 
                                            class="w-full px-4 py-3 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg cursor-not-allowed">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    🛡️ Position approximative (précision ~10km) pour votre sécurité
                                </p>
                                
                                <!-- Champs cachés pour soumission -->
                                <input type="hidden" id="detected-country-value" name="country_residence_auto">
                                <input type="hidden" id="detected-city-value" name="city_residence_auto">
                                <input type="hidden" id="detected-latitude" name="detected_latitude">
                                <input type="hidden" id="detected-longitude" name="detected_longitude">
                            </div>
                        </div>
                        
                        <!-- Pays d'intérêt -->
                        <div id="interest-country-container" class="mt-6">
                            <label for="interest_country" class="block text-sm font-medium text-gray-700 mb-2">
                                Pays d'intérêt <span class="text-gray-500 text-xs">(optionnel)</span>
                            </label>
                            <select id="interest_country" name="interest_country"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Sélectionnez un pays qui vous intéresse</option>
                                @foreach(\App\Models\Country::orderBy('name_fr')->get() as $country)
                                    <option value="{{ $country->name_fr }}" 
                                        {{ old('interest_country', $user->interest_country) == $country->name_fr ? 'selected' : '' }}>
                                        {{ $country->emoji }} {{ $country->name_fr }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Quel pays vous intéresse le plus pour votre expatriation ?</p>
                        </div>
                    </div>

                    <!-- Partage de localisation -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-blue-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            🗺️ Partage de localisation
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="share_location" name="share_location" type="checkbox" value="1" 
                                           {{ old('share_location', $user->is_visible_on_map) ? 'checked' : '' }}
                                           disabled
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <!-- Champ caché pour s'assurer que la valeur est soumise même quand la checkbox est désactivée -->
                                    <input type="hidden" id="share_location_hidden" name="share_location_hidden" value="{{ old('share_location', $user->is_visible_on_map) ? '1' : '0' }}">
                                </div>
                                <div class="ml-3">
                                    <label for="share_location" class="text-sm font-medium text-blue-800">
                                        Apparaître sur la carte des membres
                                    </label>
                                    <p class="text-xs text-blue-600 mt-1">
                                        Permettez aux autres membres de vous localiser de manière approximative sur la carte interactive. 
                                        Votre position exacte ne sera jamais partagée (rayon d'environ 10 km pour protéger votre vie privée).
                                    </p>
                                    <p id="location-requirement" class="text-xs text-orange-600 mt-1 font-medium">
                                        ⚠️ Vous devez sélectionner une ville ou utiliser la géolocalisation automatique pour activer cette option.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Informations de localisation actuelle -->
                            @if($user->hasLocationSharing())
                                <div id="current-location-info" class="mt-4 p-4 bg-white rounded-lg border border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-green-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="font-medium">Localisation active</span>
                                        </div>
                                        <button type="button" id="clear-location-btn" 
                                            class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 hover:border-red-400 transition duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Position: {{ $user->getDisplayLocation() }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Dernière mise à jour: {{ $user->updated_at ? $user->updated_at->format('d/m/Y à H:i') : 'Jamais' }}
                                    </p>
                                </div>
                            @endif
                            
                        </div>
                    </div>

                    <!-- Visibilité du profil -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-yellow-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            👁️ Visibilité du profil
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_public_profile" name="is_public_profile" type="checkbox" value="1" 
                                           {{ old('is_public_profile', $user->is_public_profile) ? 'checked' : '' }}
                                           class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500 focus:ring-2">
                                </div>
                                <div class="ml-3">
                                    <label for="is_public_profile" class="text-sm font-medium text-yellow-800">
                                        Profil public accessible à tous
                                    </label>
                                    <p class="text-xs text-yellow-600 mt-1">
                                        Permettez aux personnes non connectées de voir votre profil public. 
                                        Si cette option est désactivée, seuls les membres connectés pourront voir votre profil.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Information sur l'URL du profil -->
                            <div class="mt-4 p-4 bg-white rounded-lg border border-yellow-200">
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">🔗 URL de votre profil :</span>
                                    <a href="{{ $user->getPublicProfileUrl() }}" class="text-blue-600 hover:text-blue-800 ml-1" target="_blank">
                                        {{ url($user->getPublicProfileUrl()) }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Réseaux sociaux -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2C7 1.44772 7.44772 1 8 1H16C16.5523 1 17 1.44772 17 2V4M7 4H5C4.44772 4 4 4.44772 4 5V19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19V5C20 4.44772 19.5523 4 19 4H17M7 4H17"></path>
                            </svg>
                            Réseaux sociaux et plateformes
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- YouTube -->
                            <div>
                                <label for="youtube_username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-youtube text-red-600 mr-1"></i>
                                    YouTube
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-youtube text-red-600 text-lg"></i>
                                    </div>
                                    <input type="text" id="youtube_username" name="youtube_username" 
                                           value="{{ old('youtube_username', $user->youtube_username) }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           placeholder="@monusername">
                                </div>
                            </div>

                            <!-- Instagram -->
                            <div>
                                <label for="instagram_username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-instagram text-pink-500 mr-1"></i>
                                    Instagram
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-instagram text-pink-500 text-lg"></i>
                                    </div>
                                    <input type="text" id="instagram_username" name="instagram_username" 
                                           value="{{ old('instagram_username', $user->instagram_username) }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           placeholder="monprofil">
                                </div>
                            </div>

                            <!-- TikTok -->
                            <div>
                                <label for="tiktok_username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-tiktok text-black mr-1"></i>
                                    TikTok
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-tiktok text-black text-lg"></i>
                                    </div>
                                    <input type="text" id="tiktok_username" name="tiktok_username" 
                                           value="{{ old('tiktok_username', $user->tiktok_username) }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           placeholder="@monusername">
                                </div>
                            </div>

                            <!-- LinkedIn -->
                            <div>
                                <label for="linkedin_username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-linkedin text-blue-700 mr-1"></i>
                                    LinkedIn
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-linkedin text-blue-700 text-lg"></i>
                                    </div>
                                    <input type="text" id="linkedin_username" name="linkedin_username" 
                                           value="{{ old('linkedin_username', $user->linkedin_username) }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           placeholder="mon-profil">
                                </div>
                            </div>

                            <!-- Twitter -->
                            <div>
                                <label for="twitter_username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-twitter text-blue-400 mr-1"></i>
                                    Twitter / X
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-twitter text-blue-400 text-lg"></i>
                                    </div>
                                    <input type="text" id="twitter_username" name="twitter_username" 
                                           value="{{ old('twitter_username', $user->twitter_username) }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           placeholder="@monusername">
                                </div>
                            </div>

                            <!-- Facebook -->
                            <div>
                                <label for="facebook_username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-facebook text-blue-600 mr-1"></i>
                                    Facebook
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-facebook text-blue-600 text-lg"></i>
                                    </div>
                                    <input type="text" id="facebook_username" name="facebook_username" 
                                           value="{{ old('facebook_username', $user->facebook_username) }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           placeholder="mon.profil">
                                </div>
                            </div>

                            <!-- Telegram -->
                            <div>
                                <label for="telegram_username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-telegram text-blue-500 mr-1"></i>
                                    Telegram
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-telegram text-blue-500 text-lg"></i>
                                    </div>
                                    <input type="text" id="telegram_username" name="telegram_username" 
                                           value="{{ old('telegram_username', $user->telegram_username) }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           placeholder="@monusername">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biographie -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            À propos de vous
                        </h2>
                        
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Biographie</label>
                            <textarea id="bio" name="bio" rows="6" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-y"
                                placeholder="Parlez-nous de vous, de votre parcours d'expatrié, de vos passions...">{{ old('bio', $user->bio) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Maximum 1000 caractères. Utilisez des sauts de ligne pour organiser votre texte.</p>
                        </div>
                    </div>

                    <!-- Sécurité -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Changer le mot de passe
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                            
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                                <input type="password" id="new_password" name="new_password" minlength="12"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <p class="text-xs text-gray-500 mt-1">Minimum 12 caractères avec majuscule, minuscule et chiffre</p>
                            </div>
                            
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Laissez vide si vous ne souhaitez pas changer votre mot de passe</p>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="flex justify-end">
                        <button type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                            Mettre à jour mon profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Attendre que les modules soient chargés
    function initializeComponents() {
        if (typeof ProfileLocationManager === 'undefined' || typeof FormChangeTracker === 'undefined') {
            setTimeout(initializeComponents, 100);
            return;
        }
        
        // Initialiser le gestionnaire de localisation
        const locationManager = new ProfileLocationManager();
        
        // Initialiser le tracker de changements
        const changeTracker = new FormChangeTracker('form[action="{{ route('profile.update') }}"]', {
            excludeFields: ['_token', 'avatar', 'remove_avatar'],
            showVisualIndicators: true
        });
        
        // Écouter les changements du formulaire
        const form = document.querySelector('form[action="{{ route('profile.update') }}"]');
        if (form) {
            form.addEventListener('formChanged', function(e) {
                const { hasChanges, changedFields, changedCount } = e.detail;
                // Les changements sont maintenant détectés silencieusement
            });
            
            // Intercepter la soumission pour ne traiter que les champs modifiés
            form.addEventListener('submit', function(e) {
                if (!changeTracker.hasChanges()) {
                    e.preventDefault();
                    alert('Aucun changement détecté. Rien à sauvegarder.');
                    return false;
                }
                
                // Ajouter les champs modifiés en tant que données cachées
                const changedFields = changeTracker.getChangedFields();
                
                // Créer un champ caché avec la liste des champs modifiés
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'changed_fields';
                hiddenInput.value = JSON.stringify(changedFields);
                form.appendChild(hiddenInput);
            });
        }
        
        // Hook avec le gestionnaire de localisation pour détecter les changements de localisation
        if (window.ProfileLocationManager) {
            // Surcharger les méthodes de changement de mode pour trigger le change tracker
            const originalSwitchToAutoMode = locationManager.switchToAutoMode;
            const originalSwitchToManualMode = locationManager.switchToManualMode;
            
            locationManager.switchToAutoMode = function(...args) {
                const result = originalSwitchToAutoMode.apply(this, args);
                // Donner un délai pour que les champs soient remplis
                setTimeout(() => changeTracker.checkAllFields(), 100);
                return result;
            };
            
            locationManager.switchToManualMode = function(...args) {
                const result = originalSwitchToManualMode.apply(this, args);
                // Donner un délai pour que les champs soient remplis
                setTimeout(() => changeTracker.checkAllFields(), 100);
                return result;
            };
        }
        
        // Gestion de l'aperçu de l'avatar dans le profil
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        const removeAvatarCheckbox = document.querySelector('input[name="remove_avatar"]');
        const originalAvatarSrc = avatarPreview.src;
        
        // Prévisualiser l'image uploadée
        if (avatarInput) {
            avatarInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    // Vérifier la taille du fichier (500KB max)
                    if (file.size > 500 * 1024) {
                        alert('Le fichier est trop volumineux. Maximum 500KB autorisé.');
                        this.value = '';
                        return;
                    }
                    
                    // Vérifier le type de fichier
                    if (!file.type.match('image.*')) {
                        alert('Veuillez sélectionner un fichier image.');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    
                    // Décocher la suppression si un nouveau fichier est sélectionné
                    if (removeAvatarCheckbox) {
                        removeAvatarCheckbox.checked = false;
                    }
                } else {
                    // Remettre l'image originale si aucun fichier
                    avatarPreview.src = originalAvatarSrc;
                }
            });
        }

        // Gérer la case à cocher de suppression d'avatar
        if (removeAvatarCheckbox) {
            removeAvatarCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Montrer une image par défaut ou vide
                    avatarPreview.src = '/images/default-avatar.svg';
                    // Vider le champ de fichier
                    if (avatarInput) {
                        avatarInput.value = '';
                    }
                } else {
                    // Remettre l'image originale
                    avatarPreview.src = originalAvatarSrc;
                }
            });
        }

        // Support drag & drop pour l'avatar
        const avatarUploadDiv = document.getElementById('avatar-upload');
        if (avatarUploadDiv) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                avatarUploadDiv.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                avatarUploadDiv.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                avatarUploadDiv.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                const uploadDiv = avatarUploadDiv.querySelector('div.border-dashed');
                if (uploadDiv) {
                    uploadDiv.classList.add('border-blue-500', 'bg-blue-200');
                }
            }
            
            function unhighlight() {
                const uploadDiv = avatarUploadDiv.querySelector('div.border-dashed');
                if (uploadDiv) {
                    uploadDiv.classList.remove('border-blue-500', 'bg-blue-200');
                }
            }
            
            avatarUploadDiv.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    const file = files[0];
                    
                    // Vérification immédiate pour le drag & drop
                    if (file.size > 500 * 1024) {
                        alert('Le fichier est trop volumineux. Maximum 500KB autorisé.');
                        return;
                    }
                    
                    if (!file.type.match('image.*')) {
                        alert('Veuillez sélectionner un fichier image.');
                        return;
                    }
                    
                    avatarInput.files = files;
                    avatarInput.dispatchEvent(new Event('change'));
                }
            }
        }
    }
    
    // Initialiser les composants
    initializeComponents();
});
</script>

@vite(['resources/js/profile-location.js', 'resources/js/form-change-tracker.js'])

@endsection