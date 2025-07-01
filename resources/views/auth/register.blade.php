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