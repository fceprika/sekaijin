@extends('layout')

@section('title', 'Erreur serveur - Sekaijin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h1 class="text-9xl font-bold text-red-300">500</h1>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Erreur serveur
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Une erreur inattendue s'est produite sur nos serveurs. Nous travaillons à résoudre le problème.
            </p>
        </div>
        
        <div class="mt-8 space-y-6">
            <div class="text-red-500">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    🏠 Retour à l'accueil
                </a>
                
                <button onclick="location.reload()" class="w-full py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    🔄 Actualiser la page
                </button>
            </div>
        </div>
        
        <div class="mt-8 text-xs text-gray-400">
            <p>Si le problème persiste, <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-500">contactez-nous</a>.</p>
        </div>
    </div>
</div>
@endsection