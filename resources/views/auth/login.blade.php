@extends('layout')

@section('title', 'Connexion - Sekaijin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <div class="text-4xl mb-4">🌍</div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Bon retour !</h1>
            <p class="text-gray-600">Connectez-vous à votre compte Sekaijin</p>
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

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="votre@email.com">
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="••••••••">
            </div>

            <!-- Se souvenir de moi -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Se souvenir de moi
                    </label>
                </div>
                <div class="text-sm">
                    <a href="#" class="text-blue-600 hover:text-blue-800 underline">
                        Mot de passe oublié ?
                    </a>
                </div>
            </div>

            <!-- Protection anti-spam Turnstile -->
            @turnstileEnabled
            <div class="mt-6">
                <x-turnstile 
                    data-action="login"
                    data-callback="onLoginTurnstileSuccess"
                    data-error-callback="onLoginTurnstileError"
                />
            </div>
            @endturnstileEnabled

            <!-- Bouton de connexion -->
            <div>
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                    Se connecter
                </button>
            </div>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">ou</span>
                </div>
            </div>

            <!-- Lien vers inscription -->
            <div class="text-center">
                <p class="text-gray-600 mb-4">
                    Pas encore membre de la communauté ?
                </p>
                <a href="{{ route('register') }}" 
                    class="w-full inline-block bg-white text-blue-600 py-3 px-6 rounded-lg font-semibold border-2 border-blue-600 hover:bg-blue-50 transition duration-300">
                    Créer un compte
                </a>
            </div>
        </form>

        <!-- Statistiques communauté -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-lg font-bold text-blue-600">25K+</div>
                    <div class="text-xs text-gray-500">Membres</div>
                </div>
                <div>
                    <div class="text-lg font-bold text-green-600">150</div>
                    <div class="text-xs text-gray-500">Pays</div>
                </div>
                <div>
                    <div class="text-lg font-bold text-purple-600">24/7</div>
                    <div class="text-xs text-gray-500">Entraide</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus sur email si vide
    const emailInput = document.getElementById('email');
    if (!emailInput.value) {
        emailInput.focus();
    }
    
    // Callbacks Turnstile pour connexion
    window.onLoginTurnstileSuccess = function(token) {
        console.log('Turnstile verification successful for login:', token);
        // Le token sera automatiquement inclus dans le formulaire
    };
    
    window.onLoginTurnstileError = function(error) {
        console.error('Turnstile error for login:', error);
        alert('Erreur de vérification de sécurité. Veuillez recharger la page.');
    };
});
</script>
@endsection