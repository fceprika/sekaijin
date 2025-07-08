@extends('layout')

@section('title', 'Page non trouvée - Sekaijin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h1 class="text-9xl font-bold text-gray-300">404</h1>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Page non trouvée
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
            </p>
        </div>
        
        <div class="mt-8 space-y-6">
            <div class="text-gray-500">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    🏠 Retour à l'accueil
                </a>
                
                <a href="{{ route('country.index', 'thailande') }}" class="w-full py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    🇹🇭 Thaïlande
                </a>
            </div>
        </div>
        
        <div class="mt-8 text-xs text-gray-400">
            <p>Si vous pensez qu'il s'agit d'une erreur, n'hésitez pas à <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-500">nous contacter</a>.</p>
        </div>
    </div>
</div>
@endsection