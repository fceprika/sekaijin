@extends('layout')

@section('title', 'Mot de passe oublié - Sekaijin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <div class="text-4xl mb-4">🔐</div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Mot de passe oublié ?</h1>
            <p class="text-gray-600">Pas de panique ! Entrez votre email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="votre@email.com">
            </div>

            <!-- Protection anti-spam Turnstile -->
            <div class="mt-6">
                <x-turnstile 
                    data-action="forgot-password"
                    data-callback="onForgotPasswordTurnstileSuccess"
                    data-error-callback="onForgotPasswordTurnstileError"
                />
            </div>

            <!-- Bouton d'envoi -->
            <div>
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                    Envoyer le lien de réinitialisation
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

            <!-- Liens vers connexion et inscription -->
            <div class="text-center space-y-4">
                <p class="text-gray-600">
                    Vous vous souvenez de votre mot de passe ?
                </p>
                <a href="{{ route('login') }}" 
                    class="w-full inline-block bg-white text-blue-600 py-3 px-6 rounded-lg font-semibold border-2 border-blue-600 hover:bg-blue-50 transition duration-300">
                    Se connecter
                </a>
                
                <p class="text-gray-600 text-sm">
                    Pas encore membre ? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 underline">Créer un compte</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus sur email
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.focus();
    }
    
    // Callbacks Turnstile pour mot de passe oublié
    window.onForgotPasswordTurnstileSuccess = function(token) {
        // Le token sera automatiquement inclus dans le formulaire
    };
    
    window.onForgotPasswordTurnstileError = function(error) {
        alert('Erreur de vérification de sécurité. Veuillez recharger la page.');
    };
});
</script>
@endsection