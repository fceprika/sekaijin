@extends('layout')

@section('title', 'Mentions légales - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Mentions légales</h1>
                <p class="text-indigo-100">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>

            <div class="px-8 py-8 prose prose-lg max-w-none">
                <p class="text-gray-700 leading-relaxed">
                    Conformément aux dispositions des articles 6-III et 19 de la Loi n°2004-575 du 21 juin 2004 
                    pour la Confiance dans l'économie numérique (LCEN), il est porté à la connaissance des utilisateurs 
                    du site <strong class="text-blue-600">www.sekaijin.fr</strong> les présentes mentions légales.
                </p>

                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center">
                    <span class="mr-3">🏢</span>
                    1. Éditeur du site
                </h2>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">
                        Le site <strong class="text-blue-600">Sekaijin.fr</strong> est édité par la société :<br>
                        <strong>Sekaijin LLC</strong><br>
                        1021 E Lincolnway Suite 8081, Cheyenne, WY 82001<br>
                        États-Unis<br>
                        Email : <a href="mailto:contact@sekaijin.fr" class="text-blue-600 hover:text-blue-800 underline">contact@sekaijin.fr</a><br>
                    </p>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center">
                    <span class="mr-3">🌐</span>
                    2. Hébergeur
                </h2>
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">
                        Le site est hébergé par :<br>
                        <strong>Hostinger International Ltd.</strong><br>
                        61 Lordou Vironos Street, 6023 Larnaca, Chypre<br>
                        Site web : <a href="https://www.hostinger.com" target="_blank" rel="noopener" class="text-green-600 hover:text-green-800 underline">www.hostinger.com</a>
                    </p>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center">
                    <span class="mr-3">🎯</span>
                    3. Activité du site
                </h2>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">
                        <strong class="text-purple-600">Sekaijin.fr</strong> est une plateforme communautaire dédiée aux expatriés français, 
                        permettant la création de profils, la publication de contenus et le partage d'informations 
                        entre membres résidant à l'étranger.
                    </p>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center">
                    <span class="mr-3">©️</span>
                    4. Propriété intellectuelle
                </h2>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">
                        Le contenu du site (textes, images, logo, design) est la propriété exclusive de 
                        <strong class="text-orange-600">Sekaijin LLC</strong>, sauf mention contraire. 
                        Toute reproduction, distribution ou utilisation sans autorisation est strictement interdite.
                    </p>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center">
                    <span class="mr-3">🔒</span>
                    5. Données personnelles
                </h2>
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">
                        Les données collectées sont traitées conformément à la législation en vigueur. 
                        Pour en savoir plus, veuillez consulter notre 
                        <a href="{{ route('privacy') }}" class="text-red-600 hover:text-red-800 underline font-medium">
                            politique de confidentialité
                        </a>.
                    </p>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center">
                    <span class="mr-3">📞</span>
                    6. Contact
                </h2>
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">
                        Pour toute question ou demande, vous pouvez nous contacter à l'adresse suivante :<br>
                        <a href="mailto:contact@sekaijin.fr" class="text-indigo-600 hover:text-indigo-800 underline font-medium text-lg">
                            contact@sekaijin.fr
                        </a>
                    </p>
                </div>

                <!-- Message informatif -->
                <div class="mt-8 p-6 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg text-white">
                    <div class="flex items-center">
                        <span class="text-3xl mr-4">🌍</span>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Une communauté mondiale</h3>
                            <p class="text-blue-100">
                                Sekaijin connecte les expatriés français du monde entier dans le respect 
                                de la législation française et européenne.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Références légales -->
                <div class="mt-6 p-4 bg-gray-100 border border-gray-300 rounded-lg">
                    <h4 class="font-bold text-gray-700 mb-2">📋 Références légales :</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Loi n°2004-575 du 21 juin 2004 (LCEN)</li>
                        <li>• Règlement Général sur la Protection des Données (RGPD)</li>
                        <li>• Code civil français - Droit à l'image</li>
                        <li>• Loi Informatique et Libertés</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection