@extends('layout')

@section('title', 'Rejoignez notre communauté - Sekaijin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-500">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Contenu réservé aux membres
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Les profils des membres sont uniquement accessibles aux personnes inscrites
            </p>
        </div>

        <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
            <div class="space-y-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Pourquoi rejoindre Sekaijin ?
                    </h3>
                    
                    <div class="space-y-4 text-left">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">
                                    <strong>Accédez aux profils</strong> des membres et découvrez leurs parcours d'expatriation
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">
                                    <strong>Participez aux discussions</strong> et échangez avec d'autres expatriés français
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h3a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 010-2h3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">
                                    <strong>Accédez aux événements</strong> et aux actualités de votre pays de résidence
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">
                                    <strong>Restez informé</strong> des dernières actualités et conseils pratiques
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <div class="text-center space-y-4">
                        <a href="{{ route('register') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                            Rejoindre la communauté
                        </a>
                        
                        <div class="text-sm">
                            <span class="text-gray-500">Déjà membre ?</span>
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium ml-1">
                                Se connecter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-500">
                Une communauté de plus de 25 000 expatriés français dans le monde
            </p>
        </div>
    </div>
</div>
@endsection