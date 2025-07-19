<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $seoData = $seoData ?? app(\App\Services\SeoService::class)->generateSeoData('home');
    @endphp
    
    <!-- Title -->
    <title>{{ $seoData['title'] }}</title>
    
    <!-- Basic Meta Tags -->
    <meta name="description" content="{{ $seoData['description'] }}">
    <meta name="keywords" content="{{ $seoData['keywords'] ?? '' }}">
    <meta name="author" content="Sekaijin">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $seoData['canonical'] }}">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ $seoData['title'] }}">
    <meta property="og:description" content="{{ $seoData['description'] }}">
    <meta property="og:url" content="{{ $seoData['canonical'] }}">
    <meta property="og:type" content="{{ $seoData['type'] ?? 'website' }}">
    <meta property="og:image" content="{{ $seoData['image'] ?? asset('/images/sekaijin_logo.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ $seoData['locale'] ?? 'fr_FR' }}">
    <meta property="og:site_name" content="{{ $seoData['site_name'] ?? 'Sekaijin' }}">
    
    @if(isset($seoData['article']))
        <meta property="article:published_time" content="{{ $seoData['article']['published_time'] }}">
        <meta property="article:modified_time" content="{{ $seoData['article']['modified_time'] }}">
        <meta property="article:author" content="{{ $seoData['article']['author'] }}">
        <meta property="article:section" content="{{ $seoData['article']['section'] }}">
        @foreach($seoData['article']['tag'] as $tag)
            <meta property="article:tag" content="{{ $tag }}">
        @endforeach
    @endif
    
    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoData['title'] }}">
    <meta name="twitter:description" content="{{ $seoData['description'] }}">
    <meta name="twitter:image" content="{{ $seoData['image'] ?? asset('/images/sekaijin_logo.png') }}">
    <meta name="twitter:site" content="@sekaijin">
    <meta name="twitter:creator" content="@sekaijin">
    
    <!-- Structured Data -->
    @if(isset($structuredData))
        <script type="application/ld+json" nonce="{{ $csp_nonce ?? '' }}">
            {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
    
    @if(config('services.google_analytics.id') && app()->environment('production'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.id') }}" nonce="{{ $csp_nonce ?? '' }}"></script>
    <script nonce="{{ $csp_nonce ?? '' }}">
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ config('services.google_analytics.id') }}');
    </script>
    @endif
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    
    <!-- Mapbox CSS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css" rel="stylesheet">
    
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Share Component CSS -->
    <link rel="stylesheet" href="/css/share-component.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Mapbox JS -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js" nonce="{{ $csp_nonce ?? '' }}"></script>
</head>
<body class="bg-gray-100">
    @auth
        @if(!auth()->user()->hasVerifiedEmail())
            <div class="bg-amber-500 text-white relative" id="email-verification-banner">
                <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between flex-wrap">
                        <div class="w-0 flex-1 flex items-center">
                            <span class="flex p-2 rounded-lg bg-amber-600">
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <p class="ml-3 font-medium">
                                <span class="md:hidden">
                                    Vérifiez votre email pour accéder à toutes les fonctionnalités
                                </span>
                                <span class="hidden md:inline">
                                    Votre email n'est pas encore vérifié. Vérifiez-le pour créer du contenu, publier des annonces et rendre votre profil public.
                                </span>
                            </p>
                        </div>
                        <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
                            <a href="{{ route('verification.notice') }}" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-amber-600 bg-white hover:bg-amber-50 transition-colors duration-200">
                                Vérifier maintenant
                            </a>
                        </div>
                        <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-3">
                            <button type="button" class="-mr-1 flex p-2 rounded-md hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2" onclick="document.getElementById('email-verification-banner').style.display='none'">
                                <span class="sr-only">Masquer</span>
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <nav class="bg-white shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex space-x-7">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center py-4 px-2">
                            <img src="/images/sekaijin_logo.png" alt="Sekaijin" class="h-8 w-auto">
                        </a>
                        
                        <!-- Unified Country Selector -->
                        <div class="relative ml-2 md:ml-4">
                            <button id="countries-dropdown-btn" class="relative flex items-center space-x-1 md:space-x-2 py-1.5 md:py-2 px-2 md:px-4 rounded-lg transition-all duration-300 text-sm md:text-base {{ isset($currentCountry) ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-md' : 'bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 shadow-lg animate-pulse' }}">
                                @if(isset($currentCountry))
                                    <span class="text-base md:text-lg">{{ $currentCountry->emoji }}</span>
                                    <span class="font-medium hidden sm:inline">{{ $currentCountry->name_fr }}</span>
                                @else
                                    <span class="text-base md:text-lg">🌍</span>
                                    <span class="font-medium hidden sm:inline">Choisir un pays</span>
                                    <span class="font-medium sm:hidden">Pays</span>
                                @endif
                                <svg class="w-3 h-3 md:w-4 md:h-4 transition-transform duration-200 ml-0.5 md:ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                @if(!isset($currentCountry))
                                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5 md:h-3 md:w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 md:h-3 md:w-3 bg-purple-500"></span>
                                    </span>
                                @endif
                            </button>
                        
                            <!-- Dropdown Menu -->
                            <div id="countries-dropdown" class="absolute left-0 mt-2 w-48 md:w-64 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible transform scale-95 transition-all duration-200 ease-out z-50">
                                <div class="p-3">
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold px-2 md:px-3">Sélectionnez votre destination</p>
                                    </div>
                                    <div class="space-y-1">
                                        @foreach($allCountries as $country)
                                            <a href="{{ route('country.index', $country->slug) }}" 
                                               class="flex items-center justify-between px-2 md:px-3 py-2 rounded-lg transition duration-200 {{ isset($currentCountry) && $currentCountry->slug === $country->slug ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                                <div class="flex items-center">
                                                    <span class="text-lg md:text-xl mr-2 md:mr-3">{{ $country->emoji }}</span>
                                                    <div>
                                                        <span class="font-medium text-sm md:text-base">{{ $country->name_fr }}</span>
                                                    </div>
                                                </div>
                                                @if(isset($currentCountry) && $currentCountry->slug === $country->slug)
                                                    <span class="text-blue-600">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">

                    <!-- Main Navigation Links -->
                    @if(isset($currentCountry))
                        <a href="{{ route('country.actualites', $currentCountry->slug) }}" 
                           class="py-4 px-3 text-gray-700 hover:text-blue-600 transition duration-300 font-medium {{ request()->routeIs('country.actualites') ? 'text-blue-600' : '' }}">
                            Actualités
                        </a>
                        <a href="{{ route('country.blog', $currentCountry->slug) }}" 
                           class="py-4 px-3 text-gray-700 hover:text-blue-600 transition duration-300 font-medium {{ request()->routeIs('country.blog') ? 'text-blue-600' : '' }}">
                            Blog
                        </a>
                        <a href="{{ route('country.communaute', $currentCountry->slug) }}" 
                           class="py-4 px-3 text-gray-700 hover:text-blue-600 transition duration-300 font-medium {{ request()->routeIs('country.communaute') ? 'text-blue-600' : '' }}">
                            Communauté
                        </a>
                        <a href="{{ route('country.evenements', $currentCountry->slug) }}" 
                           class="py-4 px-3 text-gray-700 hover:text-blue-600 transition duration-300 font-medium {{ request()->routeIs('country.evenements') ? 'text-blue-600' : '' }}">
                            Événements
                        </a>
                        <a href="{{ route('country.annonces', $currentCountry->slug) }}" 
                           class="py-4 px-3 text-gray-700 hover:text-blue-600 transition duration-300 font-medium {{ request()->routeIs('country.annonces') || request()->routeIs('country.announcement.*') || request()->routeIs('country.announcements.*') ? 'text-blue-600' : '' }}">
                            Annonces
                        </a>
                    @endif
                    
                    @if(!isset($currentCountry))
                        <a href="/about" class="py-4 px-3 text-gray-700 hover:text-blue-600 transition duration-300 font-medium">
                            À propos
                        </a>
                        <a href="/contact" class="py-4 px-3 text-gray-700 hover:text-blue-600 transition duration-300 font-medium">
                            Contact
                        </a>
                    @endif

                    <!-- User Menu or Auth Links -->
                    @auth
                        <div class="relative ml-4">
                            <!-- User Menu Button -->
                            <button id="user-menu-btn" class="flex items-center space-x-3 py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-300" dusk="user-menu">
                                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-blue-600">
                                    <img src="{{ Auth::user()->getAvatarUrl() }}" 
                                         alt="Avatar de {{ Auth::user()->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div id="user-menu" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transform scale-95 transition-all duration-200 ease-out z-50">
                                <div class="py-2">
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 hover:text-red-700 transition duration-200">
                                            <i class="fas fa-shield-alt mr-3 w-4"></i>
                                            Administration
                                        </a>
                                        <hr class="my-2">
                                    @endif
                                    <a href="{{ Auth::user()->getPublicProfileUrl() }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        <i class="fas fa-eye mr-3 w-4"></i>
                                        Voir mon profil
                                    </a>
                                    <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        <i class="fas fa-edit mr-3 w-4"></i>
                                        Éditer mes infos
                                    </a>
                                    <a href="{{ route('articles.my-articles') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        <i class="fas fa-file-alt mr-3 w-4"></i>
                                        Mes articles
                                    </a>
                                    <a href="{{ route('announcements.my') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        <i class="fas fa-tags mr-3 w-4"></i>
                                        Mes annonces
                                    </a>
                                    <a href="{{ route('favorites.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        <i class="fas fa-bookmark mr-3 w-4"></i>
                                        Mes favoris
                                    </a>
                                    <hr class="my-2">
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-200 text-left">
                                            <i class="fas fa-sign-out-alt mr-3 w-4"></i>
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-3 ml-6">
                            <a href="{{ route('login') }}" class="py-2 px-4 text-blue-600 hover:text-blue-800 transition duration-300 font-medium">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-medium shadow-sm">
                                Inscription
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        <!-- Mobile sidebar menu -->
        <div id="mobile-menu" class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
            <div class="flex flex-col h-full">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <img src="/images/sekaijin_logo.png" alt="Sekaijin" class="h-6 w-auto">
                    <button id="close-mobile-menu" class="text-gray-500 hover:text-blue-600 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Menu content -->
                <div class="flex-1 overflow-y-auto">
                    <!-- User section -->
                    @auth
                        <div class="p-4 border-b border-gray-200 bg-blue-50">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-blue-600">
                                    <img src="{{ Auth::user()->getAvatarUrl() }}" 
                                         alt="Avatar de {{ Auth::user()->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ Auth::user()->name }}
                                        @if(Auth::user()->isAdmin())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200 ml-2">
                                                <i class="fas fa-shield-alt mr-1"></i>
                                                Admin
                                            </span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">{{ Auth::user()->getDisplayLocation() }}</p>
                                </div>
                            </div>
                            <div class="mt-3 flex space-x-2">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="text-red-600 text-sm hover:text-red-800">Administration</a>
                                @endif
                                <a href="{{ Auth::user()->getPublicProfileUrl() }}" class="text-blue-600 text-sm hover:text-blue-800">Voir profil</a>
                                <a href="{{ route('profile.show') }}" class="text-blue-600 text-sm hover:text-blue-800">Modifier</a>
                            </div>
                        </div>
                    @endauth

                    <!-- Country selection prompt -->
                    @if(!isset($currentCountry))
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-2">🌍 Sélectionnez votre destination</p>
                            <p class="text-xs text-gray-600">Choisissez un pays pour accéder au contenu spécifique</p>
                        </div>
                    @endif

                    <!-- Country navigation -->
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">
                            @if(isset($currentCountry))
                                Changer de pays
                            @else
                                Pays disponibles
                            @endif
                        </h3>
                        @foreach($allCountries as $country)
                            <a href="{{ route('country.index', $country->slug) }}" 
                               class="flex items-center py-3 px-2 rounded-lg transition duration-200 {{ isset($currentCountry) && $currentCountry->slug === $country->slug ? 'bg-blue-100 text-blue-600 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                                <span class="text-xl mr-3">{{ $country->emoji }}</span>
                                <span>{{ $country->name_fr }}</span>
                                @if(isset($currentCountry) && $currentCountry->slug === $country->slug)
                                    <span class="ml-auto text-blue-600">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <!-- Country-specific sections -->
                    @if(isset($currentCountry))
                        <div class="p-4 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ $currentCountry->name_fr }}</h3>
                            <a href="{{ route('country.actualites', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.actualites') ? 'bg-blue-100 text-blue-600' : '' }}">
                                Actualités
                            </a>
                            <a href="{{ route('country.blog', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.blog') ? 'bg-blue-100 text-blue-600' : '' }}">
                                Blog
                            </a>
                            <a href="{{ route('country.communaute', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.communaute') ? 'bg-blue-100 text-blue-600' : '' }}">
                                Communauté
                            </a>
                            <a href="{{ route('country.evenements', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.evenements') ? 'bg-blue-100 text-blue-600' : '' }}">
                                Événements
                            </a>
                            <a href="{{ route('country.annonces', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.annonces') || request()->routeIs('country.announcement.*') || request()->routeIs('country.announcements.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                                Annonces
                            </a>
                        </div>
                    @endif

                    <!-- Global sections -->
                    <div class="p-4 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Global</h3>
                    @if(!isset($currentCountry))
                        <a href="/about" class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200">
                            ℹ️ À propos
                        </a>
                        <a href="/contact" class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200">
                            ✉️ Contact
                        </a>
                    @endif
                    </div>

                </div>

                <!-- Footer actions -->
                <div class="p-4 border-t border-gray-200">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition duration-300">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <div class="space-y-2">
                            <a href="{{ route('login') }}" class="block text-center bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition duration-300">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                                Inscription
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <script nonce="{{ $csp_nonce ?? '' }}">
        // Navigation functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu functionality
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const closeMobileMenuBtn = document.getElementById('close-mobile-menu');

            function openMobileMenu() {
                mobileMenuOverlay.classList.remove('hidden');
                mobileMenu.classList.remove('translate-x-full');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                mobileMenu.classList.add('translate-x-full');
                mobileMenuOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', openMobileMenu);
            }
            if (closeMobileMenuBtn) {
                closeMobileMenuBtn.addEventListener('click', closeMobileMenu);
            }
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', closeMobileMenu);
            }

            // Close mobile menu when clicking on navigation links
            if (mobileMenu) {
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', closeMobileMenu);
                });
            }

            // Countries Dropdown functionality
            const countriesDropdownBtn = document.getElementById('countries-dropdown-btn');
            const countriesDropdown = document.getElementById('countries-dropdown');

            function toggleCountriesDropdown() {
                const isVisible = !countriesDropdown.classList.contains('opacity-0');
                
                if (isVisible) {
                    // Close dropdown
                    countriesDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                    countriesDropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                    countriesDropdownBtn.querySelector('svg').classList.remove('rotate-180');
                } else {
                    // Close other dropdowns first
                    closeAllDropdowns();
                    // Open dropdown
                    countriesDropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                    countriesDropdown.classList.add('opacity-100', 'visible', 'scale-100');
                    countriesDropdownBtn.querySelector('svg').classList.add('rotate-180');
                }
            }

            // User Menu Dropdown functionality
            const userMenuBtn = document.getElementById('user-menu-btn');
            const userMenu = document.getElementById('user-menu');

            function toggleUserMenu() {
                const isVisible = userMenu && !userMenu.classList.contains('opacity-0');
                
                if (isVisible) {
                    // Close dropdown
                    userMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                    userMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                    userMenuBtn.querySelector('svg').classList.remove('rotate-180');
                } else {
                    // Close other dropdowns first
                    closeAllDropdowns();
                    // Open dropdown
                    userMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
                    userMenu.classList.add('opacity-100', 'visible', 'scale-100');
                    userMenuBtn.querySelector('svg').classList.add('rotate-180');
                }
            }

            // Close all dropdowns
            function closeAllDropdowns() {
                if (countriesDropdown) {
                    countriesDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                    countriesDropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                    countriesDropdownBtn.querySelector('svg').classList.remove('rotate-180');
                }
                
                if (userMenu) {
                    userMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                    userMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                    userMenuBtn.querySelector('svg').classList.remove('rotate-180');
                }
            }

            // Event listeners for dropdown buttons
            if (countriesDropdownBtn) {
                countriesDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleCountriesDropdown();
                });
            }

            if (userMenuBtn) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleUserMenu();
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!countriesDropdownBtn?.contains(e.target) && !countriesDropdown?.contains(e.target) &&
                    !userMenuBtn?.contains(e.target) && !userMenu?.contains(e.target)) {
                    closeAllDropdowns();
                }
            });

            // Close dropdowns on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAllDropdowns();
                }
            });

            // Prevent dropdown links from closing the dropdown immediately
            if (countriesDropdown) {
                countriesDropdown.addEventListener('click', function(e) {
                    // Allow navigation but add small delay for visual feedback
                    if (e.target.tagName === 'A') {
                        setTimeout(closeAllDropdowns, 100);
                    }
                });
            }

            // Sticky navigation effect
            const nav = document.querySelector('nav');
            let lastScrollTop = 0;

            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 100) {
                    // Add enhanced shadow and slightly compact navigation when scrolled
                    nav.classList.add('shadow-xl');
                    nav.classList.remove('shadow-lg');
                } else {
                    // Reset to original shadow
                    nav.classList.add('shadow-lg');
                    nav.classList.remove('shadow-xl');
                }
                
                lastScrollTop = scrollTop;
            });
        });
    </script>

    <main>
        @if (session('success'))
            <div class="bg-green-500 text-white text-center py-3">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="bg-red-500 text-white text-center py-3">
                {{ session('error') }}
            </div>
        @endif
        
        @if (session('info'))
            <div class="bg-blue-500 text-white text-center py-3">
                {{ session('info') }}
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- À propos de Sekaijin -->
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="/images/sekaijin_logo.png" alt="Sekaijin" class="h-10 w-auto brightness-0 invert">
                    </div>
                    <p class="text-gray-300 mb-4 leading-relaxed">
                        La communauté qui connecte les expatriés français du monde entier. 
                        Partagez vos expériences, découvrez de nouveaux horizons et créez des liens durables avec vos compatriotes.
                    </p>
                    <div class="flex items-center space-x-6 text-sm">
                        <div class="flex items-center">
                            <span class="text-green-400 mr-2">👥</span>
                            <span>{{ $totalMembers ?? '25K+' }} membres</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-blue-400 mr-2">🌏</span>
                            <span>{{ $allCountries->count() }} pays</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation rapide -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 flex items-center">
                        <span class="mr-2">🚀</span>
                        Navigation
                    </h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="/" class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                <span class="mr-2">🏠</span>Accueil
                            </a>
                        </li>
                        <li>
                            <a href="/about" class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                <span class="mr-2">ℹ️</span>À propos
                            </a>
                        </li>
                        <li>
                            <a href="/contact" class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                <span class="mr-2">✉️</span>Contact
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terms') }}" class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                <span class="mr-2">📋</span>Conditions d'utilisation
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('privacy') }}" class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                <span class="mr-2">🔒</span>Confidentialité
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal') }}" class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                <span class="mr-2">⚖️</span>Mentions légales
                            </a>
                        </li>
                        @guest
                            <li>
                                <a href="{{ route('register') }}" class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                    <span class="mr-2">📝</span>Inscription
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('profile.show') }}" class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                    <span class="mr-2">👤</span>Mon profil
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>

                <!-- Destinations populaires -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 flex items-center">
                        <span class="mr-2">✈️</span>
                        Destinations
                    </h4>
                    <ul class="space-y-2">
                        @foreach($allCountries->take(4) as $country)
                            <li>
                                <a href="{{ route('country.index', $country->slug) }}" 
                                   class="text-gray-300 hover:text-yellow-400 transition duration-300 flex items-center">
                                    <span class="mr-2">{{ $country->emoji }}</span>
                                    {{ $country->name_fr }}
                                </a>
                            </li>
                        @endforeach
                        @if($allCountries->count() > 4)
                            <li>
                                <span class="text-gray-400 text-sm flex items-center">
                                    <span class="mr-2">🌟</span>
                                    Et {{ $allCountries->count() - 4 }} autres pays...
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Separator -->
            <div class="border-t border-gray-700 my-8"></div>

            <!-- Bottom section -->
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-400 text-sm mb-4 md:mb-0">
                    &copy; {{ date('Y') }} Sekaijin - Communauté des expatriés français. Tous droits réservés.
                </div>
                
                <!-- Social links -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-400 text-sm">Rejoignez-nous :</span>
                    <div class="flex space-x-3">
                        <a href="https://discord.gg/uz9DnRBMJc" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-indigo-400 transition duration-300" title="Discord">
                            <i class="fab fa-discord"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Made with love -->
            <div class="text-center mt-6 pt-4 border-t border-gray-700">
                <p class="text-gray-500 text-sm flex items-center justify-center">
                    Fait avec <span class="text-red-500 mx-1">❤️</span> pour les expatriés français
                    <span class="ml-2">🇫🇷</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Favorites functionality script -->
    <script nonce="{{ $csp_nonce ?? '' }}">
    // Add event listeners for favorite buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Add click listeners to all favorite buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('[data-toggle-favorite]')) {
                e.preventDefault();
                e.stopPropagation();
                const button = e.target.closest('[data-toggle-favorite]');
                const type = button.dataset.favoriteType;
                const id = button.dataset.favoriteId;
                toggleFavorite(type, id);
            }
        });
    });

    function toggleFavorite(type, id) {
        const button = document.getElementById(`favorite-btn-${type}-${id}`);
        const originalText = button.querySelector('span')?.textContent;
        
        // Disable button during request
        button.disabled = true;
        button.style.opacity = '0.6';
        if (button.querySelector('span')) {
            button.querySelector('span').textContent = 'Chargement...';
        }
        
        fetch('/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                type: type,
                id: id
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const icon = button.querySelector('i');
                const span = button.querySelector('span');
                
                if (data.favorited) {
                    // Item is now favorited
                    button.classList.add('bg-blue-50', 'border-blue-300', 'text-blue-700');
                    button.classList.remove('bg-white', 'border-gray-300', 'text-gray-700');
                    if (icon) icon.classList.add('text-blue-600');
                    if (span) span.textContent = 'Sauvegardé';
                } else {
                    // Item is no longer favorited
                    button.classList.remove('bg-blue-50', 'border-blue-300', 'text-blue-700');
                    button.classList.add('bg-white', 'border-gray-300', 'text-gray-700');
                    if (icon) icon.classList.remove('text-blue-600');
                    if (span) span.textContent = 'Sauvegarder';
                }
            } else {
                throw new Error(data.message || 'Erreur inconnue');
            }
        })
        .catch(error => {
            console.error('Favorite toggle error:', error);
            
            // Restore original text
            if (button.querySelector('span')) {
                button.querySelector('span').textContent = originalText;
            }
            
            // Show user-friendly error message
            let errorMessage = 'Erreur lors de la sauvegarde. Veuillez réessayer.';
            if (error.message.includes('401') || error.message.includes('Unauthenticated')) {
                errorMessage = 'Vous devez être connecté pour sauvegarder du contenu.';
                // Redirect to login after a delay
                setTimeout(() => {
                    window.location.href = '/connexion';
                }, 2000);
            } else if (error.message.includes('422')) {
                errorMessage = 'Ce contenu ne peut pas être sauvegardé.';
            }
            
            showNotification(errorMessage, 'error');
        })
        .finally(() => {
            // Re-enable button
            button.disabled = false;
            button.style.opacity = '1';
        });
    }
    
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(n => n.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification-toast fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;
        
        // Set colors based on type
        if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
        } else if (type === 'error') {
            notification.classList.add('bg-red-500', 'text-white');
        } else {
            notification.classList.add('bg-blue-500', 'text-white');
        }
        
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="flex-1">${message}</span>
                <button class="ml-2 text-white hover:text-gray-200 close-notification">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;
        
        // Add click listener for close button
        notification.querySelector('.close-notification').addEventListener('click', function() {
            notification.remove();
        });
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 4 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 4000);
    }
    </script>
    
    <!-- Share Component JavaScript -->
    <script src="/js/share-component.js" nonce="{{ $csp_nonce ?? '' }}"></script>
</body>
</html>