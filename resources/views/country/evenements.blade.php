@extends('layout')

@section('title', 'Événements ' . $countryModel->name_fr . ' - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        📅 <span class="ml-2">Événements {{ $countryModel->emoji }} {{ $countryModel->name_fr }}</span>
                    </h1>
                    <p class="text-gray-600 mt-1">Rencontres et activités de la communauté française</p>
                </div>
                @auth
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition duration-200">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Créer un événement
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Events List -->
            <div class="lg:col-span-2">
                <!-- Filter Tabs -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex flex-wrap gap-2">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium text-sm">
                            Tous les événements
                        </button>
                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-200 transition duration-200">
                            Cette semaine
                        </button>
                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-200 transition duration-200">
                            Ce mois-ci
                        </button>
                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-200 transition duration-200">
                            En ligne
                        </button>
                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-200 transition duration-200">
                            Gratuit
                        </button>
                    </div>
                </div>

                @if($featuredEvents->count() > 0)
                    <!-- Featured Event -->
                    @php $featuredEvent = $featuredEvents->first() @endphp
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="relative h-48 bg-gradient-to-r from-green-500 to-blue-600">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    À LA UNE
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <div class="text-4xl font-bold">{{ $featuredEvent->start_date->format('d') }}</div>
                                <div class="text-sm">{{ strtoupper($featuredEvent->start_date->format('M')) }}</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                @php
                                    $categoryColor = match($featuredEvent->category) {
                                        'networking' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                        'conférence' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                        'culturel' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                                        'gastronomie' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                                        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                                    };
                                @endphp
                                <span class="{{ $categoryColor['bg'] }} {{ $categoryColor['text'] }} px-2 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst($featuredEvent->category) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $featuredEvent->start_date->format('H:i') }}
                                    @if($featuredEvent->end_date)
                                        - {{ $featuredEvent->end_date->format('H:i') }}
                                    @endif
                                </span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                <a href="{{ route('country.event.show', [$countryModel->slug, $featuredEvent->slug]) }}" class="hover:text-green-600">
                                    {{ $featuredEvent->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 mb-4">
                                {{ $featuredEvent->description }}
                            </p>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    @if($featuredEvent->is_online)
                                        En ligne
                                    @else
                                        {{ $featuredEvent->location }}
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    {{ $featuredEvent->formatted_price }}
                                </div>
                                @if($featuredEvent->max_participants)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        {{ $featuredEvent->current_participants }} participants inscrits
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr($featuredEvent->organizer->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-gray-700 font-medium">
                                        Organisé par 
                                        <a href="{{ $featuredEvent->organizer->getPublicProfileUrl() }}" class="hover:text-green-600">
                                            {{ $featuredEvent->organizer->name }}
                                        </a>
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg font-medium text-sm hover:bg-gray-200 transition duration-200">
                                        Partager
                                    </button>
                                    <a href="{{ route('country.event.show', [$countryModel->slug, $featuredEvent->slug]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-green-700 transition duration-200">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($upcomingEvents->count() > 0)
                    <!-- Regular Events -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        @foreach($upcomingEvents as $event)
                            @if(!($featuredEvents->count() > 0 && $event->id === $featuredEvents->first()->id))
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                                    @php
                                        $eventGradient = match($event->category) {
                                            'networking' => 'from-green-400 to-teal-500',
                                            'conférence' => 'from-blue-400 to-purple-500',
                                            'culturel' => 'from-purple-400 to-pink-500',
                                            'gastronomie' => 'from-orange-400 to-red-500',
                                            default => 'from-gray-400 to-gray-600',
                                        };
                                        $categoryColorSmall = match($event->category) {
                                            'networking' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                            'conférence' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                            'culturel' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                                            'gastronomie' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                                        };
                                    @endphp
                                    <div class="h-32 bg-gradient-to-r {{ $eventGradient }} relative">
                                        <div class="absolute bottom-2 left-4 text-white">
                                            <div class="text-2xl font-bold">{{ $event->start_date->format('d') }}</div>
                                            <div class="text-xs">{{ strtoupper($event->start_date->format('M')) }}</div>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="{{ $categoryColorSmall['bg'] }} {{ $categoryColorSmall['text'] }} px-2 py-1 rounded-full text-xs font-medium">
                                                {{ ucfirst($event->category) }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $event->start_date->format('H:i') }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-2">
                                            <a href="{{ route('country.event.show', [$countryModel->slug, $event->slug]) }}" class="hover:text-blue-600">
                                                {{ $event->title }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-600 text-sm mb-3">
                                            {{ $event->description }}
                                        </p>
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                            <span>
                                                📍 @if($event->is_online)
                                                    En ligne
                                                @else
                                                    {{ $event->location }}
                                                @endif
                                            </span>
                                            <span>💰 {{ $event->formatted_price }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            @if($event->max_participants)
                                                <span class="text-xs text-gray-600">{{ $event->current_participants }} inscrits</span>
                                            @else
                                                <span class="text-xs text-gray-600">{{ $event->organizer->name }}</span>
                                            @endif
                                            <a href="{{ route('country.event.show', [$countryModel->slug, $event->slug]) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium hover:bg-blue-700 transition duration-200">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center">
                        {{ $upcomingEvents->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📅</div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun événement à venir</h3>
                        <p class="text-gray-500 mb-6">Les prochains événements apparaîtront ici.</p>
                        @auth
                            <button class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
                                Créer le premier événement
                            </button>
                        @else
                            <a href="{{ route('register') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors inline-block">
                                Rejoindre pour voir les événements
                            </a>
                        @endauth
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Types d'événements</h3>
                    <div class="space-y-2">
                        @php
                            $eventCategories = [
                                'networking' => ['count' => $upcomingEvents->where('category', 'networking')->count(), 'color' => 'green'],
                                'conférence' => ['count' => $upcomingEvents->where('category', 'conférence')->count(), 'color' => 'blue'],
                                'culturel' => ['count' => $upcomingEvents->where('category', 'culturel')->count(), 'color' => 'purple'],
                                'gastronomie' => ['count' => $upcomingEvents->where('category', 'gastronomie')->count(), 'color' => 'orange'],
                            ];
                        @endphp
                        @foreach($eventCategories as $category => $data)
                            @if($data['count'] > 0)
                                <a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-200">
                                    <span class="text-gray-700">{{ ucfirst($category) }}</span>
                                    <span class="bg-{{ $data['color'] }}-100 text-{{ $data['color'] }}-800 px-2 py-1 rounded-full text-xs">{{ $data['count'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Statistiques</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Événements ce mois-ci</span>
                            <span class="font-bold text-green-600">{{ $eventStats['this_month'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Événements gratuits</span>
                            <span class="font-bold text-blue-600">{{ $eventStats['free'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Événements en ligne</span>
                            <span class="font-bold text-purple-600">{{ $eventStats['online'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Create Event CTA -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 border border-green-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Organisez un événement</h3>
                    <p class="text-gray-600 text-sm mb-4">Créez votre propre événement et rassemblez la communauté française !</p>
                    @auth
                        <button class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:from-green-700 hover:to-blue-700 transition duration-200">
                            Créer un événement
                        </button>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-green-600 to-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:from-green-700 hover:to-blue-700 transition duration-200">
                            Rejoindre la communauté
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection