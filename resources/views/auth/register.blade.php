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
            
            <!-- Nom et Prénom -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            </div>

            <!-- Date de naissance -->
            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance *</label>
                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            </div>

            <!-- Téléphone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="+33 1 23 45 67 89">
            </div>

            <!-- Pays et Ville -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="country_residence" class="block text-sm font-medium text-gray-700 mb-2">Pays de résidence *</label>
                    <select id="country_residence" name="country_residence" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        <option value="">Sélectionnez un pays</option>
                        <option value="Canada" {{ old('country_residence') == 'Canada' ? 'selected' : '' }}>Canada</option>
                        <option value="États-Unis" {{ old('country_residence') == 'États-Unis' ? 'selected' : '' }}>États-Unis</option>
                        <option value="Royaume-Uni" {{ old('country_residence') == 'Royaume-Uni' ? 'selected' : '' }}>Royaume-Uni</option>
                        <option value="Allemagne" {{ old('country_residence') == 'Allemagne' ? 'selected' : '' }}>Allemagne</option>
                        <option value="Espagne" {{ old('country_residence') == 'Espagne' ? 'selected' : '' }}>Espagne</option>
                        <option value="Italie" {{ old('country_residence') == 'Italie' ? 'selected' : '' }}>Italie</option>
                        <option value="Japon" {{ old('country_residence') == 'Japon' ? 'selected' : '' }}>Japon</option>
                        <option value="Australie" {{ old('country_residence') == 'Australie' ? 'selected' : '' }}>Australie</option>
                        <option value="Singapour" {{ old('country_residence') == 'Singapour' ? 'selected' : '' }}>Singapour</option>
                        <option value="Suisse" {{ old('country_residence') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                        <option value="Autre" {{ old('country_residence') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div>
                    <label for="city_residence" class="block text-sm font-medium text-gray-700 mb-2">Ville de résidence</label>
                    <input type="text" id="city_residence" name="city_residence" value="{{ old('city_residence') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        placeholder="Paris, Londres, New York...">
                </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation côté client
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    
    form.addEventListener('submit', function(e) {
        if (password.value !== passwordConfirm.value) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas');
            passwordConfirm.focus();
        }
    });
});
</script>
@endsection