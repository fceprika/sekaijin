@extends('layout')

@section('title', 'Mes favoris - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes favoris</h1>
            <p class="text-gray-600">Retrouvez ici tous les articles et actualités que vous avez sauvegardés</p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Articles favoris</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $favoriteArticles->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-newspaper text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Actualités favorites</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $favoriteNews->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <i class="fas fa-bookmark text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $favoriteArticles->total() + $favoriteNews->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid: Articles and News in 2 columns -->
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Articles Column -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                        Articles favoris ({{ $favoriteArticles->total() }})
                    </h2>
                </div>
                
                @if($favoriteArticles->count() > 0)
                    <div class="space-y-4">
                        @foreach($favoriteArticles as $article)
                            <a href="{{ route('country.article.show', [$article->country->slug, $article->slug]) }}" 
                               class="block bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all hover:scale-[1.02] group">
                                <div class="p-5">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($article->category) }}
                                        </span>
                                        <button onclick="event.preventDefault(); event.stopPropagation(); toggleFavorite('article', {{ $article->id }})" 
                                                id="favorite-btn-article-{{ $article->id }}"
                                                class="p-2 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                            <i class="fas fa-bookmark text-sm"></i>
                                        </button>
                                    </div>
                                    
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                        {{ $article->title }}
                                    </h3>
                                    
                                    @if($article->excerpt)
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $article->excerpt }}</p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center space-x-2">
                                            @if($article->country)
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $article->country->name_fr }}</span>
                                            @endif
                                            <span>{{ $article->published_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($article->reading_time)
                                                <span>{{ $article->reading_time }}</span>
                                            @endif
                                            <span>{{ $article->views }} vues</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    
                    @if($favoriteArticles->hasPages())
                        <div class="mt-6">
                            {{ $favoriteArticles->appends(['news_page' => $favoriteNews->currentPage()])->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-8 bg-white rounded-lg shadow-sm">
                        <div class="p-3 rounded-full bg-gray-100 inline-block mb-3">
                            <i class="fas fa-bookmark text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-2">Aucun article favori</h3>
                        <p class="text-gray-500 text-sm mb-3">Commencez à sauvegarder des articles</p>
                        <a href="{{ url('/') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                            Découvrir
                        </a>
                    </div>
                @endif
            </div>

            <!-- News Column -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-newspaper text-red-600 mr-2"></i>
                        Actualités favorites ({{ $favoriteNews->total() }})
                    </h2>
                </div>
                
                @if($favoriteNews->count() > 0)
                    <div class="space-y-4">
                        @foreach($favoriteNews as $news)
                            <a href="{{ route('country.news.show', [$news->country->slug, $news->slug]) }}" 
                               class="block bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all hover:scale-[1.02] group">
                                <div class="p-5">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                                {{ ucfirst($news->category) }}
                                            </span>
                                            @if($news->is_featured)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-star mr-1"></i>
                                                    Une
                                                </span>
                                            @endif
                                        </div>
                                        <button onclick="event.preventDefault(); event.stopPropagation(); toggleFavorite('news', {{ $news->id }})" 
                                                id="favorite-btn-news-{{ $news->id }}"
                                                class="p-2 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                            <i class="fas fa-bookmark text-sm"></i>
                                        </button>
                                    </div>
                                    
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                        {{ $news->title }}
                                    </h3>
                                    
                                    @if($news->excerpt)
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $news->excerpt }}</p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center space-x-2">
                                            @if($news->country)
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $news->country->name_fr }}</span>
                                            @endif
                                            <span>{{ $news->published_at->format('d/m/Y') }}</span>
                                        </div>
                                        <span>{{ $news->views }} vues</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    
                    @if($favoriteNews->hasPages())
                        <div class="mt-6">
                            {{ $favoriteNews->appends(['articles_page' => $favoriteArticles->currentPage()])->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-8 bg-white rounded-lg shadow-sm">
                        <div class="p-3 rounded-full bg-gray-100 inline-block mb-3">
                            <i class="fas fa-newspaper text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-2">Aucune actualité favorite</h3>
                        <p class="text-gray-500 text-sm mb-3">Commencez à sauvegarder des actualités</p>
                        <a href="{{ url('/') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                            Découvrir
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection