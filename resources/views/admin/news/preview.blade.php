@extends('layout')

@section('title', 'Prévisualisation: ' . $news->title)

@section('content')
<!-- Preview Header -->
<div class="bg-yellow-50 border-b border-yellow-200">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <span class="text-yellow-800 font-medium">Mode Prévisualisation</span>
                <span class="text-yellow-600 text-sm">Cette actualité n'est pas encore publiée</span>
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
                <li><a href="/" class="hover:text-blue-600 whitespace-nowrap">{{ $news->country->name_fr ?? 'Global' }}</a></li>
                <li class="before:content-['/'] before:mx-2 flex items-center">
                    <a href="#" class="hover:text-blue-600 whitespace-nowrap">Actualités</a>
                </li>
                <li class="before:content-['/'] before:mx-2 text-gray-900 font-medium flex items-center">
                    <span class="truncate max-w-[200px] sm:max-w-none">{{ $news->title }}</span>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- News Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ ucfirst($news->category ?? 'Administrative') }}
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
                        @if($news->author->avatar)
                            <img src="{{ asset('storage/avatars/' . $news->author->avatar) }}" 
                                 alt="Avatar de {{ $news->author->name }}" 
                                 class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">
                                    {{ strtoupper(substr($news->author->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <div class="text-lg font-medium text-gray-900">
                                {{ $news->author->name }}
                                @if($news->author->is_verified)
                                    <i class="fas fa-check-circle text-blue-500 ml-1"></i>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">
                                Publié le {{ $news->published_at ? $news->published_at->format('d F Y à H:i') : 'Non publié' }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Social Actions -->
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- News Content -->
            <div class="px-8 py-8">
                <div class="prose prose-lg max-w-4xl tinymce-content">
                    {!! $news->content !!}
                </div>
            </div>

            <!-- News Footer -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
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
                    
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour aux actualités
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection