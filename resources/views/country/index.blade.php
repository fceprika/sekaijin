@extends('layout')

@section('title', 'Sekaijin ' . $countryModel->name_fr . ' - Communauté française')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                {{ $countryModel->emoji }}
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-pink-400">
                    {{ $countryModel->name_fr }}
                </span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-3xl mx-auto">
                {{ $countryModel->description }}
            </p>
            
            <!-- Country Switcher -->
            <div class="flex justify-center space-x-4 mb-8">
                @foreach($allCountries as $country)
                    <a href="{{ route('country.index', $country->slug) }}" 
                       class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition duration-300 {{ $country->slug === $countryModel->slug ? 'bg-white text-blue-600' : 'bg-white bg-opacity-20 text-white hover:bg-opacity-30' }}">
                        {{ $country->emoji }} {{ $country->name_fr }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Quick Navigation -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            <a href="{{ route('country.actualites', $countryModel->slug) }}" 
               class="bg-white rounded-xl p-6 text-center hover:shadow-lg transition duration-300 group">
                <div class="text-3xl mb-3">📰</div>
                <h3 class="font-bold text-gray-800 group-hover:text-blue-600">Actualités</h3>
                <p class="text-sm text-gray-600">Les dernières nouvelles</p>
            </a>
            
            <a href="{{ route('country.blog', $countryModel->slug) }}" 
               class="bg-white rounded-xl p-6 text-center hover:shadow-lg transition duration-300 group">
                <div class="text-3xl mb-3">✍️</div>
                <h3 class="font-bold text-gray-800 group-hover:text-blue-600">Blog</h3>
                <p class="text-sm text-gray-600">Articles de la communauté</p>
            </a>
            
            <a href="{{ route('country.communaute', $countryModel->slug) }}" 
               class="bg-white rounded-xl p-6 text-center hover:shadow-lg transition duration-300 group">
                <div class="text-3xl mb-3">👥</div>
                <h3 class="font-bold text-gray-800 group-hover:text-blue-600">Communauté</h3>
                <p class="text-sm text-gray-600">{{ $communityMembers->count() }} membres</p>
            </a>
            
            <a href="{{ route('country.evenements', $countryModel->slug) }}" 
               class="bg-white rounded-xl p-6 text-center hover:shadow-lg transition duration-300 group">
                <div class="text-3xl mb-3">📅</div>
                <h3 class="font-bold text-gray-800 group-hover:text-blue-600">Événements</h3>
                <p class="text-sm text-gray-600">Prochains rendez-vous</p>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Latest News Section -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            📰 <span class="ml-2">Actualités {{ $countryModel->name_fr }}</span>
                        </h2>
                        <a href="{{ route('country.actualites', $countryModel->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Voir tout →
                        </a>
                    </div>
                    
                    @if($featuredNews->count() > 0)
                        <div class="space-y-4">
                            @foreach($featuredNews->take(3) as $news)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <h3 class="font-semibold text-gray-800">{{ $news->title }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $news->excerpt }}</p>
                                    <span class="text-xs text-gray-500">{{ $news->published_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-3">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3v.01M17 16v.01"></path>
                                </svg>
                            </div>
                            <h4 class="text-gray-600 font-medium">Aucune actualité publiée</h4>
                            <p class="text-gray-500 text-sm">Les actualités pour {{ $countryModel->name_fr }} arriveront bientôt !</p>
                        </div>
                    @endif
                </div>

                <!-- Latest Blog Posts -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            ✍️ <span class="ml-2">Blog {{ $countryModel->name_fr }}</span>
                        </h2>
                        <a href="{{ route('country.blog', $countryModel->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Voir tout →
                        </a>
                    </div>
                    
                    @if($featuredArticles->count() > 0)
                        <div class="space-y-6">
                            @foreach($featuredArticles->take(3) as $article)
                                <article class="{{ !$loop->last ? 'border-b border-gray-200 pb-4' : '' }}">
                                    <h3 class="font-semibold text-gray-800 mb-2">{{ $article->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-2">{{ $article->excerpt }}</p>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <span>Par {{ $article->author->name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $article->published_at->diffForHumans() }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-3">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <h4 class="text-gray-600 font-medium">Aucun article publié</h4>
                            <p class="text-gray-500 text-sm">Les articles pour {{ $countryModel->name_fr }} arriveront bientôt !</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Community Members -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            👥 <span class="ml-2">Communauté</span>
                        </h3>
                        <a href="{{ route('country.communaute', $countryModel->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir tout →
                        </a>
                    </div>
                    
                    @if($communityMembers->count() > 0)
                        <div class="space-y-3">
                            @foreach($communityMembers->take(4) as $member)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ $member->getPublicProfileUrl() }}" 
                                           class="font-medium text-gray-800 hover:text-blue-600">
                                            {{ $member->name }}
                                        </a>
                                        @if($member->city_residence)
                                            <p class="text-xs text-gray-500">{{ $member->city_residence }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 text-center">
                                <span class="font-semibold">{{ $communityMembers->count() }}</span> 
                                membre{{ $communityMembers->count() > 1 ? 's' : '' }} en {{ $countryModel->name_fr }}
                            </p>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-gray-600 font-medium">Aucun membre pour l'instant</h4>
                            <p class="text-gray-500 text-sm">Soyez le premier à rejoindre !</p>
                        </div>
                    @endif
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            📅 <span class="ml-2">Événements</span>
                        </h3>
                        <a href="{{ route('country.evenements', $countryModel->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir tout →
                        </a>
                    </div>
                    
                    @if($upcomingEvents->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcomingEvents->take(3) as $event)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <h4 class="font-medium text-gray-800 text-sm">{{ $event->title }}</h4>
                                    <p class="text-xs text-gray-600">{{ $event->start_date->format('d/m/Y H:i') }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if($event->is_online)
                                            En ligne
                                        @else
                                            {{ $event->location ?? 'Lieu à confirmer' }}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0v4a2 2 0 002 2h2m-4-6v4a2 2 0 002 2h2m-4-6h4m0-4v4m0 0v4a2 2 0 002 2h2m-4-6h4"></path>
                                </svg>
                            </div>
                            <h4 class="text-gray-600 font-medium">Aucun événement à venir</h4>
                            <p class="text-gray-500 text-sm">Les événements arriveront bientôt !</p>
                        </div>
                    @endif
                </div>

                <!-- Call to Action -->
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isAmbassador())
                        <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl p-6 border border-purple-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Gestion {{ $countryModel->name_fr }}</h3>
                            <p class="text-gray-600 text-sm mb-4">Contribuez au contenu de cette page.</p>
                            <div class="space-y-2">
                                <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200 text-sm">
                                    Ajouter une actualité
                                </button>
                                <button class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-purple-700 transition duration-200 text-sm">
                                    Créer un événement
                                </button>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Rejoignez-nous</h3>
                        <p class="text-gray-600 text-sm mb-4">Connectez-vous avec la communauté française en {{ $countryModel->name_fr }}.</p>
                        <a href="/inscription" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            S'inscrire
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection