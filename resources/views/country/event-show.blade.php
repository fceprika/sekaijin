@extends('layout')

@section('title', $event->title . ' - Événements ' . $currentCountry->name_fr)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8 text-sm">
            <ol class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('country.index', $currentCountry->slug) }}" class="hover:text-blue-600">{{ $currentCountry->name_fr }}</a></li>
                <li class="before:content-['/'] before:mx-2">
                    <a href="{{ route('country.evenements', $currentCountry->slug) }}" class="hover:text-blue-600">Événements</a>
                </li>
                <li class="before:content-['/'] before:mx-2 text-gray-900 font-medium">{{ $event->title }}</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Event Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ ucfirst($event->category) }}
                    </span>
                    <div class="flex items-center space-x-2">
                        @if($event->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                Événement vedette
                            </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $event->isFree() ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            {{ $event->formatted_price }}
                        </span>
                    </div>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                
                <p class="text-xl text-gray-600 mb-6">{{ $event->description }}</p>

                <!-- Event Details -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 text-gray-700">
                            <i class="fas fa-calendar-alt text-green-600"></i>
                            <span>{{ $event->start_date->format('l d F Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-3 text-gray-700">
                            <i class="fas fa-clock text-green-600"></i>
                            <span>
                                {{ $event->start_date->format('H:i') }}
                                @if($event->end_date)
                                    - {{ $event->end_date->format('H:i') }}
                                @endif
                            </span>
                        </div>
                        @if($event->location || $event->is_online)
                            <div class="flex items-center space-x-3 text-gray-700">
                                <i class="fas fa-{{ $event->is_online ? 'video' : 'map-marker-alt' }} text-green-600"></i>
                                <span>
                                    @if($event->is_online)
                                        Événement en ligne
                                    @else
                                        {{ $event->location }}
                                        @if($event->address)
                                            <br><span class="text-sm text-gray-500">{{ $event->address }}</span>
                                        @endif
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-3">
                        @if($event->max_participants)
                            <div class="flex items-center space-x-3 text-gray-700">
                                <i class="fas fa-users text-green-600"></i>
                                <span>{{ $event->current_participants }}/{{ $event->max_participants }} participants</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($event->current_participants / $event->max_participants) * 100 }}%"></div>
                            </div>
                        @endif
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">
                                    {{ strtoupper(substr($event->organizer->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Organisé par</p>
                                <a href="{{ route('public.profile', $event->organizer->name) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $event->organizer->name }}
                                    @if($event->organizer->is_verified)
                                        <i class="fas fa-check-circle text-blue-500 ml-1"></i>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex items-center justify-between">
                    @if($event->hasAvailableSpots())
                        <button class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                            @if($event->isFree())
                                S'inscrire gratuitement
                            @else
                                S'inscrire - {{ $event->formatted_price }}
                            @endif
                        </button>
                    @else
                        <button class="bg-gray-400 text-white font-medium py-3 px-6 rounded-lg cursor-not-allowed" disabled>
                            Complet
                        </button>
                    @endif
                    
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-share-alt"></i>
                            <span class="ml-1">Partager</span>
                        </button>
                        <button class="text-gray-600 hover:text-red-600 transition-colors">
                            <i class="far fa-heart"></i>
                            <span class="ml-1">Sauvegarder</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Event Description -->
            @if($event->full_description)
            <div class="px-8 py-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                <div class="prose prose-lg max-w-none">
                    {!! nl2br(e($event->full_description)) !!}
                </div>
            </div>
            @endif

            <!-- Event Footer -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        @if($event->is_online && $event->online_link)
                            <p><strong>Lien de l'événement :</strong> <a href="{{ $event->online_link }}" target="_blank" class="text-blue-600 hover:text-blue-700">{{ $event->online_link }}</a></p>
                        @endif
                    </div>
                    
                    <a href="{{ route('country.evenements', $currentCountry->slug) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        ← Retour aux événements
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Events -->
        @if($relatedEvents->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Autres événements à venir</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($relatedEvents as $relatedEvent)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($relatedEvent->category) }}
                            </span>
                            <span class="text-xs font-medium {{ $relatedEvent->isFree() ? 'text-blue-600' : 'text-purple-600' }}">
                                {{ $relatedEvent->formatted_price }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('country.event.show', [$currentCountry->slug, $relatedEvent->slug]) }}" class="hover:text-blue-600">
                                {{ $relatedEvent->title }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $relatedEvent->description }}</p>
                        
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $relatedEvent->start_date->format('d/m/Y') }}</span>
                            @if($relatedEvent->max_participants)
                                <span>{{ $relatedEvent->current_participants }}/{{ $relatedEvent->max_participants }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection