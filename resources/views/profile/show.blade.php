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
                        <a href="{{ route('public.profile', $user->name) }}" target="_blank" 
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

            <!-- Messages de succès/erreur -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 m-6 rounded-lg">
                    {{ session('success') }}
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
                            <div class="flex items-center space-x-6">
                                <div class="flex-shrink-0">
                                    <img id="avatar-preview" class="h-20 w-20 rounded-full object-cover border-2 border-gray-300" 
                                         src="{{ $user->getAvatarUrl() }}" 
                                         alt="Avatar de {{ $user->name }}">
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="avatar" name="avatar" accept="image/*"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG ou WebP. Maximum 2MB. Laissez vide pour conserver l'avatar actuel.</p>
                                    @if($user->avatar)
                                        <label class="flex items-center mt-2">
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
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">Pays de résidence *</label>
                                <select id="country_residence" name="country_residence" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <option value="">Sélectionnez un pays</option>
                                    @include('partials.countries', ['selected' => old('country_residence', $user->country_residence)])
                                </select>
                            </div>
                            
                            <div>
                                <label for="city_residence" class="block text-sm font-medium text-gray-700 mb-2">Ville de résidence</label>
                                <input type="text" id="city_residence" name="city_residence" value="{{ old('city_residence', $user->city_residence) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    placeholder="Paris, Londres, Tokyo...">
                            </div>
                        </div>
                        
                        <!-- Pays de destination (si résidence en France) -->
                        <div id="destination-country-container" class="mt-6" style="display: none;">
                            <label for="destination_country" class="block text-sm font-medium text-gray-700 mb-2">Pays de destination souhaité</label>
                            <select id="destination_country" name="destination_country"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Sélectionnez votre pays de destination</option>
                                @include('partials.countries', ['selected' => old('destination_country', $user->destination_country), 'exclude' => 'France'])
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Où souhaitez-vous vous expatrier ?</p>
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
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                </div>
                                <div class="ml-3">
                                    <label for="share_location" class="text-sm font-medium text-blue-800">
                                        Apparaître sur la carte des membres
                                    </label>
                                    <p class="text-xs text-blue-600 mt-1">
                                        Permettez aux autres membres de vous localiser de manière approximative sur la carte interactive. 
                                        Votre position exacte ne sera jamais partagée (rayon d'environ 10 km pour protéger votre vie privée).
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Informations de localisation actuelle -->
                            <div id="current-location-info" class="mt-4 p-4 bg-white rounded-lg border border-blue-200">
                                @if($user->hasLocationSharing())
                                    <div class="flex items-center text-green-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="font-medium">Localisation active</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Position: {{ $user->getDisplayLocation() }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Dernière mise à jour: {{ $user->updated_at ? $user->updated_at->format('d/m/Y à H:i') : 'Jamais' }}
                                    </p>
                                @else
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <span class="font-medium">Localisation inactive</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Vous n'apparaissez pas sur la carte des membres.
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Boutons de gestion de localisation -->
                            <div id="location-controls" class="flex space-x-3 mt-4">
                                <button type="button" id="updateLocationBtn" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition duration-200 text-sm font-medium">
                                    📍 Mettre à jour ma position
                                </button>
                                <button type="button" id="removeLocationBtn" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 transition duration-200 text-sm font-medium">
                                    🗑️ Supprimer ma position
                                </button>
                            </div>
                            
                            <!-- Status de localisation -->
                            <div id="location-status" class="mt-4 p-3 rounded-lg" style="display: none;">
                                <!-- Les messages de statut apparaîtront ici -->
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

<script src="/js/geolocation.js"></script>
<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    const countryResidence = document.getElementById('country_residence');
    const destinationContainer = document.getElementById('destination-country-container');
    
    // Fonction pour afficher/masquer le champ destination
    function toggleDestinationCountry() {
        if (countryResidence.value === 'France') {
            destinationContainer.style.display = 'block';
        } else {
            destinationContainer.style.display = 'none';
            document.getElementById('destination_country').value = '';
        }
    }
    
    // Écouter les changements
    countryResidence.addEventListener('change', toggleDestinationCountry);
    
    // Vérifier au chargement
    toggleDestinationCountry();
    
    // Gestion de la localisation
    const geoService = new GeolocationService();
    const locationUI = new LocationUI(geoService);
    const updateLocationBtn = document.getElementById('updateLocationBtn');
    const removeLocationBtn = document.getElementById('removeLocationBtn');
    const locationStatus = document.getElementById('location-status');
    const shareLocationCheckbox = document.getElementById('share_location');
    
    // Fonction d'affichage des messages de statut
    function showLocationStatus(message, type = 'info') {
        locationStatus.style.display = 'block';
        locationStatus.className = `mt-4 p-3 rounded-lg ${type === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 
                                                          type === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 
                                                          'bg-blue-50 border border-blue-200 text-blue-700'}`;
        locationStatus.innerHTML = message;
        
        // Masquer après 5 secondes pour les messages de succès
        if (type === 'success') {
            setTimeout(() => {
                locationStatus.style.display = 'none';
            }, 5000);
        }
    }
    
    // Mettre à jour la position
    updateLocationBtn.addEventListener('click', async function() {
        if (!geoService.isGeolocationSupported()) {
            showLocationStatus('❌ La géolocalisation n\'est pas supportée par votre navigateur.', 'error');
            return;
        }
        
        this.disabled = true;
        this.textContent = '📍 Localisation en cours...';
        
        try {
            showLocationStatus('🔍 Obtention de votre position...', 'info');
            
            const position = await geoService.getCurrentPosition();
            
            showLocationStatus(`📍 Position détectée: ${position.city}. Enregistrement...`, 'info');
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const result = await geoService.saveLocationToServer(csrfToken);
            
            showLocationStatus('✅ Votre position a été mise à jour avec succès!', 'success');
            shareLocationCheckbox.checked = true;
            
            // Recharger la page après 2 secondes pour afficher les nouvelles données
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            
        } catch (error) {
            showLocationStatus('❌ ' + error.message, 'error');
        } finally {
            this.disabled = false;
            this.textContent = '📍 Mettre à jour ma position';
        }
    });
    
    // Supprimer la position
    removeLocationBtn.addEventListener('click', async function() {
        if (!confirm('Êtes-vous sûr de vouloir supprimer votre position de la carte ?')) {
            return;
        }
        
        this.disabled = true;
        this.textContent = '🗑️ Suppression...';
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch('/api/remove-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            if (!response.ok) {
                throw new Error('Erreur lors de la suppression');
            }
            
            showLocationStatus('✅ Votre position a été supprimée de la carte.', 'success');
            shareLocationCheckbox.checked = false;
            
            // Recharger la page après 2 secondes
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            
        } catch (error) {
            showLocationStatus('❌ Erreur lors de la suppression: ' + error.message, 'error');
        } finally {
            this.disabled = false;
            this.textContent = '🗑️ Supprimer ma position';
        }
    });
    
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
                // Vérifier la taille du fichier (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux. Maximum 2MB autorisé.');
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
                // Revenir à l'avatar original
                avatarPreview.src = originalAvatarSrc;
            }
        });
    }
    
    // Gérer la case à cocher "Supprimer l'avatar"
    if (removeAvatarCheckbox) {
        removeAvatarCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Afficher un aperçu de l'avatar par défaut
                const userName = document.getElementById('name').value || 'Avatar';
                avatarPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=3B82F6&color=fff&size=80`;
                // Vider l'input file
                if (avatarInput) {
                    avatarInput.value = '';
                }
            } else {
                // Revenir à l'avatar original
                avatarPreview.src = originalAvatarSrc;
            }
        });
    }
});
</script>

@endsection