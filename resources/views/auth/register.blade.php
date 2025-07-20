@extends('layout')

@section('title', 'Rejoignez les expatriés français du monde entier - Sekaijin')

@section('content')
<!-- Background avec effet moderne -->
<div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-700">
    <!-- Motifs de fond subtils -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 1200 800" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M200 300C200 300 250 280 300 300C350 320 400 290 450 310C500 330 550 300 600 320C650 340 700 310 750 330C800 350 850 320 900 340C950 360 1000 330 1050 350" stroke="white" stroke-width="2" opacity="0.3"/>
            <path d="M150 400C150 400 200 380 250 400C300 420 350 390 400 410C450 430 500 400 550 420C600 440 650 410 700 430C750 450 800 420 850 440" stroke="white" stroke-width="2" opacity="0.3"/>
            <circle cx="300" cy="250" r="3" fill="white" opacity="0.4"/>
            <circle cx="500" cy="200" r="4" fill="white" opacity="0.3"/>
            <circle cx="800" cy="280" r="3" fill="white" opacity="0.4"/>
        </svg>
    </div>
    
    <div class="relative z-10 flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">
            <!-- Conteneur principal du formulaire -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                
                <!-- En-tête avec indicateur d'étapes -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6 text-white">
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold mb-2">Rejoignez Sekaijin</h1>
                        <p class="text-blue-100">La communauté des expatriés français</p>
                    </div>
                    
                    <!-- Indicateur d'étapes -->
                    <div class="flex items-center justify-center space-x-8">
                        <div class="flex items-center">
                            <div id="step1-indicator" class="w-8 h-8 rounded-full bg-white text-blue-600 flex items-center justify-center font-bold mr-3 flex-shrink-0 aspect-square">1</div>
                            <span id="step1-text" class="font-medium">Création de compte</span>
                        </div>
                        <div class="flex-1 h-1 bg-blue-400 rounded-full mx-4"></div>
                        <div class="flex items-center">
                            <div id="step2-indicator" class="w-8 h-8 rounded-full bg-blue-400 text-white flex items-center justify-center font-bold mr-3 flex-shrink-0 aspect-square">2</div>
                            <span id="step2-text" class="font-medium opacity-60">Compléter le profil</span>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-8">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulaire Étape 1: Création de compte -->
                    <form method="POST" action="{{ route('register') }}" id="step1-form">
                        @csrf
                        
                        <!-- ÉTAPE 1: Création de compte -->
                        <div id="step1" class="step-content">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Création de compte</h2>
                                <p class="text-gray-600">Quelques informations essentielles pour commencer</p>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Pseudo -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        👤 Pseudo *
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-lg"
                                            placeholder="Choisissez votre pseudo">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <div id="username-status" class="hidden">
                                                <span id="username-loading" class="text-gray-400 hidden">⏳</span>
                                                <span id="username-available" class="text-green-500 hidden">✅</span>
                                                <span id="username-taken" class="text-red-500 hidden">❌</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="username-message" class="mt-1 text-sm"></div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        📧 Adresse email *
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-lg"
                                        placeholder="votre@email.fr">
                                </div>

                                <!-- Pays d'intérêt (optionnel) -->
                                <div class="form-group">
                                    <label for="interest_country" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pays d'intérêt <span class="text-gray-500 text-xs">(optionnel)</span>
                                    </label>
                                    <select id="interest_country" name="interest_country"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                        <option value="">Sélectionnez un pays qui vous intéresse</option>
                                        @foreach(\App\Models\Country::orderBy('name_fr')->get() as $country)
                                            <option value="{{ $country->name_fr }}">{{ $country->emoji }} {{ $country->name_fr }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Quel pays vous intéresse le plus pour votre expatriation ?</p>
                                </div>

                                <!-- Mot de passe -->
                                <div>
                                    <label for="password_step1" class="block text-sm font-medium text-gray-700 mb-2">
                                        🔒 Mot de passe *
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="password_step1" name="password" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-lg"
                                            minlength="12" placeholder="12 caractères minimum">
                                        <button type="button" id="toggle-password-step1" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <span id="eye-icon-step1" class="text-gray-400 hover:text-gray-600 text-xl">👁️</span>
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div id="password-strength-bar-step1" class="h-full transition-all duration-300"></div>
                                            </div>
                                            <span id="password-strength-text-step1" class="text-xs font-medium"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Majuscule + minuscule + chiffre</p>
                                    </div>
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div>
                                    <label for="password_confirmation_step1" class="block text-sm font-medium text-gray-700 mb-2">
                                        🔒 Confirmer le mot de passe *
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="password_confirmation_step1" name="password_confirmation" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-lg"
                                            placeholder="Confirmez votre mot de passe">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <span id="password-match-icon-step1" class="hidden text-xl"></span>
                                        </div>
                                    </div>
                                    <div id="password-match-message-step1" class="mt-1 text-sm"></div>
                                </div>

                                <!-- Conditions obligatoires -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="flex items-start cursor-pointer">
                                        <input id="terms_step1" name="terms" type="checkbox" required
                                            class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 mt-0.5 mr-3">
                                        <span class="text-sm text-gray-700">
                                            J'accepte les <a href="{{ route('terms') }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">conditions d'utilisation</a> 
                                            et la <a href="{{ route('privacy') }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">politique de confidentialité</a> *
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Protection anti-spam Turnstile -->
                            <div class="mt-6">
                                <x-turnstile 
                                    data-action="register"
                                    data-callback="onTurnstileSuccess"
                                    data-error-callback="onTurnstileError"
                                />
                            </div>

                            <!-- Bouton Créer le compte -->
                            <div class="mt-8">
                                <button type="submit" id="create-account-btn" 
                                    class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:from-green-700 hover:to-blue-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                                    <span id="create-text">Créer mon compte</span>
                                    <span id="create-loading" class="hidden">⏳ Création en cours...</span>
                                </button>
                                <p class="text-center text-sm text-gray-500 mt-3">
                                    Votre compte sera créé et vous pourrez enrichir votre profil ensuite
                                </p>
                            </div>
                        </div>
                    </form>

                    <!-- Formulaire Étape 2: Enrichissement du profil -->
                    <form method="POST" action="{{ route('enrich.profile') }}" enctype="multipart/form-data" id="step2-form" class="hidden">
                        @csrf
                        
                        <!-- ÉTAPE 2: Enrichir le profil (optionnel) -->
                        <div id="step2" class="step-content">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl">🎉</span>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Compte créé avec succès !</h2>
                                <p class="text-gray-600">Bienvenue sur Sekaijin, <span id="welcome-name" class="font-medium"></span> !</p>
                                <p class="text-gray-500 text-sm mt-2">Enrichissez votre profil pour une meilleure expérience (optionnel)</p>
                            </div>
                            
                            <div class="space-y-8">
                                <!-- Photo de profil -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        📷 Photo de profil (optionnel)
                                    </label>
                                    <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                                        <div class="flex-shrink-0 flex justify-center sm:justify-start">
                                            <div class="relative">
                                                <img id="avatar-preview" class="h-20 w-20 rounded-full object-cover border-4 border-gray-200 shadow-md" 
                                                     src="https://ui-avatars.com/api/?name=Avatar&background=3B82F6&color=fff&size=80" 
                                                     alt="Aperçu avatar">
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="relative">
                                                <input type="file" id="avatar" name="avatar" accept="image/*" 
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                                <div class="flex items-center justify-center w-full px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 hover:border-gray-400 transition duration-200 cursor-pointer">
                                                    <div class="text-center">
                                                        <svg class="mx-auto h-6 w-6 text-gray-400 mb-1" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        <p class="text-sm font-medium text-gray-600" id="upload-text">Choisir une image</p>
                                                        <p class="text-xs text-gray-500">JPG, PNG • Max 100KB</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Géolocalisation -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        📍 Localisation (optionnelle)
                                    </label>
                                    
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

                                    <!-- Boutons de choix de mode -->
                                    <div class="mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <button type="button" id="auto-location-btn" 
                                                class="w-full bg-gradient-to-r from-blue-500 to-green-500 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-green-600 transition duration-200 flex items-center justify-center shadow-md">
                                                <span class="mr-2">🌍</span>
                                                <span>Détecter automatiquement</span>
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
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">
                                                    🗺️ Pays de résidence
                                                </label>
                                                <select id="country_residence" name="country_residence"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                                    <option value="">Sélectionnez un pays</option>
                                                    @include('partials.countries', ['selected' => old('country_residence'), 'filter' => 'europe_asia'])
                                                </select>
                                                <p class="text-xs text-gray-500 mt-1">🌍 Actuellement disponible pour l'Europe et l'Asie uniquement</p>
                                            </div>
                                            <div>
                                                <label for="city_residence" class="block text-sm font-medium text-gray-700 mb-2">
                                                    🏙️ Ville de résidence
                                                </label>
                                                <select id="city_residence" name="city_residence" disabled
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                                    <option value="">Sélectionnez d'abord un pays</option>
                                                    @if(old('city_residence'))
                                                        <option value="{{ old('city_residence') }}" selected>{{ old('city_residence') }}</option>
                                                    @endif
                                                </select>
                                                <div id="city-loading" class="hidden mt-2 text-sm text-blue-600 flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Chargement des villes...
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">💡 Les villes sont chargées automatiquement selon votre pays</p>
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
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                    
                                    <!-- Carte des membres -->
                                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <label class="flex items-start cursor-pointer">
                                            <input id="share_location" name="share_location" type="checkbox" value="1" disabled
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 mt-0.5 mr-3 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <div>
                                                <span class="text-base font-medium text-blue-800">🗺️ Apparaître sur la carte des membres</span>
                                                <p class="text-sm text-blue-700 mt-1">Permettez aux autres membres de vous localiser approximativement.</p>
                                                <p id="location-requirement-register" class="text-xs text-orange-600 mt-1 font-medium">
                                                    ⚠️ Vous devez sélectionner une ville ou utiliser la géolocalisation automatique pour activer cette option.
                                                </p>
                                                <p class="text-xs text-gray-600 mt-2">
                                                    <span class="text-green-600">🛡️</span> <strong>Nous ne partageons jamais votre position exacte.</strong> Zone de 10 km, modifiable à tout moment.
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <!-- Visibilité du profil -->
                                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <label class="flex items-start cursor-pointer">
                                            <input id="is_public_profile" name="is_public_profile" type="checkbox" value="1"
                                                class="w-5 h-5 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500 focus:ring-2 mt-0.5 mr-3">
                                            <div>
                                                <span class="text-base font-medium text-yellow-800">👁️ Profil public accessible à tous</span>
                                                <p class="text-sm text-yellow-700 mt-1">
                                                    Permettez aux personnes non connectées de voir votre profil public. Si cette option est désactivée, seuls les membres connectés pourront voir votre profil.
                                                </p>
                                                <p class="text-xs text-gray-600 mt-2">
                                                    <span class="text-blue-600">🔗</span> URL de votre profil : <span class="font-mono text-xs"><span id="profile-url-base"></span>/membre/<span id="profile-url-preview" class="font-bold">votre-pseudo</span></span>
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            </div>


                            <!-- Boutons navigation -->
                            <div class="mt-8 space-y-4">
                                <!-- Bouton principal : Enrichir le profil -->
                                <button type="submit" id="enrich-profile-btn"
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                                    <span id="enrich-text">✨ Enrichir mon profil</span>
                                    <span id="enrich-loading" class="hidden">⏳ Mise à jour en cours...</span>
                                </button>
                                
                                <!-- Bouton secondaire : Passer cette étape -->
                                <div class="text-center">
                                    <a href="/" id="skip-step2" 
                                       class="inline-flex items-center px-6 py-3 text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                                        Passer cette étape et découvrir Sekaijin →
                                    </a>
                                </div>
                                
                                <p class="text-center text-xs text-gray-500">
                                    Vous pourrez toujours enrichir votre profil plus tard depuis votre espace personnel
                                </p>
                            </div>
                        </div>
                    </form>

                    <!-- Lien vers connexion (seulement visible à l'étape 1) -->
                    <div id="login-link" class="text-center pt-6 border-t border-gray-200 mt-8">
                        <p class="text-gray-600">
                            Déjà membre ? 
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium underline">
                                Se connecter
                            </a>
                        </p>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Éléments principaux
    const step1Form = document.getElementById('step1-form');
    const step2Form = document.getElementById('step2-form');
    const loginLink = document.getElementById('login-link');
    
    // Éléments indicateur d'étapes
    const step1Indicator = document.getElementById('step1-indicator');
    const step2Indicator = document.getElementById('step2-indicator');
    const step1Text = document.getElementById('step1-text');
    const step2Text = document.getElementById('step2-text');
    
    // Éléments du formulaire
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const destinationCountryInput = document.getElementById('interest_country');
    const passwordInput = document.getElementById('password_step1');
    const passwordConfirmInput = document.getElementById('password_confirmation_step1');
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    
    // Initialiser l'URL de base au chargement
    updateProfileUrlPreview();
    
    // Navigation entre étapes
    function showStep2(userData) {
        step1Form.classList.add('hidden');
        step2Form.classList.remove('hidden');
        loginLink.classList.add('hidden');
        
        // Mettre à jour l'indicateur
        step1Indicator.classList.remove('bg-white', 'text-blue-600');
        step1Indicator.classList.add('bg-green-500', 'text-white');
        step1Text.classList.add('opacity-60');
        
        step2Indicator.classList.remove('bg-blue-400');
        step2Indicator.classList.add('bg-green-500', 'text-white');
        step2Text.classList.remove('opacity-60');
        
        // Mettre à jour le message de bienvenue
        if (userData && userData.name) {
            document.getElementById('welcome-name').textContent = userData.name;
        }
        
        // Faire défiler en haut
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Fonction pour afficher les erreurs
    function showErrors(errors) {
        // Supprimer les anciennes erreurs
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        document.querySelectorAll('.error-alert').forEach(el => el.remove());
        
        // Créer une alerte générale d'erreur
        const errorAlert = document.createElement('div');
        errorAlert.className = 'error-alert bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6';
        errorAlert.innerHTML = `
            <div class="flex items-center">
                <span class="text-red-500 mr-2">⚠️</span>
                <strong>Erreur de validation :</strong>
            </div>
            <ul class="list-disc list-inside mt-2 space-y-1">
                ${Object.values(errors).map(error => `<li>${error[0]}</li>`).join('')}
            </ul>
        `;
        
        // Insérer l'alerte au début du formulaire
        const form = document.getElementById('step1');
        form.insertBefore(errorAlert, form.firstChild);
        
        // Afficher aussi les erreurs spécifiques à chaque champ
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                // Ajouter une bordure rouge au champ
                input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message mt-1 text-sm text-red-500 font-medium';
                errorDiv.textContent = errors[field][0];
                input.parentNode.appendChild(errorDiv);
            }
        });
        
        // Faire défiler vers le haut pour voir les erreurs
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Fonction pour nettoyer les erreurs d'un champ
    function clearFieldErrors(field) {
        const input = document.getElementById(field);
        if (input) {
            input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            const errorMsg = input.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        }
    }
    
    // Soumission du formulaire étape 1 (création de compte)
    step1Form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const createBtn = document.getElementById('create-account-btn');
        const createText = document.getElementById('create-text');
        const createLoading = document.getElementById('create-loading');
        
        // Désactiver le bouton et afficher le loading
        createBtn.disabled = true;
        createText.classList.add('hidden');
        createLoading.classList.remove('hidden');
        
        try {
            const formData = new FormData(this);
            
            // Use Laravel route helper for better reliability
            const registrationUrl = '{{ route("register") }}';
            const response = await fetch(registrationUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Compte créé avec succès, passer à l'étape 2
                showStep2(data.user);
            } else {
                // Afficher les erreurs
                if (data.errors) {
                    showErrors(data.errors);
                } else {
                    alert('Une erreur est survenue lors de la création du compte.');
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur de connexion est survenue. Veuillez réessayer.');
        } finally {
            // Réactiver le bouton
            createBtn.disabled = false;
            createText.classList.remove('hidden');
            createLoading.classList.add('hidden');
        }
    });
    
    // Vérification pseudo en temps réel
    let usernameTimeout;
    nameInput.addEventListener('input', function() {
        const username = this.value.trim();
        const statusDiv = document.getElementById('username-status');
        const messageDiv = document.getElementById('username-message');
        const loadingIcon = document.getElementById('username-loading');
        const availableIcon = document.getElementById('username-available');
        const takenIcon = document.getElementById('username-taken');
        
        updateAvatarPreview();
        updateProfileUrlPreview();
        
        if (username.length < 3) {
            statusDiv.classList.add('hidden');
            messageDiv.textContent = '';
            return;
        }
        
        clearTimeout(usernameTimeout);
        usernameTimeout = setTimeout(async () => {
            statusDiv.classList.remove('hidden');
            loadingIcon.classList.remove('hidden');
            availableIcon.classList.add('hidden');
            takenIcon.classList.add('hidden');
            messageDiv.textContent = 'Vérification...';
            messageDiv.className = 'mt-1 text-sm text-gray-500';
            
            try {
                const response = await fetch(`/api/check-username/${encodeURIComponent(username)}`);
                const data = await response.json();
                
                loadingIcon.classList.add('hidden');
                
                if (data.available) {
                    availableIcon.classList.remove('hidden');
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'mt-1 text-sm text-green-500';
                } else {
                    takenIcon.classList.remove('hidden');
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'mt-1 text-sm text-red-500';
                }
            } catch (error) {
                loadingIcon.classList.add('hidden');
                messageDiv.textContent = 'Erreur de vérification';
                messageDiv.className = 'mt-1 text-sm text-gray-500';
            }
        }, 500);
    });
    
    // Gestion avatar
    function updateAvatarPreview() {
        const name = nameInput.value || 'Avatar';
        if (!avatarInput.files || avatarInput.files.length === 0) {
            avatarPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=3B82F6&color=fff&size=80`;
        }
    }
    
    function updateProfileUrlPreview() {
        const username = nameInput.value.trim();
        const previewElement = document.getElementById('profile-url-preview');
        const baseUrlElement = document.getElementById('profile-url-base');
        
        // Mettre à jour l'URL de base
        if (baseUrlElement) {
            baseUrlElement.textContent = window.location.origin;
        }
        
        // Mettre à jour le pseudo
        if (previewElement) {
            if (username) {
                // Convertir en slug (minuscules, remplacer espaces par tirets)
                const slug = username.toLowerCase().replace(/[^a-z0-9._-]/g, '');
                previewElement.textContent = slug || 'votre-pseudo';
            } else {
                previewElement.textContent = 'votre-pseudo';
            }
        }
    }
    
    avatarInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const uploadText = document.getElementById('upload-text');
        
        if (file) {
            if (file.size > 100 * 1024) {
                alert('Le fichier est trop volumineux. Maximum 100KB autorisé.');
                this.value = '';
                return;
            }
            
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
            
            uploadText.textContent = file.name;
        } else {
            updateAvatarPreview();
            uploadText.textContent = 'Choisir une image';
        }
    });
    
    // Force du mot de passe
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('password-strength-bar-step1');
        const strengthText = document.getElementById('password-strength-text-step1');
        
        // Nettoyer les erreurs du champ mot de passe
        clearFieldErrors('password');
        
        let strength = 0;
        if (password.length >= 12) strength += 25;
        if (/[a-z]/.test(password)) strength += 25;
        if (/[A-Z]/.test(password)) strength += 25;
        if (/\d/.test(password)) strength += 25;
        
        if (strength === 0) {
            strengthBar.style.width = '0%';
            strengthText.textContent = '';
        } else if (strength <= 25) {
            strengthBar.style.width = '25%';
            strengthBar.className = 'h-full bg-red-500 transition-all duration-300';
            strengthText.textContent = 'Faible';
            strengthText.className = 'text-xs font-medium text-red-500';
        } else if (strength <= 50) {
            strengthBar.style.width = '50%';
            strengthBar.className = 'h-full bg-orange-500 transition-all duration-300';
            strengthText.textContent = 'Moyen';
            strengthText.className = 'text-xs font-medium text-orange-500';
        } else if (strength <= 75) {
            strengthBar.style.width = '75%';
            strengthBar.className = 'h-full bg-yellow-500 transition-all duration-300';
            strengthText.textContent = 'Bon';
            strengthText.className = 'text-xs font-medium text-yellow-600';
        } else {
            strengthBar.style.width = '100%';
            strengthBar.className = 'h-full bg-green-500 transition-all duration-300';
            strengthText.textContent = 'Fort';
            strengthText.className = 'text-xs font-medium text-green-500';
        }
        
        checkPasswordMatch();
    });
    
    // Vérification correspondance mots de passe
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = passwordConfirmInput.value;
        const matchIcon = document.getElementById('password-match-icon-step1');
        const matchMessage = document.getElementById('password-match-message-step1');
        
        if (confirmPassword.length === 0) {
            matchIcon.classList.add('hidden');
            matchMessage.textContent = '';
            return;
        }
        
        matchIcon.classList.remove('hidden');
        
        if (password === confirmPassword) {
            matchIcon.textContent = '✅';
            matchMessage.textContent = 'Les mots de passe correspondent';
            matchMessage.className = 'mt-1 text-sm text-green-500';
        } else {
            matchIcon.textContent = '❌';
            matchMessage.textContent = 'Les mots de passe ne correspondent pas';
            matchMessage.className = 'mt-1 text-sm text-red-500';
        }
    }
    
    passwordConfirmInput.addEventListener('input', function() {
        clearFieldErrors('password_confirmation');
        checkPasswordMatch();
    });
    
    // Nettoyer les erreurs quand l'utilisateur tape dans les champs
    nameInput.addEventListener('input', function() {
        clearFieldErrors('name');
    });
    
    emailInput.addEventListener('input', function() {
        clearFieldErrors('email');
    });
    
    // Toggle visibilité mot de passe
    document.getElementById('toggle-password-step1').addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        document.getElementById('eye-icon-step1').textContent = type === 'password' ? '👁️' : '🙈';
    });
    
    // Géolocalisation et gestion des modes
    const autoLocationBtn = document.getElementById('auto-location-btn');
    const manualLocationBtn = document.getElementById('manual-location-btn');
    const geolocationMessage = document.getElementById('geolocation-message');
    const detectedLocation = document.getElementById('detected-location');
    const countrySelect = document.getElementById('country_residence');
    const cityInput = document.getElementById('city_residence');
    
    // Fonction pour mettre à jour l'état visuel des boutons
    function updateButtonStates() {
        const isAutoMode = !document.getElementById('auto-location-section').classList.contains('hidden');
        const isManualMode = !document.getElementById('manual-location-section').classList.contains('hidden');
        
        if (isAutoMode) {
            // Mode automatique actif - couleur vive + ring
            autoLocationBtn.className = 'w-full bg-gradient-to-r from-blue-600 to-green-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-green-700 transition duration-200 flex items-center justify-center shadow-lg ring-2 ring-blue-300';
            manualLocationBtn.className = 'w-full bg-gradient-to-r from-orange-400 to-red-400 text-white py-3 px-4 rounded-lg font-medium hover:from-orange-500 hover:to-red-500 transition duration-200 flex items-center justify-center shadow-md opacity-75';
        } else if (isManualMode) {
            // Mode manuel actif - couleur vive + ring
            autoLocationBtn.className = 'w-full bg-gradient-to-r from-blue-400 to-green-400 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-500 hover:to-green-500 transition duration-200 flex items-center justify-center shadow-md opacity-75';
            manualLocationBtn.className = 'w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-3 px-4 rounded-lg font-medium hover:from-orange-700 hover:to-red-700 transition duration-200 flex items-center justify-center shadow-lg ring-2 ring-orange-300';
        } else {
            // Aucun mode actif (état initial) - couleurs attractives par défaut
            autoLocationBtn.className = 'w-full bg-gradient-to-r from-blue-500 to-green-500 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-green-600 transition duration-200 flex items-center justify-center shadow-md';
            manualLocationBtn.className = 'w-full bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 px-4 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition duration-200 flex items-center justify-center shadow-md';
        }
    }
    
    // Initialiser l'état des boutons
    updateButtonStates();
    
    // Géolocalisation automatique
    autoLocationBtn.addEventListener('click', async function() {
        if (!navigator.geolocation) {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            return;
        }

        const originalHtml = this.innerHTML;
        this.innerHTML = '<span class="mr-2">⏳</span><span>Détection en cours...</span>';
        this.disabled = true;
        
        try {
            // Use the same geolocation logic as the profile page
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(
                    resolve,
                    reject,
                    {
                        enableHighAccuracy: false,
                        timeout: 10000,
                        maximumAge: 300000 // 5 minutes cache
                    }
                );
            });

            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Use internal API to avoid CSP issues
            const response = await fetch(`/api/geocode?lat=${lat}&lng=${lng}`);

            if (!response.ok) {
                throw new Error('Erreur de géocodage');
            }

            const data = await response.json();
            
            if (!data.success || !data.city || !data.countryName) {
                throw new Error('Impossible de déterminer votre localisation');
            }

            const city = data.city;
            const countryDisplayName = data.countryName;
            
            // Basculer vers le mode automatique avec les données détectées
            switchToAutoMode(countryDisplayName, city, lat, lng);
            
            // Mettre à jour l'état visuel des boutons
            updateButtonStates();
            
            this.innerHTML = '<span class="mr-2">✅</span><span>Position détectée</span>';

        } catch (error) {
            console.warn('Erreur de géolocalisation:', error);
            this.innerHTML = '<span class="mr-2">❌</span><span>Erreur de localisation</span>';
            
            // Show user-friendly error message
            let errorMessage = 'Erreur de localisation';
            if (error.code === 1) {
                errorMessage = 'Autorisation refusée';
            } else if (error.code === 2) {
                errorMessage = 'Position indisponible';
            } else if (error.code === 3) {
                errorMessage = 'Délai dépassé';
            }
            this.innerHTML = `<span class="mr-2">❌</span><span>${errorMessage}</span>`;
        } finally {
            this.disabled = false;
        }
    });
    
    // Bouton mode manuel
    manualLocationBtn.addEventListener('click', function() {
        switchToManualMode();
        updateButtonStates();
    });
    
    // Variables globales pour la géolocalisation
    let userGeolocation = null;
    let geolocationRequested = false;
    
    // Gestion du checkbox de géolocalisation
    const shareLocationCheckbox = document.getElementById('share_location');
    if (shareLocationCheckbox) {
        shareLocationCheckbox.addEventListener('change', async function() {
            if (this.checked && !geolocationRequested && 'geolocation' in navigator) {
                geolocationRequested = true;
                
                // Afficher un indicateur de demande de géolocalisation
                const geoStatus = document.createElement('div');
                geoStatus.id = 'geo-status';
                geoStatus.className = 'mt-2 text-sm text-blue-600';
                geoStatus.textContent = '📍 Demande d\'autorisation de géolocalisation...';
                this.parentNode.appendChild(geoStatus);
                
                try {
                    const position = await new Promise((resolve, reject) => {
                        const timeout = setTimeout(() => {
                            reject(new Error('Timeout'));
                        }, 10000); // 10 secondes timeout
                        
                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                clearTimeout(timeout);
                                resolve(pos);
                            },
                            (err) => {
                                clearTimeout(timeout);
                                reject(err);
                            },
                            {
                                enableHighAccuracy: false,
                                timeout: 8000,
                                maximumAge: 300000 // 5 minutes
                            }
                        );
                    });
                    
                    userGeolocation = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    };
                    
                    geoStatus.textContent = '✅ Géolocalisation obtenue';
                    geoStatus.className = 'mt-2 text-sm text-green-600';
                    
                } catch (geoError) {
                    console.warn('Impossible d\'obtenir la géolocalisation:', geoError);
                    userGeolocation = null;
                    geoStatus.textContent = '❌ Géolocalisation non disponible (optionnel)';
                    geoStatus.className = 'mt-2 text-sm text-orange-600';
                    
                    // Ne pas décocher automatiquement, laisser l'utilisateur décider
                }
                
                // Supprimer le message après 3 secondes
                setTimeout(() => {
                    if (geoStatus && geoStatus.parentNode) {
                        geoStatus.parentNode.removeChild(geoStatus);
                    }
                }, 3000);
            }
        });
    }
    
    // Soumission du formulaire étape 2 (enrichissement du profil)
    step2Form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const enrichBtn = document.getElementById('enrich-profile-btn');
        const enrichText = document.getElementById('enrich-text');
        const enrichLoading = document.getElementById('enrich-loading');
        
        // Désactiver le bouton et afficher le loading
        enrichBtn.disabled = true;
        enrichText.classList.add('hidden');
        enrichLoading.classList.remove('hidden');
        
        try {
            const formData = new FormData(this);
            
            // Ajouter les coordonnées si disponibles et si l'utilisateur a coché la case
            if (shareLocationCheckbox && shareLocationCheckbox.checked && userGeolocation) {
                formData.append('initial_latitude', userGeolocation.latitude);
                formData.append('initial_longitude', userGeolocation.longitude);
            }
            
            // Use explicit URL to avoid route resolution issues in CI
            const enrichProfileUrl = '{{ route("enrich.profile") }}';
            const response = await fetch(enrichProfileUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Rediriger vers l'accueil
                window.location.href = data.redirect_url || '/';
            } else {
                // Afficher les erreurs
                if (data.errors) {
                    showErrors(data.errors);
                } else {
                    alert('Une erreur est survenue lors de l\'enrichissement du profil.');
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur de connexion est survenue. Veuillez réessayer.');
        } finally {
            // Réactiver le bouton
            enrichBtn.disabled = false;
            enrichText.classList.remove('hidden');
            enrichLoading.classList.add('hidden');
        }
    });
    
    // Système de sélection dynamique de ville pour l'inscription
    const countryResidence = document.getElementById('country_residence');
    const cityResidence = document.getElementById('city_residence');
    const cityLoading = document.getElementById('city-loading');
    const locationRequirementRegister = document.getElementById('location-requirement-register');
    
    // Fonction pour charger les villes dynamiquement
    function loadCitiesForCountryRegister(countryName) {
        if (!countryName) {
            cityResidence.innerHTML = '<option value="">Sélectionnez d\'abord un pays</option>';
            cityResidence.disabled = true;
            cityLoading.classList.add('hidden');
            updateLocationSharingStateRegister();
            return;
        }
        
        // Activer le champ ville et afficher le loading
        cityResidence.disabled = false;
        cityResidence.innerHTML = '<option value="">Chargement des villes...</option>';
        cityLoading.classList.remove('hidden');
        
        // Appel AJAX pour récupérer les villes
        fetch('/api/cities', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ country: countryName })
        })
        .then(response => response.json())
        .then(data => {
            cityLoading.classList.add('hidden');
            cityResidence.innerHTML = '<option value="">Choisissez une ville</option>';
            
            if (data.success && data.cities && data.cities.length > 0) {
                data.cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    cityResidence.appendChild(option);
                });
            } else {
                cityResidence.innerHTML = '<option value="">Aucune ville trouvée</option>';
            }
            
            // Vérifier l'état de la checkbox après chargement
            updateLocationSharingStateRegister();
        })
        .catch(error => {
            console.error('Erreur lors du chargement des villes:', error);
            cityLoading.classList.add('hidden');
            cityResidence.innerHTML = '<option value="">Erreur de chargement</option>';
            updateLocationSharingStateRegister();
        });
    }
    
    // Fonction pour mettre à jour l'état de la checkbox de partage de localisation
    function updateLocationSharingStateRegister() {
        // Vérifier si on est en mode automatique
        const isAutoMode = !document.getElementById('auto-location-section').classList.contains('hidden');
        const hasDetectedLocation = document.getElementById('detected-city-value').value !== '';
        
        // Vérifier si des coordonnées sont disponibles
        const hasDetectedCoordinates = document.getElementById('detected-latitude') && document.getElementById('detected-latitude').value !== '';
        const hasManualCity = cityResidence && cityResidence.value && cityResidence.value !== '';
        
        // Activer la checkbox si : ville sélectionnée OU mode automatique avec coordonnées détectées
        const shouldActivateCheckbox = hasManualCity || (isAutoMode && (hasDetectedLocation || hasDetectedCoordinates));
        
        if (shouldActivateCheckbox) {
            shareLocationCheckbox.disabled = false;
            locationRequirementRegister.classList.add('hidden');
            
            // Auto-cocher la checkbox seulement lors de la première détection
            // (pas de données existantes en inscription, donc plus simple)
            if ((hasDetectedCoordinates || hasManualCity) && !shareLocationCheckbox.checked) {
                shareLocationCheckbox.checked = true;
            }
        } else {
            shareLocationCheckbox.disabled = true;
            locationRequirementRegister.classList.remove('hidden');
        }
    }
    
    // Écouter les changements sur le select de pays
    countryResidence.addEventListener('change', function() {
        loadCitiesForCountryRegister(this.value);
    });
    
    // Écouter les changements sur le select de ville
    cityResidence.addEventListener('change', updateLocationSharingStateRegister);
    
    // Charger les villes au chargement initial si un pays est sélectionné
    if (countryResidence.value) {
        loadCitiesForCountryRegister(countryResidence.value);
    } else {
        // S'assurer que la checkbox est désactivée
        updateLocationSharingStateRegister();
    }
    
    // Fonctions de basculement entre modes manuel/automatique
    function switchToAutoMode(country, city, lat, lng) {
        // Masquer mode manuel
        document.getElementById('manual-location-section').classList.add('hidden');
        
        // Afficher mode auto avec données
        document.getElementById('auto-location-section').classList.remove('hidden');
        document.getElementById('detected-country-display').value = country;
        document.getElementById('detected-city-display').value = city;
        
        // Remplir champs cachés
        document.getElementById('detected-country-value').value = country;
        document.getElementById('detected-city-value').value = city;
        document.getElementById('detected-latitude').value = lat;
        document.getElementById('detected-longitude').value = lng;
        
        // Activer checkbox localisation
        updateLocationSharingStateRegister();
    }

    function switchToManualMode() {
        // Afficher mode manuel
        document.getElementById('manual-location-section').classList.remove('hidden');
        
        // Masquer mode auto
        document.getElementById('auto-location-section').classList.add('hidden');
        
        // Vider champs cachés
        document.getElementById('detected-country-value').value = '';
        document.getElementById('detected-city-value').value = '';
        document.getElementById('detected-latitude').value = '';
        document.getElementById('detected-longitude').value = '';
        
        // Réinitialiser les dropdowns manuels seulement si pas de valeur existante
        if (!countryResidence.value) {
            countryResidence.value = '';
            cityResidence.innerHTML = '<option value="">Sélectionnez d\'abord un pays</option>';
            cityResidence.disabled = true;
        } else {
            // Si un pays est déjà sélectionné, charger les villes correspondantes
            loadCitiesForCountryRegister(countryResidence.value);
        }
        
        // Réinitialiser le bouton automatique
        autoLocationBtn.innerHTML = '<span class="mr-2">🌍</span><span>Détecter automatiquement</span>';
        autoLocationBtn.disabled = false;
        
        // Réinitialiser checkbox
        updateLocationSharingStateRegister();
    }
    
    // Event listener pour le bouton "Modifier manuellement"
    const editManualLocationBtn = document.getElementById('edit-manual-location');
    if (editManualLocationBtn) {
        editManualLocationBtn.addEventListener('click', function() {
            switchToManualMode();
            updateButtonStates();
        });
    }
    
    // Callbacks Turnstile pour étape 1
    window.onTurnstileSuccess = function(token) {
        console.log('Turnstile verification successful for step 1:', token);
        // Le token sera automatiquement inclus dans le formulaire
    };
    
    window.onTurnstileError = function(error) {
        console.error('Turnstile error for step 1:', error);
        alert('Erreur de vérification de sécurité. Veuillez recharger la page.');
    };
    
});
</script>
@endsection