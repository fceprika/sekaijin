@extends('layout')

@section('title', 'Prévisualisation: ' . $article->title)

@section('content')
<!-- Preview Header -->
<div class="bg-yellow-50 border-b border-yellow-200">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <span class="text-yellow-800 font-medium">Mode Prévisualisation</span>
                <span class="text-yellow-600 text-sm">Cet article n'est pas encore publié</span>
            </div>
            <button onclick="window.close()" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                ✕ Fermer
            </button>
        </div>
    </div>
</div>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8 text-sm">
            <ol class="flex items-center flex-wrap gap-x-2 gap-y-1 text-gray-500">
                <li><a href="/" class="hover:text-blue-600 whitespace-nowrap">{{ $article->country->name_fr ?? 'Global' }}</a></li>
                <li class="before:content-['/'] before:mx-2 flex items-center">
                    <a href="#" class="hover:text-blue-600 whitespace-nowrap">Blog</a>
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
                        {{ ucfirst($article->category ?? 'Témoignage') }}
                    </span>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span>{{ $article->views ?? 0 }} vues</span>
                        @if($article->reading_time)
                            <span>{{ $article->reading_time }}</span>
                        @endif
                    </div>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>
                
                @if($article->excerpt)
                    <p class="text-xl text-gray-600 mb-6">{{ $article->excerpt }}</p>
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
                            <div class="text-lg font-medium text-gray-900">
                                {{ $article->author->name }}
                                @if($article->author->is_verified)
                                    <i class="fas fa-check-circle text-blue-500 ml-1"></i>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">
                                Publié le {{ $article->published_at ? $article->published_at->format('d F Y') : 'Non publié' }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Social Actions -->
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-red-600 transition-colors">
                            <i class="far fa-heart"></i>
                            <span>{{ $article->likes ?? 0 }}</span>
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
                            <span>J'aime ({{ $article->likes ?? 0 }})</span>
                        </button>
                        <button class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-share-alt"></i>
                            <span>Partager</span>
                        </button>
                    </div>
                    
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour au blog
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection