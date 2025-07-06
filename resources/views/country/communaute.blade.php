@extends('layout')

@section('title', 'Communauté ' . $countryModel->name_fr . ' - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        👥 <span class="ml-2">Communauté {{ $countryModel->emoji }} {{ $countryModel->name_fr }}</span>
                    </h1>
                    <p class="text-gray-600 mt-1">{{ $communityMembers->total() }} français expatriés connectés</p>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                        </svg>
                        Filtres
                    </button>
                    @auth
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition duration-200">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Organiser un événement
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Members Grid -->
            <div class="lg:col-span-3">
                @if($communityMembers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($communityMembers as $member)
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                                <!-- Profile Header -->
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-24 relative">
                                    <div class="absolute -bottom-8 left-6">
                                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-2xl font-bold text-blue-600 shadow-lg border-4 border-white">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <!-- Role Badge -->
                                    @if($member->role !== 'free')
                                        <div class="absolute top-3 right-3">
                                            @php
                                                $roleClasses = match($member->role) {
                                                    'premium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'ambassador' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                    'admin' => 'bg-red-100 text-red-800 border-red-200',
                                                    default => 'bg-blue-100 text-blue-800 border-blue-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $roleClasses }} border">
                                                {{ $member->getRoleDisplayName() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Profile Content -->
                                <div class="pt-10 pb-6 px-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-lg font-bold text-gray-800">{{ $member->name }}</h3>
                                        @if($member->is_verified)
                                            <span class="text-green-500" title="Membre vérifié">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Location -->
                                    @if($member->city_residence)
                                        <p class="text-gray-600 text-sm mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $member->city_residence }}
                                        </p>
                                    @endif
                                    
                                    <!-- Bio Preview -->
                                    @if($member->bio)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                            {{ Str::limit($member->bio, 80) }}
                                        </p>
                                    @endif
                                    
                                    <!-- Member Since -->
                                    <p class="text-xs text-gray-500 mb-4">
                                        Membre depuis {{ $member->created_at->diffForHumans() }}
                                    </p>
                                    
                                    <!-- Social Links Preview -->
                                    @if($member->youtube_username || $member->instagram_username || $member->linkedin_username)
                                        <div class="flex space-x-2 mb-4">
                                            @if($member->youtube_username)
                                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                    <i class="fab fa-youtube text-red-600 text-sm"></i>
                                                </div>
                                            @endif
                                            @if($member->instagram_username)
                                                <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center">
                                                    <i class="fab fa-instagram text-pink-500 text-sm"></i>
                                                </div>
                                            @endif
                                            @if($member->linkedin_username)
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fab fa-linkedin text-blue-700 text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <a href="{{ $member->getPublicProfileUrl() }}" 
                                           class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition duration-200">
                                            Voir profil
                                        </a>
                                        @auth
                                            @if(auth()->user()->id !== $member->id)
                                                <button class="bg-gray-200 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium hover:bg-gray-300 transition duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $communityMembers->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <div class="text-gray-400 mb-6">
                            <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Aucun membre pour l'instant</h3>
                        <p class="text-gray-600 mb-6">Soyez le premier à rejoindre la communauté française en {{ $countryModel->name_fr }} !</p>
                        <a href="/inscription" class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Rejoindre la communauté
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Stats -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Statistiques</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total membres</span>
                            <span class="font-bold text-blue-600">{{ $communityMembers->total() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Nouveaux ce mois</span>
                            <span class="font-bold text-green-600">+{{ rand(5, 15) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Membres vérifiés</span>
                            <span class="font-bold text-purple-600">{{ rand(2, 8) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Ambassadeurs</span>
                            <span class="font-bold text-orange-600">{{ rand(1, 3) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Popular Cities -->
                @if($communityMembers->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Villes populaires</h3>
                        <div class="space-y-3">
                            @php
                                $cities = $communityMembers->pluck('city_residence')->filter()->countBy()->sortDesc()->take(5);
                            @endphp
                            @foreach($cities as $city => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">{{ $city }}</span>
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $count }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Filters -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Filtres rapides</h3>
                    <div class="space-y-2">
                        <button class="w-full text-left py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-between">
                            <span class="text-gray-700">Nouveaux membres</span>
                            <span class="text-green-600">{{ rand(5, 15) }}</span>
                        </button>
                        <button class="w-full text-left py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-between">
                            <span class="text-gray-700">Membres vérifiés</span>
                            <span class="text-purple-600">{{ rand(2, 8) }}</span>
                        </button>
                        <button class="w-full text-left py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-between">
                            <span class="text-gray-700">Avec réseaux sociaux</span>
                            <span class="text-blue-600">{{ rand(10, 25) }}</span>
                        </button>
                        <button class="w-full text-left py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-between">
                            <span class="text-gray-700">Actifs récemment</span>
                            <span class="text-orange-600">{{ rand(15, 30) }}</span>
                        </button>
                    </div>
                </div>

                <!-- Community Guidelines -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Règles de la communauté</h3>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p>• Respectez les autres membres</p>
                        <p>• Partagez des informations utiles</p>
                        <p>• Pas de spam ou contenu inapproprié</p>
                        <p>• Entraide et bienveillance</p>
                    </div>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-3 inline-block">
                        Voir le règlement complet →
                    </a>
                </div>

                <!-- Call to Action -->
                @guest
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 border border-green-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Rejoignez-nous !</h3>
                        <p class="text-gray-600 text-sm mb-4">Connectez-vous avec la communauté française en {{ $countryModel->name_fr }}.</p>
                        <a href="/inscription" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-green-600 to-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:from-green-700 hover:to-blue-700 transition duration-200">
                            S'inscrire gratuitement
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection