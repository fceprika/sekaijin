@extends('layout')

@section('title', 'Service temporairement indisponible - Sekaijin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h1 class="text-9xl font-bold text-yellow-300">503</h1>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Service indisponible
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Le service est temporairement indisponible pour maintenance. Veuillez réessayer dans quelques minutes.
            </p>
        </div>
        
        <div class="mt-8 space-y-6">
            <div class="text-yellow-500">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L5.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            
            <div class="flex flex-col space-y-4">
                <button onclick="location.reload()" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    🔄 Réessayer
                </button>
                
                <a href="{{ route('home') }}" class="w-full py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    🏠 Retour à l'accueil
                </a>
            </div>
        </div>
        
        <div class="mt-8 text-xs text-gray-400">
            <p>Maintenance en cours. Merci pour votre patience.</p>
        </div>
    </div>
</div>
@endsection