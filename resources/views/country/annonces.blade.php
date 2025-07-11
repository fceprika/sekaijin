@extends('layout')

@section('title', 'Annonces ' . $countryModel->name_fr)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- En-tête avec badge pays -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <span class="text-2xl mr-2">{{ $countryModel->emoji }}</span>
                        <h1 class="text-2xl font-bold text-gray-900">Annonces {{ $countryModel->name_fr }}</h1>
                    </div>
                    <p class="text-sm text-gray-600">Découvrez les biens, services et logements proposés par la communauté française en {{ $countryModel->name_fr }}</p>
                </div>
                @auth
                    <a href="{{ route('country.announcements.create', $countryModel->slug) }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Créer une annonce
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filtres (sidebar) -->
            <div class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h2>
                    
                    <form action="{{ route('country.annonces', $countryModel->slug) }}" method="GET" id="filter-form">
                        <!-- Type d'annonce -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Type d'annonce</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Toutes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="vente" {{ request('type') == 'vente' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Vente</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="location" {{ request('type') == 'location' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Location</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="colocation" {{ request('type') == 'colocation' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Colocation</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="service" {{ request('type') == 'service' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Service</span>
                                </label>
                            </div>
                        </div>

                        <!-- Ville -->
                        @if($cities->isNotEmpty())
                            <div class="mb-6">
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                                <select name="city" id="city" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Toutes les villes</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Tri -->
                        <div class="mb-6">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                            <select name="sort" id="sort" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="recent" {{ request('sort', 'recent') == 'recent' ? 'selected' : '' }}>Plus récentes</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Plus consultées</option>
                            </select>
                        </div>

                        <!-- Recherche -->
                        <div class="mb-6">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   placeholder="Mots-clés..."
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 text-sm font-medium">
                            Appliquer les filtres
                        </button>
                        
                        @if(request()->hasAny(['type', 'city', 'sort', 'search']))
                            <a href="{{ route('country.annonces', $countryModel->slug) }}" class="block text-center mt-2 text-sm text-gray-600 hover:text-gray-900">
                                Réinitialiser
                            </a>
                        @endif
                    </form>
                </div>

                @auth
                    <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                        <a href="{{ route('announcements.my') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Mes annonces
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Liste des annonces -->
            <div class="flex-1">
                @if($announcements->isEmpty())
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-4xl mr-2">{{ $countryModel->emoji }}</span>
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune annonce trouvée en {{ $countryModel->name_fr }}</h3>
                        <p class="text-gray-500 mb-6">
                            @if(request()->hasAny(['type', 'city', 'search']))
                                Essayez de modifier vos critères de recherche
                            @else
                                Soyez le premier à publier une annonce dans ce pays !
                            @endif
                        </p>
                        @auth
                            <a href="{{ route('country.announcements.create', $countryModel->slug) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Créer une annonce
                            </a>
                        @endauth
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($announcements as $announcement)
                            <a href="{{ route('country.announcement.show', [$countryModel->slug, $announcement]) }}" class="block bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                @php
                                    $images = is_array($announcement->images) ? array_values(array_filter($announcement->images)) : [];
                                @endphp
                                @if($images && count($images) > 0)
                                    <img src="{{ Storage::url($images[0]) }}" alt="{{ $announcement->title }}" class="w-full h-48 object-cover rounded-t-lg">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $announcement->type == 'vente' ? 'bg-blue-100 text-blue-800' : ($announcement->type == 'location' ? 'bg-green-100 text-green-800' : ($announcement->type == 'colocation' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ $announcement->type_display }}
                                        </span>
                                        <span class="text-lg font-semibold text-gray-900">
                                            {{ $announcement->formatted_price }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">{{ $announcement->title }}</h3>
                                    
                                    <div class="flex items-center text-xs text-gray-500 mb-2">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $announcement->city }}
                                        @if($announcement->address)
                                            - {{ Str::limit($announcement->address, 20) }}
                                        @endif
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $announcement->description }}</p>
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center">
                                            @if($announcement->user->avatar)
                                                <img src="{{ Storage::url('avatars/' . $announcement->user->avatar) }}" alt="{{ $announcement->user->name }}" class="h-5 w-5 rounded-full mr-1 border-2 border-blue-500">
                                            @else
                                                <div class="h-5 w-5 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold mr-1 border-2 border-blue-500">
                                                    {{ substr($announcement->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span>{{ $announcement->user->name }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                {{ $announcement->views }}
                                            </span>
                                            <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $announcements->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    const form = document.getElementById('filter-form');
    const inputs = form.querySelectorAll('input[type="radio"], select');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            form.submit();
        });
    });
});
</script>
@endsection