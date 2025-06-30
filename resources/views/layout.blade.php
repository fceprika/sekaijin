<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sekaijin - Communauté des expatriés français')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="/" class="flex items-center py-4 px-2">
                            <span class="font-bold text-blue-600 text-xl">🌍 Sekaijin</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <a href="/" class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300">Accueil</a>
                    <a href="/about" class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300">À propos</a>
                    <a href="/services" class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300">Services</a>
                    <a href="/contact" class="py-4 px-2 text-gray-500 hover:text-blue-600 transition duration-300">Contact</a>
                    
                    @auth
                        <div class="relative ml-4">
                            <span class="text-blue-600 font-medium">{{ Auth::user()->first_name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
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