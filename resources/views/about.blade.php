@extends('layout')

@section('title', 'À propos - Sekaijin')

@section('content')
<div class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">À propos de Sekaijin</h1>
            <p class="text-xl text-gray-600">Notre mission pour les expatriés français</p>
        </div>
        
        <!-- Nos débuts -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                    <span class="text-gray-500">Image - Nos débuts</span>
                </div>
                <div>
                    <h2 class="text-3xl font-semibold mb-6 text-gray-800">Nos débuts</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Je m'appelle Wecko, je suis un expatrié français installé à Pattaya, en Thaïlande. 
                        Depuis longtemps, j'avais ce rêve en tête : vivre en Asie, découvrir de nouvelles cultures, 
                        et bâtir une vie ailleurs. Mais une fois à l'étranger, on réalise souvent à quel point 
                        l'aventure peut aussi être synonyme de solitude.
                    </p>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        C'est dans ce contexte que l'idée de Sekaijin est née.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Je voulais créer un espace simple, chaleureux, où les Français expatriés peuvent se retrouver, 
                        s'entraider, partager leurs expériences et ne plus se sentir seuls. Un lieu où les liens se 
                        tissent au-delà des frontières.
                    </p>
                </div>
            </div>
        </div>

        <!-- Notre mission -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <h2 class="text-3xl font-semibold mb-6 text-gray-800">Notre mission</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Chez Sekaijin, notre mission est simple : rassembler les Français expatriés autour de 
                        valeurs de partage, d'ambition et d'ouverture.
                    </p>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Nous croyons qu'un départ à l'étranger ne devrait jamais signifier l'isolement. Au contraire, 
                        c'est une opportunité unique de grandir, de s'ouvrir au monde, et de créer des liens forts avec 
                        ceux qui vivent la même aventure.
                    </p>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Nous voulons bâtir une communauté dynamique, composée de Français aux quatre coins du monde, 
                        qui partagent l'envie de s'intégrer pleinement dans leur pays d'accueil, tout en restant 
                        connectés à leurs racines.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Informer, inspirer, et connecter : voilà notre engagement. Grâce à des actualités locales, 
                        des blogs personnels, des échanges sincères et bienveillants, Sekaijin devient ce point de 
                        repère qui manquait à beaucoup.
                    </p>
                </div>
                <div class="order-1 lg:order-2 bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                    <span class="text-gray-500">Image - Notre mission</span>
                </div>
            </div>
        </div>

        <!-- Notre communauté aujourd'hui -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                    <span class="text-gray-500">Image - Notre communauté</span>
                </div>
                <div>
                    <h2 class="text-3xl font-semibold mb-6 text-gray-800">Notre communauté aujourd'hui</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Ce qui n'était au départ qu'une idée dans un coin de ma tête est devenu une réalité : 
                        Sekaijin rassemble aujourd'hui des dizaines de Français installés en Thaïlande, au Japon 
                        et en Indonésie, avec un même objectif — ne plus être seuls à l'étranger.
                    </p>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Notre communauté évolue chaque jour. Des profils variés nous rejoignent : des entrepreneurs, 
                        des étudiants, des créatifs, des familles, des aventuriers… tous animés par cette envie de 
                        s'intégrer, de partager, et d'avancer ensemble.
                    </p>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Sur le site, certains racontent leur parcours, d'autres donnent des conseils, partagent des 
                        bons plans, ou simplement discutent. Des liens se créent, des projets naissent, des rencontres 
                        changent des vies.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Sekaijin grandit avec vous — et grâce à vous. À mesure que la communauté s'étend à d'autres 
                        pays, notre mission reste la même : vous permettre de vous sentir chez vous, partout dans le monde.
                    </p>
                </div>
            </div>
        </div>

        <!-- Nos valeurs - 4 cartes -->
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Nos valeurs</h2>
                <p class="text-gray-600">Les piliers qui guident notre communauté</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-blue-50 rounded-lg p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Communauté</h3>
                    <p class="text-gray-600">Créer des liens durables entre expatriés français du monde entier</p>
                </div>
                <div class="bg-green-50 rounded-lg p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Entraide</h3>
                    <p class="text-gray-600">S'entraider pour surmonter les défis de l'expatriation</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Innovation</h3>
                    <p class="text-gray-600">Développer des solutions créatives pour les expatriés</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Confiance</h3>
                    <p class="text-gray-600">Bâtir un environnement sûr et bienveillant pour tous</p>
                </div>
            </div>
        </div>

        <!-- Carte fondateur Wecko -->
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Notre fondateur</h2>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-8 shadow-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                        <div class="md:col-span-1 text-center">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <span class="text-gray-500 text-sm">Photo Wecko</span>
                            </div>
                            <h3 class="text-2xl font-semibold text-gray-800 mb-2">Wecko</h3>
                            <p class="text-blue-600 font-medium">Fondateur de Sekaijin</p>
                            <p class="text-gray-600 text-sm mt-2">Pattaya, Thaïlande</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-gray-700 mb-4 leading-relaxed">
                                Expatrié français passionné par l'Asie, Wecko a créé Sekaijin avec une vision simple : 
                                permettre aux Français du monde entier de ne plus se sentir seuls à l'étranger.
                            </p>
                            <p class="text-gray-700 mb-4 leading-relaxed">
                                Fort de son expérience personnelle d'expatriation en Thaïlande, il comprend les défis 
                                uniques auxquels font face les Français à l'étranger et s'engage à construire une 
                                communauté bienveillante et solidaire.
                            </p>
                            <p class="text-gray-700 leading-relaxed">
                                "Sekaijin, c'est plus qu'un site : c'est une communauté en construction, ouverte aux 
                                personnes ambitieuses, bienveillantes, et surtout désireuses de s'intégrer sincèrement 
                                dans les pays qu'elles choisissent d'appeler « chez elles »."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA final -->
        <div class="text-center">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Rejoignez notre communauté</h2>
            <p class="text-gray-600 mb-8">Sekaijin, c'est la famille que l'on retrouve, même loin de chez soi.</p>
            <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-300 text-lg font-medium">
                S'inscrire maintenant
            </button>
        </div>
    </div>
</div>
@endsection