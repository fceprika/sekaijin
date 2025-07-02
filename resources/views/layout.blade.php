<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
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
            </div>
        </div>
    </nav>

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