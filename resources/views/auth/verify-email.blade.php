@extends('layout')

@section('title', 'Vérification Email - Sekaijin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-blue-500 rounded-full flex items-center justify-center">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Vérifiez votre email
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Nous avons envoyé un lien de vérification à<br>
                <span class="font-medium text-blue-600">{{ auth()->user()->email }}</span>
            </p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            @if (session('message'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('message') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Étapes à suivre :
                    </h3>
                    <ol class="text-sm text-gray-600 text-left space-y-2">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                            Vérifiez votre boîte de réception
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                            Cliquez sur le lien de vérification dans l'email
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                            Votre compte sera activé automatiquement
                        </li>
                    </ol>
                </div>

                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-4">
                        Vous n'avez pas reçu l'email ? Vérifiez vos spams ou demandez un nouvel envoi.
                    </p>
                    
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Renvoyer l'email de vérification
                        </button>
                    </form>
                </div>

                <div class="border-t pt-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Fonctionnalités limitées
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Tant que votre email n'est pas vérifié, vous ne pouvez pas :</p>
                                    <ul class="list-disc list-inside mt-1 space-y-1">
                                        <li>Créer des articles ou événements</li>
                                        <li>Publier des annonces</li>
                                        <li>Rendre votre profil public</li>
                                        <li>Upload d'avatar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        ← Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection