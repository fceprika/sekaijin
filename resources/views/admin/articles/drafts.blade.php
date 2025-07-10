@extends('admin.layout')

@section('title', 'Articles en attente de validation - Admin Sekaijin')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">📝 Articles en attente de validation</h2>
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                {{ $drafts->total() }} brouillon{{ $drafts->total() > 1 ? 's' : '' }}
            </span>
        </div>
        <p class="text-gray-600 text-sm mt-1">Validez les articles soumis par les membres de la communauté</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 m-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($drafts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Article
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Auteur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pays
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Catégorie
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date soumission
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($drafts as $article)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="max-w-sm">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $article->title }}
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ Str::limit($article->excerpt, 100) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($article->author->avatar)
                                        <img src="{{ $article->author->getAvatarUrl() }}" 
                                             alt="{{ $article->author->name }}" 
                                             class="w-8 h-8 rounded-full mr-2 object-cover">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs mr-2">
                                            {{ strtoupper(substr($article->author->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $article->author->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $article->author->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $article->created_at->format('d/m/Y H:i') }}
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $article->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('articles.preview', $article) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900">
                                        Prévisualiser
                                    </a>
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" 
                                       class="text-gray-600 hover:text-gray-900">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.articles.publish', $article) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir publier cet article ?')"
                                                class="text-green-600 hover:text-green-900">
                                            Publier
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $drafts->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun article en attente</h3>
            <p class="text-gray-500">Tous les articles ont été validés ! 🎉</p>
        </div>
    @endif
</div>

<!-- Info Box -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h3 class="text-lg font-medium text-blue-900 mb-2">Guide de validation</h3>
    <div class="text-sm text-blue-800 space-y-2">
        <p>• Vérifiez la qualité du contenu et sa pertinence pour la communauté</p>
        <p>• Assurez-vous qu'il n'y a pas de contenu inapproprié ou publicitaire</p>
        <p>• Vérifiez l'orthographe et la grammaire si nécessaire</p>
        <p>• Vous pouvez modifier l'article avant de le publier si besoin</p>
    </div>
</div>
@endsection