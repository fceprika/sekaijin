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
                            <div id="step1-indicator" class="w-8 h-8 rounded-full bg-white text-blue-600 flex items-center justify-center font-bold mr-3">1</div>
                            <span id="step1-text" class="font-medium">Création de compte</span>
                        </div>
                        <div class="flex-1 h-1 bg-blue-400 rounded-full mx-4"></div>
                        <div class="flex items-center">
                            <div id="step2-indicator" class="w-8 h-8 rounded-full bg-blue-400 text-white flex items-center justify-center font-bold mr-3">2</div>
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

                                <!-- Pays d'intérêt (forcé à Thaïlande pour le moment) -->
                                <input type="hidden" id="country_interest" name="country_interest" value="Thaïlande">
                                
                                <!-- Message informatif -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">🇹🇭</span>
                                        <div>
                                            <p class="text-blue-800 font-medium">Destination : Thaïlande</p>
                                            <p class="text-blue-700 text-sm mt-1">
                                                Rejoignez notre communauté d'expatriés français en Thaïlande. 
                                                D'autres destinations seront bientôt disponibles !
                                            </p>
                                        </div>
                                    </div>
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
                                            J'accepte les <a href="#" class="text-blue-600 hover:text-blue-800 underline">conditions d'utilisation</a> 
                                            et la <a href="#" class="text-blue-600 hover:text-blue-800 underline">politique de confidentialité</a> *
                                        </span>
                                    </label>
                                </div>
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

                                    <!-- Bouton géolocalisation -->
                                    <div class="mb-4">
                                        <button type="button" id="geolocate-btn" 
                                            class="w-full bg-gradient-to-r from-green-500 to-blue-500 text-white py-3 px-4 rounded-lg font-medium hover:from-green-600 hover:to-blue-600 transition duration-200 flex items-center justify-center">
                                            <span id="geolocate-icon" class="mr-2">🌍</span>
                                            <span id="geolocate-text">Détecter automatiquement ma position</span>
                                        </button>
                                    </div>

                                    <!-- Champs manuels -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">
                                                🗺️ Pays de résidence
                                            </label>
                                            <select id="country_residence" name="country_residence"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                                <option value="">Sélectionnez un pays</option>
                                                @include('partials.countries', ['selected' => old('country_residence')])
                                            </select>
                                        </div>
                                        <div>
                                            <label for="city_residence" class="block text-sm font-medium text-gray-700 mb-2">
                                                🏙️ Ville de résidence
                                            </label>
                                            <input type="text" id="city_residence" name="city_residence" value="{{ old('city_residence') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                                placeholder="Votre ville">
                                        </div>
                                    </div>
                                    
                                    <!-- Carte des membres -->
                                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <label class="flex items-start cursor-pointer">
                                            <input id="share_location" name="share_location" type="checkbox" value="1"
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 mt-0.5 mr-3">
                                            <div>
                                                <span class="text-base font-medium text-blue-800">🗺️ Apparaître sur la carte des membres</span>
                                                <p class="text-sm text-blue-700 mt-1">Permettez aux autres membres de vous localiser approximativement.</p>
                                                <div class="mt-2 flex items-start space-x-2">
                                                    <span class="text-green-600">🛡️</span>
                                                    <p class="text-xs text-gray-600">
                                                        <strong>Nous ne partageons jamais votre position exacte.</strong><br>
                                                        Zone de 10 km, modifiable à tout moment.
                                                    </p>
                                                </div>
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
    const countryInterestSelect = document.getElementById('country_interest');
    const passwordInput = document.getElementById('password_step1');
    const passwordConfirmInput = document.getElementById('password_confirmation_step1');
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    
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
        step2Indicator.classList.add('bg-white', 'text-blue-600');
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
        
        // Afficher les nouvelles erreurs
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message mt-1 text-sm text-red-500';
                errorDiv.textContent = errors[field][0];
                input.parentNode.appendChild(errorDiv);
            }
        });
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
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
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
    
    passwordConfirmInput.addEventListener('input', checkPasswordMatch);
    
    // Toggle visibilité mot de passe
    document.getElementById('toggle-password-step1').addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        document.getElementById('eye-icon-step1').textContent = type === 'password' ? '👁️' : '🙈';
    });
    
    // Géolocalisation
    const geolocateBtn = document.getElementById('geolocate-btn');
    const geolocationMessage = document.getElementById('geolocation-message');
    const detectedLocation = document.getElementById('detected-location');
    const geolocateIcon = document.getElementById('geolocate-icon');
    const geolocateText = document.getElementById('geolocate-text');
    const countrySelect = document.getElementById('country_residence');
    const cityInput = document.getElementById('city_residence');
    
    const countryMappings = {
        'FR': 'France', 'TH': 'Thaïlande', 'JP': 'Japon', 'US': 'États-Unis', 'CA': 'Canada',
        'DE': 'Allemagne', 'GB': 'Royaume-Uni', 'ES': 'Espagne', 'IT': 'Italie', 'CH': 'Suisse',
        'BE': 'Belgique', 'AU': 'Australie', 'NL': 'Pays-Bas', 'PT': 'Portugal', 'AT': 'Autriche'
    };
    
    geolocateBtn.addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            return;
        }

        geolocateIcon.textContent = '⏳';
        geolocateText.textContent = 'Localisation en cours...';
        geolocateBtn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            async function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                try {
                    const response = await fetch(`/api/geocode?lat=${lat}&lng=${lng}`);
                    const data = await response.json();

                    if (data.success && data.country && data.city) {
                        const countryName = countryMappings[data.country] || data.countryName || data.country;
                        
                        const countryOption = Array.from(countrySelect.options).find(option => 
                            option.textContent.includes(countryName) || option.value === countryName
                        );
                        
                        if (countryOption) {
                            countrySelect.value = countryOption.value;
                        }

                        cityInput.value = data.city;
                        detectedLocation.textContent = `${countryName}, ${data.city}`;
                        geolocationMessage.classList.remove('hidden');

                        geolocateIcon.textContent = '✅';
                        geolocateText.textContent = 'Localisation détectée';
                        geolocateBtn.style.display = 'none';
                    } else {
                        throw new Error('Impossible de déterminer votre localisation');
                    }
                } catch (error) {
                    geolocateIcon.textContent = '❌';
                    geolocateText.textContent = 'Erreur de localisation';
                    geolocateBtn.disabled = false;
                }
            },
            function(error) {
                geolocateIcon.textContent = '❌';
                geolocateText.textContent = 'Localisation refusée';
                geolocateBtn.disabled = false;
            }
        );
    });
    
    // Soumission du formulaire étape 2 (enrichissement du profil)
    step2Form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const enrichBtn = document.getElementById('enrich-profile-btn');
        const enrichText = document.getElementById('enrich-text');
        const enrichLoading = document.getElementById('enrich-loading');
        const shareLocationCheckbox = document.getElementById('share_location');
        
        // Désactiver le bouton et afficher le loading
        enrichBtn.disabled = true;
        enrichText.classList.add('hidden');
        enrichLoading.classList.remove('hidden');
        
        try {
            const formData = new FormData(this);
            
            // Ajouter les coordonnées de géolocalisation si demandées
            if (shareLocationCheckbox.checked && 'geolocation' in navigator) {
                try {
                    const position = await new Promise((resolve, reject) => {
                        navigator.geolocation.getCurrentPosition(resolve, reject);
                    });
                    
                    formData.append('initial_latitude', position.coords.latitude);
                    formData.append('initial_longitude', position.coords.longitude);
                } catch (geoError) {
                    console.warn('Impossible d\'obtenir la géolocalisation:', geoError);
                }
            }
            
            const response = await fetch(this.action, {
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
});
</script>
@endsection