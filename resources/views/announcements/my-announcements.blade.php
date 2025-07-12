@extends('layout')

@section('title', 'Mes annonces')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header avec gradient -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-xl p-4 sm:p-6 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">Mes annonces</h1>
                    <p class="text-blue-100 text-sm sm:text-base">Gérez vos annonces et suivez leurs performances</p>
                </div>
                <a href="{{ route('announcements.create') }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-white bg-opacity-20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-opacity-30 transition duration-300 shadow-lg text-sm sm:text-base">
                    <svg class="mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden sm:inline">Nouvelle annonce</span>
                    <span class="sm:hidden">Nouvelle</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-b-xl shadow-xl border-t border-gray-100">
            <div class="p-6 sm:p-8">

                @if($announcements->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center">
                            <svg class="h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune annonce</h3>
                        <p class="text-lg text-gray-500 mb-8 max-w-md mx-auto">Vous n'avez pas encore publié d'annonce. Commencez dès maintenant à partager vos biens et services !</p>
                        <div class="space-y-4">
                            <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition duration-300 shadow-lg transform hover:scale-105">
                                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Créer ma première annonce
                            </a>
                            <div class="text-sm text-gray-400">
                                C'est gratuit et ça ne prend que quelques minutes
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Statistiques rapides -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 lg:p-6 border border-blue-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-500 rounded-lg flex-shrink-0">
                                    <svg class="h-4 w-4 lg:h-6 lg:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 lg:ml-4 min-w-0">
                                    <p class="text-xs lg:text-sm font-medium text-blue-600">Total</p>
                                    <p class="text-xl lg:text-2xl font-bold text-blue-900">{{ $announcements->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 lg:p-6 border border-green-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-500 rounded-lg flex-shrink-0">
                                    <svg class="h-4 w-4 lg:h-6 lg:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 lg:ml-4 min-w-0">
                                    <p class="text-xs lg:text-sm font-medium text-green-600">Actives</p>
                                    <p class="text-xl lg:text-2xl font-bold text-green-900">{{ $announcements->where('status', 'active')->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-4 lg:p-6 border border-yellow-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-500 rounded-lg flex-shrink-0">
                                    <svg class="h-4 w-4 lg:h-6 lg:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 lg:ml-4 min-w-0">
                                    <p class="text-xs lg:text-sm font-medium text-yellow-600">En attente</p>
                                    <p class="text-xl lg:text-2xl font-bold text-yellow-900">{{ $announcements->where('status', 'pending')->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 lg:p-6 border border-purple-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-500 rounded-lg flex-shrink-0">
                                    <svg class="h-4 w-4 lg:h-6 lg:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 lg:ml-4 min-w-0">
                                    <p class="text-xs lg:text-sm font-medium text-purple-600">Total vues</p>
                                    <p class="text-xl lg:text-2xl font-bold text-purple-900">{{ $announcements->sum('views') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop: Table view -->
                    <div class="hidden lg:block overflow-hidden rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Annonce
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Vues
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="relative px-6 py-4">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($announcements as $announcement)
                                    <tr class="hover:bg-gray-50 transition duration-200">
                                        <td class="px-6 py-6">
                                            <div class="flex items-center">
                                                @php
                                                    $rawImages = $announcement->images;
                                                    if (is_string($rawImages)) {
                                                        $images = json_decode($rawImages, true) ?: [];
                                                    } elseif (is_array($rawImages)) {
                                                        $images = $rawImages;
                                                    } else {
                                                        $images = [];
                                                    }
                                                    $images = array_values(array_filter($images));
                                                @endphp
                                                @if($images && count($images) > 0)
                                                    <div class="h-16 w-16 rounded-xl overflow-hidden shadow-sm border-2 border-white">
                                                        <img src="{{ Storage::url($images[0]) }}" alt="{{ $announcement->title }}" class="h-full w-full object-cover">
                                                    </div>
                                                @else
                                                    <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-sm border-2 border-white">
                                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-base font-semibold text-gray-900 mb-1">
                                                        <a href="{{ route('announcements.show', $announcement) }}" class="hover:text-blue-600 transition duration-200">
                                                            {{ $announcement->title }}
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500 flex items-center">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $announcement->city }}, {{ $announcement->country }}
                                                    </div>
                                                    @if($announcement->price)
                                                        <div class="text-sm font-semibold text-blue-600 mt-1">
                                                            {{ number_format($announcement->price, 0, ',', ' ') }} {{ $announcement->currency }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium shadow-sm {{ $announcement->type == 'vente' ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300' : ($announcement->type == 'location' ? 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300' : ($announcement->type == 'colocation' ? 'bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 border border-purple-300' : 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300')) }}">
                                                @if($announcement->type == 'vente') Vente
                                                @elseif($announcement->type == 'location') Location
                                                @elseif($announcement->type == 'colocation') Colocation
                                                @else Service @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium shadow-sm {{ $announcement->status == 'active' ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300' : ($announcement->status == 'pending' ? 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300' : 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300') }}">
                                                @if($announcement->status == 'active') Active
                                                @elseif($announcement->status == 'pending') En attente
                                                @else Refusée @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="h-4 w-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span class="font-semibold">{{ $announcement->views }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0v4a2 2 0 002 2h2m-4-6v4a2 2 0 002 2h2m-4-6h4"></path>
                                                </svg>
                                                {{ $announcement->created_at->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end space-x-3">
                                                @if($announcement->isActive())
                                                    <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                                                        Voir
                                                    </a>
                                                @endif
                                                <a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                                                    Modifier
                                                </a>
                                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition duration-200">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile & Tablet: Card view -->
                    <div class="block lg:hidden space-y-4">
                        @foreach($announcements as $announcement)
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                @php
                                    $rawImages = $announcement->images;
                                    if (is_string($rawImages)) {
                                        $images = json_decode($rawImages, true) ?: [];
                                    } elseif (is_array($rawImages)) {
                                        $images = $rawImages;
                                    } else {
                                        $images = [];
                                    }
                                    $images = array_values(array_filter($images));
                                @endphp
                                
                                <div class="p-4">
                                    <div class="flex items-start space-x-4">
                                        <!-- Image -->
                                        <div class="flex-shrink-0">
                                            @if($images && count($images) > 0)
                                                <div class="h-20 w-20 rounded-xl overflow-hidden shadow-sm border-2 border-white">
                                                    <img src="{{ Storage::url($images[0]) }}" alt="{{ $announcement->title }}" class="h-full w-full object-cover">
                                                </div>
                                            @else
                                                <div class="h-20 w-20 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-sm border-2 border-white">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                        <a href="{{ route('announcements.show', $announcement) }}" class="hover:text-blue-600 transition duration-200">
                                                            {{ Str::limit($announcement->title, 40) }}
                                                        </a>
                                                    </h3>
                                                    
                                                    <!-- Location -->
                                                    <div class="text-sm text-gray-500 flex items-center mb-2">
                                                        <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $announcement->city }}, {{ $announcement->country }}
                                                    </div>

                                                    <!-- Price -->
                                                    @if($announcement->price)
                                                        <div class="text-lg font-bold text-blue-600 mb-3">
                                                            {{ number_format($announcement->price, 0, ',', ' ') }} {{ $announcement->currency }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Badges -->
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                <!-- Type -->
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $announcement->type == 'vente' ? 'bg-green-100 text-green-800' : ($announcement->type == 'location' ? 'bg-blue-100 text-blue-800' : ($announcement->type == 'colocation' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                    @if($announcement->type == 'vente') Vente
                                                    @elseif($announcement->type == 'location') Location
                                                    @elseif($announcement->type == 'colocation') Colocation
                                                    @else Service @endif
                                                </span>
                                                
                                                <!-- Status -->
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $announcement->status == 'active' ? 'bg-green-100 text-green-800' : ($announcement->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    @if($announcement->status == 'active') Active
                                                    @elseif($announcement->status == 'pending') En attente
                                                    @else Refusée @endif
                                                </span>
                                            </div>

                                            <!-- Stats -->
                                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    {{ $announcement->views }} vues
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0v4a2 2 0 002 2h2m-4-6v4a2 2 0 002 2h2m-4-6h4"></path>
                                                    </svg>
                                                    {{ $announcement->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex flex-wrap gap-2">
                                                @if($announcement->isActive())
                                                    <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Voir
                                                    </a>
                                                @endif
                                                <a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Modifier
                                                </a>
                                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition duration-200">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination avec style amélioré -->
                    @if($announcements->hasPages())
                        <div class="mt-8 flex justify-center">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                {{ $announcements->links() }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection