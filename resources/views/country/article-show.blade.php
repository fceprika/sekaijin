@extends('layout')

@section('title', $article->title . ' - Blog ' . $currentCountry->name_fr)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8 text-sm">
            <ol class="flex items-center flex-wrap gap-x-2 gap-y-1 text-gray-500">
                <li><a href="{{ route('country.index', $currentCountry->slug) }}" class="hover:text-blue-600 whitespace-nowrap">{{ $currentCountry->name_fr }}</a></li>
                <li class="before:content-['/'] before:mx-2 flex items-center">
                    <a href="{{ route('country.blog', $currentCountry->slug) }}" class="hover:text-blue-600 whitespace-nowrap">Blog</a>
                </li>
                <li class="before:content-['/'] before:mx-2 text-gray-900 font-medium flex items-center">
                    <span class="truncate max-w-[200px] sm:max-w-none">{{ $article->title }}</span>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Article Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($article->category) }}
                    </span>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span>{{ $article->views }} vues</span>
                        @if($article->reading_time)
                            <span>{{ $article->reading_time }}</span>
                        @endif
                    </div>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>
                
                @if($article->excerpt)
                    <p class="text-xl text-gray-600 mb-6">{{ $article->excerpt }}</p>
                @endif

                <!-- Article Image -->
                @if($article->image_url)
                    <div class="mb-6">
                        <img src="{{ $article->image_url }}" 
                             alt="{{ $article->title }}" 
                             class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-lg shadow-sm">
                    </div>
                @endif

                <!-- Author Info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if($article->author->avatar)
                            <img src="{{ asset('storage/avatars/' . $article->author->avatar) }}" 
                                 alt="Avatar de {{ $article->author->name }}" 
                                 class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">
                                    {{ strtoupper(substr($article->author->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <a href="{{ $article->author->getPublicProfileUrl() }}" class="text-lg font-medium text-gray-900 hover:text-blue-600">
                                {{ $article->author->name }}
                                @if($article->author->is_verified)
                                    <i class="fas fa-check-circle text-blue-500 ml-1"></i>
                                @endif
                            </a>
                            <p class="text-sm text-gray-500">
                                Publié le {{ $article->published_at->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Social Actions -->
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-red-600 transition-colors">
                            <i class="far fa-heart"></i>
                            <span>{{ $article->likes }}</span>
                        </button>
                        <button class="text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Article Content -->
            <div class="px-8 py-8">
                <div class="prose prose-lg max-w-none tinymce-content">
                    {!! $article->content !!}
                </div>
            </div>

            <!-- Article Footer -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="far fa-heart"></i>
                            <span>J'aime ({{ $article->likes }})</span>
                        </button>
                        <button class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-share-alt"></i>
                            <span>Partager</span>
                        </button>
                    </div>
                    
                    <a href="{{ route('country.blog', $currentCountry->slug) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour au blog
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Articles -->
        @if($relatedArticles->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Articles recommandés</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $relatedArticle)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($relatedArticle->category) }}
                            </span>
                            @if($relatedArticle->reading_time)
                                <span class="text-xs text-gray-500">{{ $relatedArticle->reading_time }}</span>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('country.article.show', [$currentCountry->slug, $relatedArticle->slug]) }}" class="hover:text-blue-600">
                                {{ $relatedArticle->title }}
                            </a>
                        </h3>
                        
                        @if($relatedArticle->excerpt)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $relatedArticle->excerpt }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Par {{ $relatedArticle->author->name }}</span>
                            <span>{{ $relatedArticle->published_at->format('d/m/Y') }}</span>
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