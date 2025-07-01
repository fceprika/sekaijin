@extends('layout')

@section('title', 'Mon profil - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <!-- En-tête du profil -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Mon Profil</h1>
                        <p class="text-blue-100 mt-2">Gérez vos informations personnelles</p>
                    </div>
                    <div>
                        <a href="{{ route('public.profile', $user->name) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg font-medium transition duration-200 backdrop-blur-sm border border-white border-opacity-20">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Voir mon profil public
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-8">
                    @csrf

                    <!-- Informations de base -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informations de base
                        </h2>
                        
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
                            <textarea id="bio" name="bio" rows="4" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-y"
                                placeholder="Parlez-nous de vous, de votre parcours d'expatrié, de vos passions...">{{ old('bio', $user->bio) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Maximum 1000 caractères</p>
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
                                <input type="password" id="new_password" name="new_password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
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

@endsection