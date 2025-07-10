@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Header avec badge brouillon -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Mode aperçu</strong> - Cet article est en mode brouillon et n'est pas encore publié.
                </p>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <article class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $article->title }}</h1>
            
            <!-- Meta info -->
            <div class="flex items-center text-sm text-gray-600 space-x-6 mb-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-blue-600 mr-3">
                        <img src="{{ $article->author->getAvatarUrl() }}" 
                             alt="Avatar de {{ $article->author->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <span>{{ $article->author->name }}</span>
                </div>
                
                <span>
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    {{ $article->country->emoji ?? '' }} {{ $article->country->name_fr ?? 'Non défini' }}
                </span>
                
                <span>
                    <i class="fas fa-tag mr-1"></i>
                    {{ ucfirst($article->category) }}
                </span>
                
                <span>
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $article->created_at->format('d/m/Y') }}
                </span>
                
                @if($article->reading_time)
                    <span>
                        <i class="fas fa-clock mr-1"></i>
                        {{ $article->reading_time }} min de lecture
                    </span>
                @endif
            </div>

            <!-- Excerpt -->
            @if($article->excerpt)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-lg">
                    <p class="text-blue-800 italic">{{ $article->excerpt }}</p>
                </div>
            @endif
        </header>

        <!-- Content -->
        <div class="prose prose-lg max-w-none">
            {!! nl2br(e($article->content)) !!}
        </div>
    </article>

    <!-- Actions -->
    <div class="mt-8 flex items-center justify-between">
        <a href="{{ route('articles.my-articles') }}" 
           class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux articles
        </a>
        
        <div class="flex space-x-4">
            <a href="{{ route('articles.edit', $article) }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
        </div>
    </div>
</div>
@endsection