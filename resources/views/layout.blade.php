<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sekaijin - Communauté des expatriés français')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    
    <!-- Mapbox CSS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css" rel="stylesheet">
    
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Mapbox JS -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg relative">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex space-x-7">
                    <div>
                        <a href="/" class="flex items-center py-4 px-2">
                            <span class="font-bold text-blue-600 text-xl">🌍 Sekaijin</span>
                            @if(isset($currentCountry))
                                <span class="ml-2 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $currentCountry->name_fr }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-3">
                    <!-- Direct Country Links -->
                    @foreach($allCountries as $country)
                        <a href="{{ route('country.index', $country->slug) }}" 
                           class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300 {{ isset($currentCountry) && $currentCountry->slug === $country->slug ? 'text-blue-600' : '' }}">
                            {{ $country->emoji }} {{ $country->name_fr }}
                        </a>
                    @endforeach
                    
                    <!-- Country-specific navigation -->
                    @if(isset($currentCountry))
                        <a href="{{ route('country.actualites', $currentCountry->slug) }}" 
                           class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300 {{ request()->routeIs('country.actualites') ? 'text-blue-600' : '' }}">
                            Actualités
                        </a>
                        <a href="{{ route('country.blog', $currentCountry->slug) }}" 
                           class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300 {{ request()->routeIs('country.blog') ? 'text-blue-600' : '' }}">
                            Blog
                        </a>
                        <a href="{{ route('country.communaute', $currentCountry->slug) }}" 
                           class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300 {{ request()->routeIs('country.communaute') ? 'text-blue-600' : '' }}">
                            Communauté
                        </a>
                        <a href="{{ route('country.evenements', $currentCountry->slug) }}" 
                           class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300 {{ request()->routeIs('country.evenements') ? 'text-blue-600' : '' }}">
                            Événements
                        </a>
                    @else
                        <a href="/about" class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300">À propos</a>
                        <a href="/contact" class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300">Contact</a>
                    @endif
                    
                    @auth
                        <div class="relative ml-4 flex items-center space-x-3">
                            <a href="{{ route('public.profile', Auth::user()->name) }}" class="text-blue-600 font-medium hover:text-blue-800 transition duration-300">{{ Auth::user()->name }}</a>
                            <a href="{{ route('profile.show') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">
                                Mon profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition duration-300">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="py-4 px-2 text-blue-600 hover:text-blue-800 transition duration-300 font-medium">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-medium">
                            Inscription
                        </a>
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
                    <span class="font-bold text-blue-600 text-xl">🌍 Sekaijin</span>
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
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-600">{{ Auth::user()->getDisplayLocation() }}</p>
                                </div>
                            </div>
                            <div class="mt-3 flex space-x-2">
                                <a href="{{ route('public.profile', Auth::user()->name) }}" class="text-blue-600 text-sm hover:text-blue-800">Voir profil</a>
                                <a href="{{ route('profile.show') }}" class="text-blue-600 text-sm hover:text-blue-800">Modifier</a>
                            </div>
                        </div>
                    @endauth

                    <!-- Country navigation -->
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Pays</h3>
                        @foreach($allCountries as $country)
                            <a href="{{ route('country.index', $country->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ isset($currentCountry) && $currentCountry->slug === $country->slug ? 'bg-blue-100 text-blue-600' : '' }}">
                                <span class="text-lg mr-2">{{ $country->emoji }}</span>
                                {{ $country->name_fr }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Country-specific sections -->
                    @if(isset($currentCountry))
                        <div class="p-4 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ $currentCountry->name_fr }}</h3>
                            <a href="{{ route('country.actualites', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.actualites') ? 'bg-blue-100 text-blue-600' : '' }}">
                                📰 Actualités
                            </a>
                            <a href="{{ route('country.blog', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.blog') ? 'bg-blue-100 text-blue-600' : '' }}">
                                ✍️ Blog
                            </a>
                            <a href="{{ route('country.communaute', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.communaute') ? 'bg-blue-100 text-blue-600' : '' }}">
                                👥 Communauté
                            </a>
                            <a href="{{ route('country.evenements', $currentCountry->slug) }}" 
                               class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200 {{ request()->routeIs('country.evenements') ? 'bg-blue-100 text-blue-600' : '' }}">
                                📅 Événements
                            </a>
                        </div>
                    @else
                        <div class="p-4 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Général</h3>
                            <a href="/about" class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200">
                                ℹ️ À propos
                            </a>
                            <a href="/contact" class="block py-3 px-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-200">
                                ✉️ Contact
                            </a>
                        </div>
                    @endif
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

    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const closeMobileMenu = document.getElementById('close-mobile-menu');

            function openMenu() {
                mobileMenuOverlay.classList.remove('hidden');
                mobileMenu.classList.remove('translate-x-full');
                document.body.style.overflow = 'hidden';
            }

            function closeMenu() {
                mobileMenu.classList.add('translate-x-full');
                mobileMenuOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            mobileMenuButton.addEventListener('click', openMenu);
            closeMobileMenu.addEventListener('click', closeMenu);
            mobileMenuOverlay.addEventListener('click', closeMenu);

            // Close menu when clicking on navigation links
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', closeMenu);
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
        
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Sekaijin - Communauté des expatriés français. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>