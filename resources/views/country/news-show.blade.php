@extends('layout')

@section('title', $news->title . ' - Actualités ' . $currentCountry->name_fr)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8 text-sm">
            <ol class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('country.index', $currentCountry->slug) }}" class="hover:text-blue-600">{{ $currentCountry->name_fr }}</a></li>
                <li class="before:content-['/'] before:mx-2">
                    <a href="{{ route('country.actualites', $currentCountry->slug) }}" class="hover:text-blue-600">Actualités</a>
                </li>
                <li class="before:content-['/'] before:mx-2 text-gray-900 font-medium">{{ $news->title }}</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- News Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ ucfirst($news->category) }}
                    </span>
                    @if($news->is_featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i>
                            À la une
                        </span>
                    @endif
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $news->title }}</h1>
                
                @if($news->excerpt)
                    <p class="text-xl text-gray-600 mb-6">{{ $news->excerpt }}</p>
                @endif

                <!-- Author Info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-lg">
                                {{ strtoupper(substr($news->author->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <a href="{{ route('public.profile', $news->author->name) }}" class="text-lg font-medium text-gray-900 hover:text-blue-600">
                                {{ $news->author->name }}
                                @if($news->author->is_verified)
                                    <i class="fas fa-check-circle text-blue-500 ml-1"></i>
                                @endif
                            </a>
                            <p class="text-sm text-gray-500">
                                Publié le {{ $news->published_at->format('d F Y à H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Social Actions -->
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">{{ $news->views }} vues</span>
                        <button class="text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- News Content -->
            <div class="px-8 py-8">
                <div class="prose prose-lg max-w-none tinymce-content">
                    {!! $news->content !!}
                </div>
            </div>

            <!-- News Footer -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-share-alt"></i>
                            <span>Partager</span>
                        </button>
                        <button class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-bookmark"></i>
                            <span>Sauvegarder</span>
                        </button>
                    </div>
                    
                    <a href="{{ route('country.actualites', $currentCountry->slug) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        ← Retour aux actualités
                    </a>
                </div>
            </div>
        </div>

        <!-- Related News -->
        @if($relatedNews->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Actualités recommandées</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($relatedNews as $relatedNewsItem)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                {{ ucfirst($relatedNewsItem->category) }}
                            </span>
                            @if($relatedNewsItem->is_featured)
                                <i class="fas fa-star text-yellow-500 text-xs"></i>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('country.news.show', [$currentCountry->slug, $relatedNewsItem->id]) }}" class="hover:text-blue-600">
                                {{ $relatedNewsItem->title }}
                            </a>
                        </h3>
                        
                        @if($relatedNewsItem->excerpt)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $relatedNewsItem->excerpt }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Par {{ $relatedNewsItem->author->name }}</span>
                            <span>{{ $relatedNewsItem->published_at->format('d/m/Y') }}</span>
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