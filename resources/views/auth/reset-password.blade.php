@extends('layout')

@section('title', 'Réinitialiser le mot de passe - Sekaijin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <div class="text-4xl mb-4">🔑</div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Nouveau mot de passe</h1>
            <p class="text-gray-600">Choisissez un nouveau mot de passe sécurisé pour votre compte</p>
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

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            
            <!-- Token caché -->
            <input type="hidden" name="token" value="{{ $token }}">
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="votre@email.com">
            </div>

            <!-- Nouveau mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="••••••••">
                <p class="mt-1 text-sm text-gray-500">Au moins 8 caractères</p>
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="••••••••">
            </div>

            <!-- Protection anti-spam Turnstile -->
            <div class="mt-6">
                <x-turnstile 
                    data-action="reset-password"
                    data-callback="onResetPasswordTurnstileSuccess"
                    data-error-callback="onResetPasswordTurnstileError"
                />
            </div>

            <!-- Bouton de réinitialisation -->
            <div>
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition duration-300 shadow-lg">
                    Réinitialiser le mot de passe
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

            <!-- Lien vers connexion -->
            <div class="text-center">
                <p class="text-gray-600 mb-4">
                    Vous vous souvenez de votre mot de passe ?
                </p>
                <a href="{{ route('login') }}" 
                    class="w-full inline-block bg-white text-blue-600 py-3 px-6 rounded-lg font-semibold border-2 border-blue-600 hover:bg-blue-50 transition duration-300">
                    Se connecter
                </a>
            </div>
        </form>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Callbacks Turnstile pour réinitialisation du mot de passe
    window.onResetPasswordTurnstileSuccess = function(token) {
        console.log('Turnstile verification successful for reset password:', token);
        // Le token sera automatiquement inclus dans le formulaire
    };
    
    window.onResetPasswordTurnstileError = function(error) {
        console.error('Turnstile error for reset password:', error);
        alert('Erreur de vérification de sécurité. Veuillez recharger la page.');
    };
});
</script>
@endsection