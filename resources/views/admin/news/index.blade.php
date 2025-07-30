@extends('admin.layout')

@section('title', 'Gestion des Actualités')

@section('content')
<div class="space-y-6">
    <!-- Header avec actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gestion des Actualités</h1>
                <p class="text-gray-600 mt-2">{{ $news->total() }} actualité(s) au total</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.news.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle actualité
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" action="{{ route('admin.news') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="Titre ou contenu...">
                </div>

                <!-- Statut -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select id="status" name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Tous les statuts</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publiées</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillons</option>
                    </select>
                </div>

                <!-- Pays -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                    <select id="country" name="country" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Tous les pays</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>
                                {{ $country->emoji }} {{ $country->name_fr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Catégorie -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select id="category" name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Toutes les catégories</option>
                        <option value="administrative" {{ request('category') === 'administrative' ? 'selected' : '' }}>Administrative</option>
                        <option value="vie-pratique" {{ request('category') === 'vie-pratique' ? 'selected' : '' }}>Vie pratique</option>
                        <option value="culture" {{ request('category') === 'culture' ? 'selected' : '' }}>Culture</option>
                        <option value="economie" {{ request('category') === 'economie' ? 'selected' : '' }}>Économie</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Filtrer
                </button>
                <a href="{{ route('admin.news') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Actions en masse -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form id="bulk-form" method="POST" action="{{ route('admin.news.bulk') }}" onsubmit="return handleBulkAction('bulk-form')">
            @csrf
            <div class="flex items-center space-x-4">
                <select name="action" 
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Sélectionner une action...</option>
                    <option value="publish">Publier</option>
                    <option value="unpublish">Dépublier</option>
                    <option value="delete">Supprimer</option>
                </select>
                <button type="submit" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-200">
                    <i class="fas fa-check mr-2"></i>
                    Appliquer
                </button>
                <span class="text-sm text-gray-500">aux éléments sélectionnés</span>
            </div>

            <!-- Liste des actualités -->
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <input type="checkbox" onchange="toggleSelectAll(this, 'news-checkbox')" 
                                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actualité</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pays</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($news as $newsItem)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <input type="checkbox" name="news[]" value="{{ $newsItem->id }}" 
                                           class="news-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                </td>
                                <td class="px-4 py-4">
                                    <div>
                                        <a href="{{ route('admin.news.edit', $newsItem) }}" class="block hover:bg-gray-50 -m-2 p-2 rounded">
                                            <h3 class="text-sm font-medium text-gray-900 hover:text-purple-600 transition duration-200">{{ $newsItem->title }}</h3>
                                            <p class="text-sm text-gray-500">{{ Str::limit($newsItem->summary, 60) }}</p>
                                            @if($newsItem->is_featured)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                    <i class="fas fa-star mr-1"></i>
                                                    Featured
                                                </span>
                                            @endif
                                        </a>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $newsItem->author->name }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $newsItem->country->emoji }} {{ $newsItem->country->name_fr }}
                                </td>
                                <td class="px-4 py-4">
                                    @php
                                        $categoryColors = [
                                            'administrative' => 'bg-blue-100 text-blue-800',
                                            'vie-pratique' => 'bg-green-100 text-green-800',
                                            'culture' => 'bg-purple-100 text-purple-800',
                                            'economie' => 'bg-orange-100 text-orange-800',
                                        ];
                                        $colorClass = $categoryColors[$newsItem->category] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ ucfirst($newsItem->category) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($newsItem->is_published)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Publiée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-edit mr-1"></i>
                                            Brouillon
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500">
                                    {{ $newsItem->created_at->format('d/m/Y') }}
                                    <br>
                                    <span class="text-xs">{{ $newsItem->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.news.edit', $newsItem) }}" 
                                           class="text-purple-600 hover:text-purple-900 transition duration-200" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($newsItem->is_published)
                                            <a href="{{ route('country.news.show', [$newsItem->country->slug, $newsItem->slug]) }}" 
                                               target="_blank"
                                               class="text-green-600 hover:text-green-900 transition duration-200" 
                                               title="Voir">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        <button type="button" 
                                                onclick="deleteNews({{ $newsItem->id }})"
                                                class="text-red-600 hover:text-red-900 transition duration-200" 
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    <div>
                                        <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-lg font-medium">Aucune actualité trouvée</p>
                                        <p class="text-sm">Commencez par créer votre première actualité.</p>
                                        <a href="{{ route('admin.news.create') }}" 
                                           class="inline-flex items-center mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
                                            <i class="fas fa-plus mr-2"></i>
                                            Créer une actualité
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- Pagination -->
    @if($news->hasPages())
        <div class="bg-white rounded-xl shadow-lg p-6">
            {{ $news->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
function deleteNews(newsId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')) {
        // Create a form for the DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/news/${newsId}`;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Add method spoofing for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush