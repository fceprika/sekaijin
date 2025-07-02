@extends('layout')

@section('title', 'Accueil - Sekaijin')

@section('content')
<!-- Hero Section with Gradient -->
<div class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
            Bienvenue sur 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-pink-400">
                Sekaijin
            </span>
        </h1>
        <p class="text-xl md:text-2xl mb-10 text-blue-100 max-w-3xl mx-auto">
            La communauté des expatriés français à travers le monde
        </p>
        <div class="space-x-4">
            <button id="hero-btn" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transform hover:scale-105 transition duration-300 shadow-lg">
                Rejoindre la communauté
            </button>
            <a href="/about" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-600 transition duration-300 inline-block">
                En savoir plus
            </a>
        </div>
    </div>
    <!-- Floating shapes -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-yellow-400 rounded-full opacity-20 animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-16 h-16 bg-pink-400 rounded-full opacity-20 animate-bounce"></div>
</div>

<!-- Interactive Map Section -->
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Notre Communauté dans le Monde</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Découvrez où se trouvent les membres de Sekaijin à travers le globe
            </p>
        </div>
        
        <!-- Map Container -->
        <div class="bg-gray-100 rounded-2xl p-2 md:p-4 shadow-lg">
            <div id="map" class="h-[250px] md:h-[400px] lg:h-[500px] w-full rounded-xl"></div>
        </div>
        
        <!-- Map Legend -->
        <div class="mt-6 text-center">
            <div class="inline-flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-6 bg-gray-50 px-4 md:px-6 py-3 rounded-lg">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-gray-600">Membres de la communauté</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs md:text-sm text-gray-500">Cliquez sur un point pour voir les détails</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Content Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Thaïlande Section -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <span class="text-3xl mr-3">🇹🇭</span>
                <h2 class="text-2xl font-bold text-gray-800">Thaïlande</h2>
            </div>
        
        <!-- Thaïlande - 2x2 Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <!-- Actualités (Top Left) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">📰 Actualités</h2>
                    <a href="/thailande/actualites" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                
                <div class="space-y-6">
                    @if($thailandNews->count() > 0)
                        @foreach($thailandNews as $index => $news)
                            <a href="{{ route('country.news.show', [$thailand->slug, $news->id]) }}" class="block">
                                <article class="{{ $index === 0 ? 'border border-orange-200 rounded-lg p-4 hover:border-orange-400 hover:shadow-md' : 'p-4 border-b border-gray-100 hover:bg-gray-50 hover:border-gray-200 rounded-lg' }} transition-all duration-200 cursor-pointer">
                                    @if($index === 0)
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">{{ $news->priority === 'high' ? 'Important' : 'Actualité' }}</span>
                                            <span class="text-sm text-gray-500">{{ $news->created_at->diffForHumans() }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">{{ $news->created_at->diffForHumans() }}</span>
                                    @endif
                                    <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $news->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($news->content, 150) }}</p>
                                    <span class="text-sm text-gray-500">Publié par {{ $news->author->name }}</span>
                                </article>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">📰</div>
                            <p class="text-gray-500">Aucune actualité pour l'instant</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Blog (Top Right) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">✍️ Blog</h2>
                    <a href="/thailande/blog" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                
                <div class="space-y-6">
                    @if($thailandArticles->count() > 0)
                        @foreach($thailandArticles as $index => $article)
                            @php
                                $categoryColors = [
                                    'voyage' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'hover' => 'hover:bg-blue-50 hover:border hover:border-blue-200'],
                                    'mode-de-vie' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'hover' => 'hover:bg-yellow-50 hover:border hover:border-yellow-200'],
                                    'culture' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'hover' => 'hover:bg-purple-50 hover:border hover:border-purple-200'],
                                    'gastronomie' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'hover' => 'hover:bg-orange-50 hover:border hover:border-orange-200'],
                                ];
                                $colors = $categoryColors[$article->category] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'hover' => 'hover:bg-gray-50 hover:border hover:border-gray-200'];
                            @endphp
                            <a href="{{ route('country.article.show', [$thailand->slug, $article->id]) }}" class="block">
                                <article class="{{ $index === 0 ? 'bg-gray-50 rounded-lg ' : '' }}p-4 {{ $colors['hover'] }} rounded-lg transition-all duration-200 cursor-pointer">
                                    <div class="flex items-center space-x-2 mb-3">
                                        <span class="{{ $colors['bg'] }} {{ $colors['text'] }} px-3 py-1 rounded-full text-sm font-medium">{{ ucfirst(str_replace('-', ' ', $article->category)) }}</span>
                                        <span class="text-sm text-gray-500">{{ $article->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $article->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($article->excerpt ?? $article->content, 150) }}</p>
                                    <span class="text-sm text-gray-500">Par {{ $article->author->name }}</span>
                                </article>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">✍️</div>
                            <p class="text-gray-500">Aucun article pour l'instant</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Communauté (Bottom Left) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">👥 Communauté</h2>
                    <a href="/thailande/communaute" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                
                <div class="space-y-6">
                    <div class="p-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">P</div>
                            <div>
                                <div class="font-medium text-gray-800">Pierre Dupont</div>
                                <span class="text-sm text-gray-500">Il y a 1 jour</span>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-3">Quelqu'un connaît un bon dentiste qui parle français à Bangkok?</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span>💬 7 commentaires</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 text-center">
                        <p class="text-gray-600">
                            <span class="font-semibold">25K+</span> membres dans le monde
                        </p>
                    </div>
                </div>
            </div>

            <!-- Événements (Bottom Right) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">📅 Événements</h2>
                    <a href="/thailande/evenements" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                
                @if($thailandEvents->count() > 0)
                    @php $event = $thailandEvents->first() @endphp
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-5 border border-green-100">
                        <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $event->title }}</h3>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center">
                                <span>📅 {{ $event->start_date->format('l j F Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span>🕒 {{ $event->start_date->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span>📍 @if($event->is_online) En ligne @else {{ $event->location }} @endif</span>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 120) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Par {{ $event->organizer->name }}</span>
                            <a href="{{ route('country.event.show', [$thailand->slug, $event->id]) }}" class="bg-green-600 text-white px-3 py-2 rounded font-medium hover:bg-green-700 transition duration-200">
                                Voir détails
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">📅</div>
                        <p class="text-gray-500">Aucun événement à venir</p>
                    </div>
                @endif
            </div>
        </div>
        </div>

        <!-- Japon Section -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <span class="text-3xl mr-3">🇯🇵</span>
                <h2 class="text-2xl font-bold text-gray-800">Japon</h2>
            </div>
        
        <!-- Japon - 2x2 Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Actualités (Top Left) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">📰 Actualités</h2>
                    <a href="/japon/actualites" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                
                <div class="space-y-6">
                    @if($japanNews->count() > 0)
                        @foreach($japanNews as $index => $news)
                            <a href="{{ route('country.news.show', [$japan->slug, $news->id]) }}" class="block">
                                <article class="{{ $index === 0 ? 'border border-orange-200 rounded-lg p-4 hover:border-orange-400 hover:shadow-md' : 'p-4 border-b border-gray-100 hover:bg-gray-50 hover:border-gray-200 rounded-lg' }} transition-all duration-200 cursor-pointer">
                                    @if($index === 0)
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">{{ $news->priority === 'high' ? 'Important' : 'Actualité' }}</span>
                                            <span class="text-sm text-gray-500">{{ $news->created_at->diffForHumans() }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">{{ $news->created_at->diffForHumans() }}</span>
                                    @endif
                                    <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $news->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($news->content, 150) }}</p>
                                    <span class="text-sm text-gray-500">Publié par {{ $news->author->name }}</span>
                                </article>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">📰</div>
                            <p class="text-gray-500">Aucune actualité pour l'instant</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Blog (Top Right) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">✍️ Blog</h2>
                    <a href="/japon/blog" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                
                <div class="space-y-6">
                    @if($japanArticles->count() > 0)
                        @foreach($japanArticles as $index => $article)
                            @php
                                $categoryColors = [
                                    'voyage' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'hover' => 'hover:bg-blue-50 hover:border hover:border-blue-200'],
                                    'mode-de-vie' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'hover' => 'hover:bg-yellow-50 hover:border hover:border-yellow-200'],
                                    'culture' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'hover' => 'hover:bg-purple-50 hover:border hover:border-purple-200'],
                                    'apprentissage' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'hover' => 'hover:bg-green-50 hover:border hover:border-green-200'],
                                    'gastronomie' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'hover' => 'hover:bg-orange-50 hover:border hover:border-orange-200'],
                                ];
                                $colors = $categoryColors[$article->category] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'hover' => 'hover:bg-gray-50 hover:border hover:border-gray-200'];
                            @endphp
                            <a href="{{ route('country.article.show', [$japan->slug, $article->id]) }}" class="block">
                                <article class="{{ $index === 0 ? 'bg-gray-50 rounded-lg ' : '' }}p-4 {{ $colors['hover'] }} rounded-lg transition-all duration-200 cursor-pointer">
                                    <div class="flex items-center space-x-2 mb-3">
                                        <span class="{{ $colors['bg'] }} {{ $colors['text'] }} px-3 py-1 rounded-full text-sm font-medium">{{ ucfirst(str_replace('-', ' ', $article->category)) }}</span>
                                        <span class="text-sm text-gray-500">{{ $article->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $article->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($article->excerpt ?? $article->content, 150) }}</p>
                                    <span class="text-sm text-gray-500">Par {{ $article->author->name }}</span>
                                </article>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">✍️</div>
                            <p class="text-gray-500">Aucun article pour l'instant</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Communauté (Bottom Left) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">👥 Communauté</h2>
                    <a href="/japon/communaute" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                
                <div class="space-y-6">
                    <div class="p-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white font-bold">A</div>
                            <div>
                                <div class="font-medium text-gray-800">Antoine Dubois</div>
                                <span class="text-sm text-gray-500">Il y a 3 jours</span>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-3">Quelqu'un connaît des cours de japonais intensifs à Tokyo pour débutants?</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span>💬 12 commentaires</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 text-center">
                        <p class="text-gray-600">
                            <span class="font-semibold">2,5K+</span> membres au Japon
                        </p>
                    </div>
                </div>
            </div>

            <!-- Événements (Bottom Right) -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">📅 Événements</h2>
                    <a href="/japon/evenements" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                
                @if($japanEvents->count() > 0)
                    @php $event = $japanEvents->first() @endphp
                    <div class="bg-gradient-to-r from-pink-50 to-red-50 rounded-lg p-5 border border-pink-100">
                        <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $event->title }}</h3>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center">
                                <span>📅 {{ $event->start_date->format('l j F Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span>🕒 {{ $event->start_date->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span>📍 @if($event->is_online) En ligne @else {{ $event->location }} @endif</span>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 120) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Par {{ $event->organizer->name }}</span>
                            <a href="{{ route('country.event.show', [$japan->slug, $event->id]) }}" class="bg-red-600 text-white px-3 py-2 rounded font-medium hover:bg-red-700 transition duration-200">
                                Voir détails
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">📅</div>
                        <p class="text-gray-500">Aucun événement à venir</p>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Pourquoi rejoindre Sekaijin ?</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Découvrez ce qui fait de Sekaijin la communauté incontournable des expatriés français
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center group">
                <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">Communauté Active</h3>
                <p class="text-gray-600 leading-relaxed">
                    Connectez-vous avec des milliers d'expatriés français partageant vos expériences.
                </p>
            </div>
            
            <div class="text-center group">
                <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">Conseils Pratiques</h3>
                <p class="text-gray-600 leading-relaxed">
                    Accédez à des guides et conseils pour faciliter votre vie d'expatrié.
                </p>
            </div>
            
            <div class="text-center group">
                <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">Réseau Mondial</h3>
                <p class="text-gray-600 leading-relaxed">
                    Présents dans plus de 150 pays, trouvez des compatriotes près de chez vous.
                </p>
            </div>
        </div>
    </div>

<!-- Stats Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold text-blue-600 mb-2">25K+</div>
                <div class="text-gray-600">Membres Actifs</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-green-600 mb-2">150</div>
                <div class="text-gray-600">Pays Couverts</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-purple-600 mb-2">24/7</div>
                <div class="text-gray-600">Entraide</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-indigo-600 mb-2">5 ans</div>
                <div class="text-gray-600">D'expérience</div>
            </div>
        </div>
    </div>
</div>

<!-- Country Coordinates Script -->
<script src="/js/country-coordinates.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero button functionality
    $('#hero-btn').click(function() {
        window.location.href = '/inscription';
    });
    
    // Initialize Mapbox
    mapboxgl.accessToken = '{{ config('services.mapbox.access_token') }}';
    
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/light-v11',
        center: [2.2137, 46.2276], // Centré sur la France
        zoom: 2,
        projection: 'globe',
        // Désactiver la collecte de données pour éviter les erreurs d'ad blocker
        collectResourceTiming: false,
        trackResize: true
    });
    
    // Ajouter les contrôles de navigation
    map.addControl(new mapboxgl.NavigationControl());
    
    // Charger les données des expatriés via AJAX
    map.on('load', function() {
        $.get('/api/expats-by-country')
            .done(function(data) {
                addExpatMarkersToMap(map, data);
            })
            .fail(function() {
                console.error('Erreur lors du chargement des données des expatriés');
            });
    });
});

