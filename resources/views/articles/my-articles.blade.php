@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Mes articles</h1>
                <p class="text-gray-600">Gérez vos articles et suivez leur statut de publication.</p>
            </div>
            <a href="{{ route('articles.create') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nouvel article
            </a>
        </div>
    </div>

    @if($articles->count() > 0)
        <div class="grid gap-6">
            @foreach($articles as $article)
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <h3 class="text-xl font-semibold text-gray-800 mr-3">{{ $article->title }}</h3>
                                
                                <!-- Status Badge -->
                                @if($article->is_published)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Publié
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-clock mr-1"></i>
                                        Brouillon
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-600 mb-4">{{ $article->excerpt }}</p>
                            
                            <div class="flex items-center text-sm text-gray-500 space-x-4">
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
                                        {{ $article->reading_time }} min
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 ml-6">
                            @if($article->is_published)
                                <a href="{{ route('country.article.show', [$article->country->slug, $article->slug]) }}" 
                                   class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-200 transition duration-200 text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Voir
                                </a>
                            @else
                                <a href="{{ route('articles.preview', $article) }}" 
                                   class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 transition duration-200 text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Aperçu
                                </a>
                            @endif

                            <a href="{{ route('articles.edit', $article) }}" 
                               class="bg-green-100 text-green-600 px-4 py-2 rounded-lg hover:bg-green-200 transition duration-200 text-sm">
                                <i class="fas fa-edit mr-1"></i>
                                Modifier
                            </a>

                            <form action="{{ route('articles.destroy', $article) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 transition duration-200 text-sm">
                                    <i class="fas fa-trash mr-1"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-file-alt text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun article</h3>
            <p class="text-gray-500 mb-6">Vous n'avez pas encore écrit d'article. Partagez votre expérience avec la communauté !</p>
            <a href="{{ route('articles.create') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Écrire mon premier article
            </a>
        </div>
    @endif
</div>
@endsection