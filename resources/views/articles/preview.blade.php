@extends('layout')

@section('title', $article->title . ' (Prévisualisation) - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Preview Banner -->
    <div class="bg-yellow-100 border-b border-yellow-200">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="text-yellow-800 font-medium">Mode prévisualisation</span>
                    @if(!$article->is_published)
                        <span class="bg-yellow-200 text-yellow-900 px-2 py-1 rounded text-xs font-medium">Brouillon</span>
                    @endif
                </div>
                <a href="{{ route('articles.edit', $article) }}" 
                   class="text-yellow-800 hover:text-yellow-900 font-medium text-sm">
                    ← Retour à l'édition
                </a>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <article class="max-w-4xl mx-auto px-4 py-12">
        <!-- Header -->
        <header class="mb-8">
            <!-- Category Badge -->
            <div class="mb-4">
                @php
                    $categoryColors = [
                        'témoignage' => 'bg-purple-100 text-purple-800',
                        'guide-pratique' => 'bg-green-100 text-green-800',
                        'travail' => 'bg-blue-100 text-blue-800',
                        'lifestyle' => 'bg-yellow-100 text-yellow-800',
                        'cuisine' => 'bg-orange-100 text-orange-800'
                    ];
                    $categoryColor = $categoryColors[$article->category] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $categoryColor }}">
                    {{ ucfirst($article->category) }}
                </span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

            <!-- Meta Information -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                <!-- Author -->
                <div class="flex items-center">
                    @if($article->author->avatar)
                        <img src="{{ $article->author->getAvatarUrl() }}" 
                             alt="{{ $article->author->name }}" 
                             class="w-8 h-8 rounded-full mr-2 object-cover">
                    @else
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-2">
                            {{ strtoupper(substr($article->author->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="font-medium">{{ $article->author->name }}</span>
                </div>

                <!-- Country -->
                <div class="flex items-center">
                    <span>{{ $article->country->emoji }} {{ $article->country->name_fr }}</span>
                </div>

                <!-- Date -->
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $article->created_at->format('d F Y') }}</span>
                </div>

                <!-- Reading Time -->
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $article->reading_time }} min de lecture</span>
                </div>
            </div>
        </header>

        <!-- Excerpt -->
        <div class="bg-gray-100 rounded-xl p-6 mb-8">
            <p class="text-lg text-gray-700 leading-relaxed">{{ $article->excerpt }}</p>
        </div>

        <!-- Content -->
        <div class="prose prose-lg max-w-none">
            {!! nl2br(e($article->content)) !!}
        </div>

        <!-- Article Footer -->
        <footer class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <!-- Like Button (Disabled in preview) -->
                <button disabled class="flex items-center space-x-2 text-gray-400 cursor-not-allowed">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span>{{ $article->likes ?? 0 }} J'aime</span>
                </button>

                <!-- Share Buttons (Disabled in preview) -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600 text-sm mr-2">Partager :</span>
                    <button disabled class="text-gray-400 cursor-not-allowed">
                        <i class="fab fa-facebook text-xl"></i>
                    </button>
                    <button disabled class="text-gray-400 cursor-not-allowed">
                        <i class="fab fa-twitter text-xl"></i>
                    </button>
                    <button disabled class="text-gray-400 cursor-not-allowed">
                        <i class="fab fa-linkedin text-xl"></i>
                    </button>
                    <button disabled class="text-gray-400 cursor-not-allowed">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </button>
                </div>
            </div>
        </footer>
    </article>

    <!-- Related Articles Section (Example) -->
    <section class="bg-gray-100 py-12 mt-12">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Articles similaires</h2>
            <p class="text-gray-600">Les articles similaires apparaîtront ici une fois l'article publié.</p>
        </div>
    </section>
</div>
@endsection