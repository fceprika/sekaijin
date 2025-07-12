@extends('layout')

@section('title', 'Mes articles - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Mes articles</h1>
                    <p class="text-gray-600">Gérez vos articles publiés et brouillons</p>
                </div>
                <a href="{{ route('articles.create') }}" 
                   class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Écrire un nouvel article
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Articles List -->
        @if($articles->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Article
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pays
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catégorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vues
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($articles as $article)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="max-w-md">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $article->title }}
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ Str::limit($article->excerpt, 80) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">
                                            {{ $article->country->emoji }} {{ $article->country->name_fr }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $categoryColor }}">
                                            {{ ucfirst($article->category) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($article->is_published)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Publié
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Brouillon
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $article->views ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $article->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('articles.edit', $article) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                Modifier
                                            </a>
                                            @if($article->is_published)
                                                <a href="{{ route('country.article.show', [$article->country->slug, $article->slug]) }}" 
                                                   target="_blank"
                                                   class="text-green-600 hover:text-green-900">
                                                    Voir
                                                </a>
                                            @else
                                                <a href="{{ route('articles.preview', $article) }}" 
                                                   target="_blank"
                                                   class="text-gray-600 hover:text-gray-900">
                                                    Prévisualiser
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $articles->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 mb-6">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun article pour le moment</h3>
                <p class="text-gray-600 mb-6">
                    Commencez à partager votre expérience d'expatrié avec la communauté !
                </p>
                <a href="{{ route('articles.create') }}" 
                   class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Écrire mon premier article
                </a>
            </div>
        @endif

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-2">Comment ça marche ?</h3>
            <div class="text-sm text-blue-800 space-y-2">
                <p>• Écrivez et sauvegardez vos articles en mode brouillon</p>
                <p>• Prévisualisez votre article avant de le soumettre</p>
                <p>• Un administrateur validera votre article avant publication</p>
                <p>• Une fois publié, votre article sera visible par toute la communauté</p>
            </div>
        </div>
    </div>
</div>
@endsection