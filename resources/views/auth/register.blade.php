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

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
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

            <!-- Pays de résidence -->
            <div>
                <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">Pays de résidence *</label>
                <select id="country_residence" name="country_residence" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <option value="">Sélectionnez un pays</option>
                    @include('partials.countries', ['selected' => old('country_residence')])
                </select>
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

            <!-- Mot de passe -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe *</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        minlength="8">
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
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
<script>
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
    
    // Gérer l'affichage des informations de localisation
    shareLocationCheckbox.addEventListener('change', function() {
        if (this.checked) {
            locationSetupInfo.style.display = 'block';
        } else {
            locationSetupInfo.style.display = 'none';
        }
    });
    
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
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.text();
            }
        })
        .then(html => {
            if (html) {
                document.open();
                document.write(html);
                document.close();
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'inscription:', error);
            // En cas d'erreur, soumettre le formulaire normalement
            form.submit();
        });
    }
});
</script>
@endsection