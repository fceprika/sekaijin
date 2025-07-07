@extends('layout')

@section('title', 'Inscription - Sekaijin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Rejoindre Sekaijin</h1>
            <p class="text-gray-600">Connectez-vous avec la communauté des expatriés français</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Pseudo -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Pseudo *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="Choisissez votre pseudo">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            </div>

            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Photo de profil (optionnel)</label>
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img id="avatar-preview" class="h-16 w-16 rounded-full object-cover border-2 border-gray-300" 
                             src="https://ui-avatars.com/api/?name={{ old('name', 'Avatar') }}&background=3B82F6&color=fff&size=64" 
                             alt="Aperçu avatar">
                    </div>
                    <div class="flex-1">
                        <div class="relative">
                            <input type="file" id="avatar" name="avatar" accept="image/*" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-blue-300 rounded-lg bg-blue-50 hover:bg-blue-100 hover:border-blue-400 transition duration-200 cursor-pointer">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-blue-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="text-sm font-medium text-blue-600">Choisir une image</p>
                                    <p class="text-xs text-gray-500">ou glisser-déposer ici</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG ou WebP. Maximum 100KB.</p>
                    </div>
                </div>
            </div>

            <!-- Partage de localisation -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">🗺️ Partage de localisation (optionnel)</h3>
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="share_location" name="share_location" type="checkbox" value="1"
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
                        <div id="location-setup-info" class="mt-2 text-xs text-gray-600" style="display: none;">
                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded">
                                ✓ Votre navigateur vous demandera l'autorisation après l'inscription
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Localisation (auto-remplie ou manuelle) -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">📍 Votre localisation</h3>
                
                <!-- Pays de résidence -->
                <div>
                    <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">Pays de résidence</label>
                    <select id="country_residence" name="country_residence"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        <option value="">Sélectionnez un pays (optionnel)</option>
                        @include('partials.countries', ['selected' => old('country_residence')])
                    </select>
                </div>

                <!-- Ville de résidence -->
                <div>
                    <label for="city_residence" class="block text-sm font-medium text-gray-700 mb-2">Ville de résidence</label>
                    <input type="text" id="city_residence" name="city_residence" value="{{ old('city_residence') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        placeholder="Votre ville (optionnel)">
                </div>

                <!-- Pays de destination (si résidence en France) -->
                <div id="destination-country-container" style="display: none;">
                    <label for="destination_country" class="block text-sm font-medium text-gray-700 mb-2">Pays de destination souhaité</label>
                    <select id="destination_country" name="destination_country"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        <option value="">Sélectionnez votre pays de destination</option>
                        @include('partials.countries', ['selected' => old('destination_country'), 'exclude' => 'France'])
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Où souhaitez-vous vous expatrier ?</p>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe *</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        minlength="12">
                    <p class="text-xs text-gray-500 mt-1">Minimum 12 caractères avec majuscule, minuscule et chiffre</p>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>
            </div>

            <!-- Conditions -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="terms" name="terms" type="checkbox" required
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="text-gray-700">
                        J'accepte les <a href="#" class="text-blue-600 hover:text-blue-800 underline">conditions d'utilisation</a> 
                        et la <a href="#" class="text-blue-600 hover:text-blue-800 underline">politique de confidentialité</a> *
                    </label>
                </div>
            </div>

            <!-- Bouton d'inscription -->
            <div>
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                    Créer mon compte Sekaijin
                </button>
            </div>

            <!-- Lien vers connexion -->
            <div class="text-center">
                <p class="text-gray-600">
                    Déjà membre ? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium underline">
                        Se connecter
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script src="/js/geolocation.js"></script>
<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Validation côté client
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    const countryResidence = document.getElementById('country_residence');
    const destinationContainer = document.getElementById('destination-country-container');
    const shareLocationCheckbox = document.getElementById('share_location');
    const locationSetupInfo = document.getElementById('location-setup-info');
    
    // Afficher/masquer le champ destination selon le pays de résidence
    countryResidence.addEventListener('change', function() {
        if (this.value === 'France') {
            destinationContainer.style.display = 'block';
        } else {
            destinationContainer.style.display = 'none';
            document.getElementById('destination_country').value = '';
        }
    });
    
    // Vérifier au chargement si France est déjà sélectionnée (old input)
    if (countryResidence.value === 'France') {
        destinationContainer.style.display = 'block';
    }
    
    // Gérer l'affichage des informations de localisation et auto-remplissage
    shareLocationCheckbox.addEventListener('change', function() {
        if (this.checked) {
            locationSetupInfo.style.display = 'block';
            // Tenter d'obtenir la géolocalisation pour auto-remplir les champs
            autoFillLocationFields();
        } else {
            locationSetupInfo.style.display = 'none';
            // Optionnel : vider les champs auto-remplis
        }
    });
    
    // Fonction pour auto-remplir les champs de localisation
    async function autoFillLocationFields() {
        if (!navigator.geolocation) {
            console.log('Géolocalisation non supportée');
            return;
        }
        
        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000
                });
            });
            
            const { latitude, longitude } = position.coords;
            
            // Utiliser l'API de géocodage inversé pour obtenir pays et ville
            const locationData = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=fr`)
                .then(response => response.json());
            
            // Auto-remplir le pays
            if (locationData.countryName) {
                const countrySelect = document.getElementById('country_residence');
                const countryOption = Array.from(countrySelect.options).find(option => 
                    option.text.toLowerCase().includes(locationData.countryName.toLowerCase()) ||
                    locationData.countryName.toLowerCase().includes(option.text.toLowerCase())
                );
                if (countryOption) {
                    countrySelect.value = countryOption.value;
                    // Déclencher l'événement change pour la logique France/destination
                    countrySelect.dispatchEvent(new Event('change'));
                }
            }
            
            // Auto-remplir la ville (privilégier les noms en caractères latins)
            if (locationData.city || locationData.locality) {
                const cityInput = document.getElementById('city_residence');
                let cityName = locationData.city || locationData.locality;
                
                // Si le nom contient des caractères non-latins, essayer d'utiliser un nom alternatif
                if (!/^[\w\s\-\.,'àáâäèéêëìíîïòóôöùúûüçñ]+$/i.test(cityName)) {
                    // Essayer d'utiliser le nom du district ou région si disponible
                    cityName = locationData.locality || locationData.principalSubdivision || cityName;
                }
                
                cityInput.value = cityName;
            }
            
            console.log('Localisation auto-remplie:', locationData);
            
        } catch (error) {
            console.log('Impossible d\'obtenir la localisation:', error.message);
        }
    }
    
    // Gérer la soumission du formulaire avec localisation
    form.addEventListener('submit', function(e) {
        if (shareLocationCheckbox.checked) {
            // Empêcher la soumission par défaut pour traiter la localisation d'abord
            e.preventDefault();
            
            // Vérifier la validité du formulaire avant de procéder
            if (password.value !== passwordConfirm.value) {
                alert('Les mots de passe ne correspondent pas');
                passwordConfirm.focus();
                return;
            }
            
            // Sauvegarder les données du formulaire
            const formData = new FormData(form);
            
            // Si géolocalisation supportée, l'obtenir maintenant
            if ('geolocation' in navigator) {
                // Désactiver le bouton de soumission
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Inscription en cours...';
                
                // Message de progression
                const progressDiv = document.createElement('div');
                progressDiv.className = 'mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-center';
                progressDiv.innerHTML = '<p class="text-blue-700">📍 Inscription en cours... Votre navigateur peut vous demander l\'autorisation de géolocalisation.</p>';
                form.appendChild(progressDiv);
                
                // Obtenir la position avec gestion d'erreurs améliorée
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        // Valider les coordonnées
                        if (position.coords.latitude < -90 || position.coords.latitude > 90 ||
                            position.coords.longitude < -180 || position.coords.longitude > 180) {
                            progressDiv.innerHTML = '<p class="text-orange-600">⚠️ Coordonnées invalides détectées. Inscription sans géolocalisation...</p>';
                            setTimeout(() => {
                                submitFormWithData(formData);
                            }, 1500);
                            return;
                        }
                        
                        // Ajouter les coordonnées aux données du formulaire
                        formData.append('initial_latitude', position.coords.latitude);
                        formData.append('initial_longitude', position.coords.longitude);
                        
                        progressDiv.innerHTML = '<p class="text-green-700">✅ Position obtenue! Finalisation de l\'inscription...</p>';
                        
                        // Soumettre le formulaire avec les données de localisation
                        submitFormWithData(formData);
                    },
                    function(error) {
                        let errorMessage = 'Impossible d\'obtenir votre position.';
                        
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Autorisation refusée pour la géolocalisation.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Position indisponible.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Délai d\'attente dépassé.';
                                break;
                        }
                        
                        progressDiv.innerHTML = `<p class="text-orange-600">⚠️ ${errorMessage} Inscription sans géolocalisation...</p>`;
                        
                        // Soumettre quand même le formulaire sans coordonnées
                        setTimeout(() => {
                            submitFormWithData(formData);
                        }, 1500);
                    },
                    {
                        enableHighAccuracy: false,
                        timeout: 10000,
                        maximumAge: 300000
                    }
                );
            } else {
                // Géolocalisation non supportée, continuer normalement
                form.submit();
            }
        }
    });
    
    // Fonction pour soumettre le formulaire avec les données
    function submitFormWithData(formData) {
        // Ajouter les données de géolocalisation aux champs cachés du formulaire
        for (let [key, value] of formData.entries()) {
            if (['initial_latitude', 'initial_longitude', 'initial_city'].includes(key)) {
                let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = key;
                hiddenInput.value = value;
                form.appendChild(hiddenInput);
            }
        }
        
        // Soumettre le formulaire normalement pour permettre la redirection Laravel
        form.submit();
    }

    // Validation du mot de passe en temps réel
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    
    function validatePassword() {
        const password = passwordInput.value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&\-_]+$/;
        
        if (password.length < 12) {
            passwordInput.setCustomValidity('Le mot de passe doit contenir au moins 12 caractères.');
        } else if (!regex.test(password)) {
            passwordInput.setCustomValidity('Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.');
        } else {
            passwordInput.setCustomValidity('');
        }
        
        // Vérifier la confirmation
        if (passwordConfirmInput.value && passwordConfirmInput.value !== password) {
            passwordConfirmInput.setCustomValidity('Les mots de passe ne correspondent pas.');
        } else {
            passwordConfirmInput.setCustomValidity('');
        }
    }
    
    passwordInput.addEventListener('input', validatePassword);
    passwordConfirmInput.addEventListener('input', validatePassword);
    
    // Gestion de l'aperçu de l'avatar
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    const nameInput = document.getElementById('name');
    
    // Mettre à jour l'aperçu par défaut quand le pseudo change
    nameInput.addEventListener('input', function() {
        if (!avatarInput.files || avatarInput.files.length === 0) {
            const name = this.value || 'Avatar';
            avatarPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=3B82F6&color=fff&size=64`;
        }
    });
    
    // Gestion interactive du upload d'avatar
    const avatarUploadDiv = avatarInput?.parentNode;
    
    if (avatarUploadDiv) {
        // Mettre à jour l'apparence quand un fichier est sélectionné
        avatarInput.addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            const textElement = avatarUploadDiv.querySelector('p.text-sm');
            
            if (fileName) {
                if (textElement) {
                    textElement.textContent = fileName;
                    textElement.classList.remove('text-blue-600');
                    textElement.classList.add('text-green-600');
                }
                // Changer l'apparence pour montrer qu'un fichier est sélectionné
                const uploadDiv = avatarUploadDiv.querySelector('div.border-dashed');
                if (uploadDiv) {
                    uploadDiv.classList.remove('border-blue-300', 'bg-blue-50');
                    uploadDiv.classList.add('border-green-300', 'bg-green-50');
                }
            } else {
                if (textElement) {
                    textElement.textContent = 'Choisir une image';
                    textElement.classList.remove('text-green-600');
                    textElement.classList.add('text-blue-600');
                }
                // Revenir à l'apparence normale
                const uploadDiv = avatarUploadDiv.querySelector('div.border-dashed');
                if (uploadDiv) {
                    uploadDiv.classList.remove('border-green-300', 'bg-green-50');
                    uploadDiv.classList.add('border-blue-300', 'bg-blue-50');
                }
            }
        });
    }
    
    // Prévisualiser l'image uploadée
    avatarInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Vérifier la taille du fichier (100KB max)
            if (file.size > 100 * 1024) {
                alert('Le fichier est trop volumineux. Maximum 100KB autorisé.');
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
        } else {
            // Revenir à l'avatar par défaut
            const name = nameInput.value || 'Avatar';
            avatarPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=3B82F6&color=fff&size=64`;
        }
    });
});
</script>
@endsection