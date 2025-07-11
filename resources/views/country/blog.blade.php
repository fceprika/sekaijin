@extends('layout')

@section('title', 'Blog ' . $countryModel->name_fr . ' - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        ✍️ <span class="ml-2">Blog {{ $countryModel->emoji }} {{ $countryModel->name_fr }}</span>
                    </h1>
                    <p class="text-gray-600 mt-1">Témoignages et conseils de la communauté française</p>
                </div>
                @auth
                    <a href="{{ route('articles.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-purple-700 transition duration-200 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Écrire un article
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Articles List -->
            <div class="lg:col-span-2">
                @if($featuredArticles->count() > 0)
                    <!-- Featured Article -->
                    @php $featuredArticle = $featuredArticles->first() @endphp
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="h-64 bg-gradient-to-r from-purple-500 to-pink-600 relative">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute bottom-4 left-4">
                                <span class="bg-white text-purple-600 px-3 py-1 rounded-full text-xs font-bold">
                                    Article vedette
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst($featuredArticle->category) }}
                                </span>
                                <span class="mx-2">•</span>
                                <span>{{ $featuredArticle->published_at->diffForHumans() }}</span>
                                @if($featuredArticle->reading_time)
                                    <span class="mx-2">•</span>
                                    <span>{{ $featuredArticle->reading_time }}</span>
                                @endif
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                                <a href="{{ route('country.article.show', [$countryModel->slug, $featuredArticle->slug]) }}" class="hover:text-purple-600">
                                    {{ $featuredArticle->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 mb-4">
                                {{ $featuredArticle->excerpt }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    @if($featuredArticle->author->avatar)
                                        <img src="{{ asset('storage/avatars/' . $featuredArticle->author->avatar) }}" 
                                             alt="Avatar de {{ $featuredArticle->author->name }}" 
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($featuredArticle->author->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ $featuredArticle->author->getPublicProfileUrl() }}" class="text-sm font-medium text-gray-800 hover:text-purple-600">
                                            {{ $featuredArticle->author->name }}
                                            @if($featuredArticle->author->is_verified)
                                                <i class="fas fa-check-circle text-blue-500 ml-1"></i>
                                            @endif
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $featuredArticle->author->getRoleDisplayName() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        {{ $featuredArticle->likes }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        {{ $featuredArticle->views }}
                                    </span>
                                    <a href="{{ route('country.article.show', [$countryModel->slug, $featuredArticle->slug]) }}" class="text-purple-600 hover:text-purple-800 font-medium">Lire →</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endif

                <!-- Regular Articles Grid -->
                @if($articles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        @foreach($articles as $article)
                            @if(!($featuredArticles->count() > 0 && $article->id === $featuredArticles->first()->id))
                                <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer group">
                                    @php
                                        $gradientClass = match($article->category) {
                                            'témoignage' => 'from-purple-400 to-purple-600',
                                            'guide-pratique' => 'from-blue-400 to-blue-600', 
                                            'travail' => 'from-indigo-400 to-purple-500',
                                            'lifestyle' => 'from-green-400 to-green-600',
                                            'cuisine' => 'from-orange-400 to-red-500',
                                            default => 'from-gray-400 to-gray-600',
                                        };
                                        $categoryColor = match($article->category) {
                                            'témoignage' => 'purple',
                                            'guide-pratique' => 'blue',
                                            'travail' => 'indigo', 
                                            'lifestyle' => 'green',
                                            'cuisine' => 'orange',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <a href="{{ route('country.article.show', [$countryModel->slug, $article->slug]) }}" class="block">
                                        <div class="h-48 bg-gradient-to-r {{ $gradientClass }}"></div>
                                        <div class="p-5">
                                        <div class="flex items-center text-sm text-gray-500 mb-2">
                                            <span class="bg-{{ $categoryColor }}-100 text-{{ $categoryColor }}-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ ucfirst($article->category) }}
                                            </span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $article->published_at->diffForHumans() }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors">
                                            {{ $article->title }}
                                        </h3>
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                            {{ $article->excerpt }}
                                        </p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                @if($article->author->avatar)
                                                    <img src="{{ asset('storage/avatars/' . $article->author->avatar) }}" 
                                                         alt="Avatar de {{ $article->author->name }}" 
                                                         class="w-6 h-6 rounded-full object-cover">
                                                @else
                                                    <div class="w-6 h-6 bg-{{ $categoryColor }}-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                                        {{ strtoupper(substr($article->author->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <a href="{{ $article->author->getPublicProfileUrl() }}" class="text-xs text-gray-700 hover:text-blue-600">
                                                    {{ $article->author->name }}
                                                </a>
                                            </div>
                                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                <span>❤️ {{ $article->likes }}</span>
                                                <span>👁️ {{ $article->views }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </article>
                            @endif
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center">
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📝</div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun article pour le moment</h3>
                        <p class="text-gray-500 mb-6">Soyez le premier à partager votre expérience !</p>
                        @auth
                            <button class="bg-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-purple-700 transition-colors">
                                Écrire le premier article
                            </button>
                        @else
                            <a href="{{ route('register') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-purple-700 transition-colors inline-block">
                                Rejoindre la communauté
                            </a>
                        @endauth
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Catégories</h3>
                    <div class="space-y-2">
                        @php
                            $categories = [
                                'témoignage' => ['count' => $articles->where('category', 'témoignage')->count(), 'color' => 'purple'],
                                'guide-pratique' => ['count' => $articles->where('category', 'guide-pratique')->count(), 'color' => 'blue'],
                                'travail' => ['count' => $articles->where('category', 'travail')->count(), 'color' => 'indigo'],
                                'lifestyle' => ['count' => $articles->where('category', 'lifestyle')->count(), 'color' => 'green'],
                                'cuisine' => ['count' => $articles->where('category', 'cuisine')->count(), 'color' => 'orange'],
                            ];
                        @endphp
                        @foreach($categories as $category => $data)
                            @if($data['count'] > 0)
                                <a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-200">
                                    <span class="text-gray-700">{{ ucfirst(str_replace('-', ' ', $category)) }}</span>
                                    <span class="bg-{{ $data['color'] }}-100 text-{{ $data['color'] }}-800 px-2 py-1 rounded-full text-xs">{{ $data['count'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Write for Us -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Partagez votre expérience</h3>
                    <p class="text-gray-600 text-sm mb-4">Votre histoire peut aider d'autres expatriés. Rejoignez notre communauté de rédacteurs !</p>
                    @auth
                        <a href="{{ route('articles.create') }}" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition duration-200 inline-flex items-center justify-center">
                            Écrire un article
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition duration-200">
                            Rejoindre la communauté
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@endsection