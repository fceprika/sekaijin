@extends('layout')

@section('title', 'Mon profil - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <!-- En-tête du profil -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                <h1 class="text-3xl font-bold text-white">Mon Profil</h1>
                <p class="text-blue-100 mt-2">Gérez vos informations personnelles</p>
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

            <form method="POST" action="{{ route('profile.update') }}" class="p-8 space-y-8">
                @csrf

                <!-- Informations de base -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations de base</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pseudo -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Pseudo *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>
                    </div>
                </div>

                <!-- Informations personnelles -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations personnelles</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Prénom -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Nom -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Date de naissance -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="+33 1 23 45 67 89">
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Localisation</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pays de résidence -->
                        <div>
                            <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">Pays de résidence *</label>
                            <select id="country_residence" name="country_residence" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Sélectionnez un pays</option>
                                @include('partials.countries', ['selected' => old('country_residence', $user->country_residence)])
                            </select>
                        </div>

                        <!-- Ville de résidence -->
                        <div>
                            <label for="city_residence" class="block text-sm font-medium text-gray-700 mb-2">Ville de résidence</label>
                            <input type="text" id="city_residence" name="city_residence" value="{{ old('city_residence', $user->city_residence) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Paris, Londres, New York...">
                        </div>
                    </div>
                </div>

                <!-- Biographie -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">À propos de moi</h2>
                    
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Biographie</label>
                        <textarea id="bio" name="bio" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            placeholder="Parlez-nous de vous, votre parcours d'expatrié, vos passions...">{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 1000 caractères</p>
                    </div>
                </div>

                <!-- Changement de mot de passe -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Changer le mot de passe</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Mot de passe actuel -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                            <input type="password" id="new_password" name="new_password" minlength="8"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                        </div>

                        <!-- Confirmer le nouveau mot de passe -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-between items-center">
                    <a href="/" class="text-gray-600 hover:text-gray-800 font-medium">
                        ← Retour à l'accueil
                    </a>
                    
                    <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-8 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                        Mettre à jour le profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations du compte -->
        <div class="mt-8 bg-white shadow-lg rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations du compte</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                <div>
                    <span class="font-medium">Membre depuis :</span>
                    {{ $user->created_at->format('d/m/Y') }}
                </div>
                <div>
                    <span class="font-medium">Dernière connexion :</span>
                    {{ $user->last_login ? $user->last_login->format('d/m/Y à H:i') : 'Jamais' }}
                </div>
                <div>
                    <span class="font-medium">Statut :</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $user->is_verified ? 'Vérifié' : 'Non vérifié' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation côté client pour les mots de passe
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const currentPassword = document.getElementById('current_password');
    
    function validatePasswords() {
        if (newPassword.value && confirmPassword.value) {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
        
        // Si un nouveau mot de passe est saisi, le mot de passe actuel devient requis
        if (newPassword.value) {
            currentPassword.required = true;
        } else {
            currentPassword.required = false;
        }
    }
    
    newPassword.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
});
</script>
@endsection