function addExpatMarkersToMap(map, expatData) {
    expatData.forEach(function(expat) {
        const coordinates = getCountryCoordinates(expat.country);
        
        if (coordinates) {
            // Calculer la taille du marqueur basée sur le nombre d'expatriés
            const size = Math.min(Math.max(expat.count / 10 + 10, 15), 40);
            
            // Créer un élément HTML pour le marqueur
            const markerElement = document.createElement('div');
            markerElement.className = 'expat-marker';
            markerElement.style.width = size + 'px';
            markerElement.style.height = size + 'px';
            markerElement.style.borderRadius = '50%';
            markerElement.style.backgroundColor = '#3b82f6';
            markerElement.style.border = '3px solid white';
            markerElement.style.cursor = 'pointer';
            markerElement.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
            markerElement.style.display = 'flex';
            markerElement.style.alignItems = 'center';
            markerElement.style.justifyContent = 'center';
            markerElement.style.color = 'white';
            markerElement.style.fontSize = '12px';
            markerElement.style.fontWeight = 'bold';
            markerElement.style.transition = 'transform 0.2s';
            
            // Afficher le nombre si assez grand
            if (size > 25) {
                markerElement.textContent = expat.count;
            }
            
            // Effet hover
            markerElement.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.2)';
                this.style.zIndex = '1000';
            });
            
            markerElement.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.zIndex = 'auto';
            });
            
            // Créer le popup avec texte en français
            const popup = new mapboxgl.Popup({
                offset: 25,
                closeButton: false
            }).setHTML(`
                <div class="text-center p-2">
                    <h3 class="font-bold text-lg text-gray-800">${expat.country}</h3>
                    <p class="text-blue-600 font-semibold">${expat.count} membre${expat.count > 1 ? 's' : ''}</p>
                    <p class="text-xs text-gray-500 mt-1">de la communauté Sekaijin</p>
                </div>
            `);
            
            // Ajouter le marqueur à la carte
            new mapboxgl.Marker(markerElement)
                .setLngLat(coordinates)
                .setPopup(popup)
                .addTo(map);
        }
    });
    
}
</script>
@endsection