@extends('layout')

@section('title', 'À propos - Sekaijin')

@section('content')
<div class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">À propos de Sekaijin</h1>
            <p class="text-xl text-gray-600">Notre mission pour les expatriés français</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-2xl font-semibold mb-4">Notre Histoire</h2>
                <p class="text-gray-600 mb-4">
                    Sekaijin (世界人 - "citoyen du monde" en japonais) est né de l'expérience personnelle 
                    de ses fondateurs, expatriés français ayant vécu dans plusieurs pays.
                </p>
                <p class="text-gray-600 mb-4">
                    Conscients des défis uniques auxquels font face les Français à l'étranger, nous avons 
                    créé cette plateforme pour connecter, informer et accompagner notre communauté mondiale.
                </p>
                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition duration-300">
                    Rejoindre la communauté
                </button>
            </div>
            <div class="bg-blue-50 rounded-lg p-8 text-center">
                <h3 class="text-xl font-semibold mb-4">Nos Valeurs</h3>
                <ul class="space-y-3">
                    <li class="flex items-center justify-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        <span class="font-medium">Solidarité</span>
                    </li>
                    <li class="flex items-center justify-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        <span class="font-medium">Partage d'expérience</span>
                    </li>
                    <li class="flex items-center justify-center">
                        <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                        <span class="font-medium">Entraide</span>
                    </li>
                    <li class="flex items-center justify-center">
                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                        <span class="font-medium">Fierté française</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection