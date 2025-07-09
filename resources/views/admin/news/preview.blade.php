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

<!-- Content -->
<div class="bg-gray-50 min-h-screen">
    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4">
        <nav class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="/" class="hover:text-blue-600">{{ $news->country->name_fr ?? 'Global' }}</a>
            <span>/</span>
            <a href="#" class="hover:text-blue-600">Actualités</a>
            <span>/</span>
            <span class="font-medium">{{ $news->title }}</span>
        </nav>
    </div>

    <!-- News Content -->
    <div class="container mx-auto px-4 pb-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- News Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                        @if($news->category === 'administrative') bg-blue-100 text-blue-800
                        @elseif($news->category === 'vie-pratique') bg-green-100 text-green-800
                        @elseif($news->category === 'culture') bg-purple-100 text-purple-800
                        @elseif($news->category === 'economie') bg-orange-100 text-orange-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ ucfirst($news->category ?? 'Actualité') }}
                    </span>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span>{{ $news->views ?? 0 }} vues</span>
                        @if($news->is_featured)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                À la une
                            </span>
                        @endif
                    </div>
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
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
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
                                Publié le {{ $news->published_at ? $news->published_at->format('d F Y') : 'Non publié' }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Social Actions -->
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">{{ $news->likes ?? 0 }}</span>
                        </button>
                        <button class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Partager</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- News Image -->
            @if($news->image_url)
                <div class="px-8 py-6">
                    <img src="{{ $news->image_url }}" 
                         alt="{{ $news->title }}" 
                         class="w-full h-64 lg:h-96 object-cover rounded-lg">
                </div>
            @endif

            <!-- News Content -->
            <div class="px-8 py-6">
                <div class="prose prose-lg max-w-none">
                    {!! $news->content !!}
                </div>
            </div>

            <!-- News Footer -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-1 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>{{ $news->views ?? 0 }} vues</span>
                        </div>
                        @if($news->is_featured)
                            <div class="flex items-center space-x-1 text-yellow-600">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <span>À la une</span>
                            </div>
                        @endif
                    </div>

                    <!-- Share Section -->
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Partager sur :</span>
                        <div class="flex items-center space-x-2">
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tailwind CSS Prose Styles -->
<style>
    .prose {
        @apply text-gray-700 leading-relaxed;
    }
    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
        @apply text-gray-900 font-bold mt-8 mb-4;
    }
    .prose h1 { @apply text-3xl; }
    .prose h2 { @apply text-2xl; }
    .prose h3 { @apply text-xl; }
    .prose h4 { @apply text-lg; }
    .prose p { @apply mb-4; }
    .prose ul, .prose ol { @apply mb-4 pl-6; }
    .prose li { @apply mb-2; }
    .prose blockquote { @apply border-l-4 border-blue-500 pl-4 italic text-gray-600 my-4; }
    .prose a { @apply text-blue-600 hover:text-blue-800 underline; }
    .prose img { @apply rounded-lg shadow-md my-6; }
    .prose code { @apply bg-gray-100 px-2 py-1 rounded text-sm font-mono; }
    .prose pre { @apply bg-gray-100 p-4 rounded-lg overflow-x-auto my-4; }
    .prose table { @apply border-collapse border border-gray-300 my-4; }
    .prose th, .prose td { @apply border border-gray-300 px-4 py-2; }
    .prose th { @apply bg-gray-50 font-bold; }
</style>
@endsection