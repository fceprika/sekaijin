@extends('layout')

@section('title', 'Profil de ' . $user->name . ' - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-12 text-white relative">
                <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                <div class="relative">
                    <!-- Info principale -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                            
                            <!-- Role Badge -->
                            @php
                                $roleClasses = match($user->role) {
                                    'premium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'ambassador' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'admin' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-blue-100 text-blue-800 border-blue-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $roleClasses }} border">
                                @if($user->role === 'premium')
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @elseif($user->role === 'ambassador')
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($user->role === 'admin')
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                                {{ $user->getRoleDisplayName() }}
                            </span>
                            
                            @if($user->is_verified)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Membre vérifié
                                </span>
                            @endif
                        </div>
                        
                        <!-- Localisation -->
                        @if($user->country_residence || $user->city_residence)
                            <div class="flex items-center text-blue-100 text-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                @if($user->city_residence && $user->country_residence)
                                    {{ $user->city_residence }}, {{ $user->country_residence }}
                                @elseif($user->country_residence)
                                    {{ $user->country_residence }}
                                @endif
                            </div>
                        @endif
                        
                        <!-- Date d'inscription -->
                        <div class="flex items-center text-blue-100 text-sm mt-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Membre depuis {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Biographie -->
            <div class="lg:col-span-2">
                @if($user->bio)
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            À propos
                        </h2>
                        <div class="prose prose-gray max-w-none">
                            <div class="text-gray-700 leading-relaxed text-lg whitespace-pre-line">{{ $user->bio }}</div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-500 mb-2">Biographie non renseignée</h3>
                        <p class="text-gray-400">Ce membre n'a pas encore ajouté de biographie.</p>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informations
                    </h3>
                    
                    <div class="space-y-4">
                        @if($user->country_residence)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Pays de résidence</p>
                                    <p class="text-gray-800">{{ $user->country_residence }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($user->city_residence)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Ville</p>
                                    <p class="text-gray-800">{{ $user->city_residence }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Membre depuis</p>
                                <p class="text-gray-800">{{ $user->created_at->locale('fr')->translatedFormat('F Y') }}</p>
                            </div>
                        </div>
                        
                        @if($user->youtube_username)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-youtube text-red-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">YouTube</p>
                                    <a href="https://www.youtube.com/{{ $user->youtube_username }}" target="_blank" rel="noopener noreferrer" 
                                       class="text-red-600 hover:text-red-700 font-medium flex items-center group">
                                        {{ $user->youtube_username }}
                                        <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($user->instagram_username)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-instagram text-pink-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Instagram</p>
                                    <a href="https://instagram.com/{{ $user->instagram_username }}" target="_blank" rel="noopener noreferrer" 
                                       class="text-pink-500 hover:text-pink-600 font-medium flex items-center group">
                                        {{ $user->instagram_username }}
                                        <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($user->tiktok_username)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-tiktok text-black"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">TikTok</p>
                                    @php
                                        $tiktokUsername = str_starts_with($user->tiktok_username, '@') ? substr($user->tiktok_username, 1) : $user->tiktok_username;
                                    @endphp
                                    <a href="https://tiktok.com/@{{ $tiktokUsername }}" target="_blank" rel="noopener noreferrer" 
                                       class="text-gray-800 hover:text-black font-medium flex items-center group">
                                        {{ $user->tiktok_username }}
                                        <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($user->linkedin_username)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-linkedin text-blue-700"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">LinkedIn</p>
                                    <a href="https://linkedin.com/in/{{ $user->linkedin_username }}" target="_blank" rel="noopener noreferrer" 
                                       class="text-blue-700 hover:text-blue-800 font-medium flex items-center group">
                                        {{ $user->linkedin_username }}
                                        <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($user->twitter_username)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-twitter text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Twitter / X</p>
                                    @php
                                        $twitterUsername = str_starts_with($user->twitter_username, '@') ? substr($user->twitter_username, 1) : $user->twitter_username;
                                    @endphp
                                    <a href="https://twitter.com/{{ $twitterUsername }}" target="_blank" rel="noopener noreferrer" 
                                       class="text-blue-400 hover:text-blue-500 font-medium flex items-center group">
                                        {{ $user->twitter_username }}
                                        <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($user->facebook_username)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-facebook text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Facebook</p>
                                    <a href="https://facebook.com/{{ $user->facebook_username }}" target="_blank" rel="noopener noreferrer" 
                                       class="text-blue-600 hover:text-blue-700 font-medium flex items-center group">
                                        {{ $user->facebook_username }}
                                        <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($user->telegram_username)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-telegram text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Telegram</p>
                                    @php
                                        $telegramUsername = str_starts_with($user->telegram_username, '@') ? substr($user->telegram_username, 1) : $user->telegram_username;
                                    @endphp
                                    <a href="https://t.me/{{ $telegramUsername }}" target="_blank" rel="noopener noreferrer" 
                                       class="text-blue-500 hover:text-blue-600 font-medium flex items-center group">
                                        {{ $user->telegram_username }}
                                        <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Call to Action -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Rejoignez la communauté</h3>
                    <p class="text-gray-600 text-sm mb-4">Connectez-vous avec des expatriés français du monde entier.</p>
                    <a href="/inscription" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection