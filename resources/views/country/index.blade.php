@extends('layout')

@section('title', 'Sekaijin ' . $countryModel->name_fr . ' - Communauté française')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    @if($countryModel->slug === 'thailande')
        <!-- Thailand Banner -->
        <div class="relative h-96 md:h-[500px] overflow-hidden">
            <img src="{{ asset('images/banners/thailand-banner.jpg') }}" 
                 alt="Bannière {{ $countryModel->name_fr }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            
            <!-- Country Switcher - Top Left -->
            <div class="absolute top-4 left-4 flex space-x-2">
                @foreach($allCountries as $country)
                    <a href="{{ route('country.index', $country->slug) }}" 
                       class="w-10 h-10 rounded-full flex items-center justify-center text-xl transition-all duration-300 
                              {{ $country->slug === $countryModel->slug 
                                 ? 'bg-white shadow-lg transform scale-110' 
                                 : 'bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm' }}"
                       title="{{ $country->name_fr }}">
                        {{ $country->emoji }}
                    </a>
                @endforeach
            </div>
            
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white max-w-4xl mx-auto px-4">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        {{ $countryModel->emoji }}
                        <span class="text-white drop-shadow-lg">
                            {{ $countryModel->name_fr }}
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-white drop-shadow max-w-3xl mx-auto">
                        {{ $countryModel->description }}
                    </p>
                </div>
            </div>
        </div>
    @elseif($countryModel->slug === 'japon')
        <!-- Japan Banner -->
        <div class="relative h-96 md:h-[500px] overflow-hidden">
            <img src="{{ asset('images/banners/banner_japan.jpg') }}" 
                 alt="Bannière {{ $countryModel->name_fr }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            
            <!-- Country Switcher - Top Left -->
            <div class="absolute top-4 left-4 flex space-x-2">
                @foreach($allCountries as $country)
                    <a href="{{ route('country.index', $country->slug) }}" 
                       class="w-10 h-10 rounded-full flex items-center justify-center text-xl transition-all duration-300 
                              {{ $country->slug === $countryModel->slug 
                                 ? 'bg-white shadow-lg transform scale-110' 
                                 : 'bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm' }}"
                       title="{{ $country->name_fr }}">
                        {{ $country->emoji }}
                    </a>
                @endforeach
            </div>
            
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white max-w-4xl mx-auto px-4">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        {{ $countryModel->emoji }}
                        <span class="text-white drop-shadow-lg">
                            {{ $countryModel->name_fr }}
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-white drop-shadow max-w-3xl mx-auto">
                        {{ $countryModel->description }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Default Gradient Banner for other countries -->
        <div class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-16 relative overflow-hidden">
            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            
            <!-- Country Switcher - Top Left -->
            <div class="absolute top-4 left-4 flex space-x-2 z-10">
                @foreach($allCountries as $country)
                    <a href="{{ route('country.index', $country->slug) }}" 
                       class="w-10 h-10 rounded-full flex items-center justify-center text-xl transition-all duration-300 
                              {{ $country->slug === $countryModel->slug 
                                 ? 'bg-white shadow-lg transform scale-110' 
                                 : 'bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm' }}"
                       title="{{ $country->name_fr }}">
                        {{ $country->emoji }}
                    </a>
                @endforeach
            </div>
            
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
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">

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
                    
                    @if($featuredNews->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($featuredNews->take(3) as $news)
                                <a href="{{ route('country.news.show', [$countryModel->slug, $news->slug]) }}" 
                                   class="block border-l-4 border-blue-500 pl-4 py-2 hover:bg-gray-50 transition duration-200 cursor-pointer">
                                    <h3 class="font-semibold text-gray-800 hover:text-blue-600">{{ $news->title }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $news->excerpt }}</p>
                                    <span class="text-xs text-gray-500">{{ $news->published_at?->diffForHumans() ?? 'Date inconnue' }}</span>
                                </a>
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
                    
                    @if($featuredArticles->isNotEmpty())
                        <div class="space-y-6">
                            @foreach($featuredArticles->take(3) as $article)
                                <a href="{{ route('country.article.show', [$countryModel->slug, $article->slug]) }}" 
                                   class="block {{ !$loop->last ? 'border-b border-gray-200 pb-4' : '' }} hover:bg-gray-50 transition duration-200 cursor-pointer p-2 -m-2 rounded">
                                    <h3 class="font-semibold text-gray-800 mb-2 hover:text-blue-600">{{ $article->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-2">{{ $article->excerpt }}</p>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <span>Par {{ $article->author->name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $article->published_at?->diffForHumans() ?? 'Date inconnue' }}</span>
                                    </div>
                                </a>
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
                    
                    @if($communityMembers->isNotEmpty())
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
                        
                        @php $memberCount = $communityMembers->count() @endphp
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 text-center">
                                <span class="font-semibold">{{ $memberCount }}</span> 
                                membre{{ $memberCount > 1 ? 's' : '' }} en {{ $countryModel->name_fr }}
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
                    
                    @if($upcomingEvents->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($upcomingEvents->take(3) as $event)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <h4 class="font-medium text-gray-800 text-sm">{{ $event->title }}</h4>
                                    <p class="text-xs text-gray-600">{{ $event->start_date?->format('d/m/Y H:i') ?? 'Date à confirmer' }}</p>
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

                <!-- Latest Announcements -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            📝 <span class="ml-2">Annonces</span>
                        </h3>
                        <a href="{{ route('country.annonces', $countryModel->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir tout →
                        </a>
                    </div>
                    
                    @if($latestAnnouncements->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($latestAnnouncements as $announcement)
                                <a href="{{ route('country.announcement.show', [$countryModel->slug, $announcement->slug]) }}" 
                                   class="block border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800 text-sm hover:text-blue-600">{{ $announcement->title }}</h4>
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($announcement->description, 80) }}</p>
                                            <div class="flex items-center mt-2 space-x-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    @if($announcement->type === 'vente') bg-green-100 text-green-800
                                                    @elseif($announcement->type === 'location') bg-blue-100 text-blue-800
                                                    @elseif($announcement->type === 'colocation') bg-purple-100 text-purple-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    @if($announcement->type === 'vente') Vente
                                                    @elseif($announcement->type === 'location') Location
                                                    @elseif($announcement->type === 'colocation') Colocation
                                                    @else Service @endif
                                                </span>
                                                @if($announcement->price)
                                                    <span class="text-xs font-semibold text-gray-700">{{ number_format($announcement->price, 0, ',', ' ') }} {{ $announcement->currency }}</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">{{ $announcement->city }} • {{ $announcement->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-gray-600 font-medium">Aucune annonce</h4>
                            <p class="text-gray-500 text-sm">Soyez le premier à publier !</p>
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
                                <a href="{{ route('admin.news.create') }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200 text-sm inline-block text-center">
                                    Ajouter une actualité
                                </a>
                                <a href="{{ route('events.create') }}" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-purple-700 transition duration-200 text-sm inline-block text-center">
                                    Créer un événement
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Rejoignez-nous</h3>
                        <p class="text-gray-600 text-sm mb-4">Connectez-vous avec la communauté française en {{ $countryModel->name_fr }}.</p>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200">
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