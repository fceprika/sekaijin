@extends('admin.layout')

@section('title', 'Gestion des annonces')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
            <div class="py-6 md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:leading-9 sm:truncate">
                            Gestion des annonces
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['pending'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Actives</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['active'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Refusées</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['refused'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="p-6">
                <form action="{{ route('admin.announcements') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Rechercher dans les annonces..." 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <select name="status" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                            <option value="refused" {{ request('status') == 'refused' ? 'selected' : '' }}>Refusées</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Filtrer
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.announcements') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Liste des annonces -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @if($announcements->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune annonce trouvée</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request()->hasAny(['search', 'status']))
                            Essayez de modifier vos critères de recherche.
                        @else
                            Aucune annonce n'a été publiée pour le moment.
                        @endif
                    </p>
                </div>
            @else
                <form id="bulk-form" action="{{ route('admin.announcements.bulk-action') }}" method="POST">
                    @csrf
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Annonce
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Annonceur
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($announcements as $announcement)
                                <tr class="{{ $announcement->status == 'pending' ? 'bg-yellow-50' : '' }}">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="announcement_ids[]" value="{{ $announcement->id }}" 
                                               class="announcement-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @php
                                                $images = is_array($announcement->images) ? array_values(array_filter($announcement->images)) : [];
                                            @endphp
                                            @if($images && count($images) > 0)
                                                <img src="{{ Storage::url($images[0]) }}" alt="{{ $announcement->title }}" class="h-10 w-10 rounded object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('admin.announcements.show', $announcement) }}" class="hover:text-blue-600">
                                                        {{ $announcement->title }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $announcement->city }}, {{ $announcement->country }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($announcement->user->avatar)
                                                <img src="{{ Storage::url('avatars/' . $announcement->user->avatar) }}" alt="{{ $announcement->user->name }}" class="h-6 w-6 rounded-full mr-2 border border-blue-500">
                                            @else
                                                <div class="h-6 w-6 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold mr-2 border border-blue-500">
                                                    {{ substr($announcement->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="text-sm text-gray-900">{{ $announcement->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->type == 'vente' ? 'bg-blue-100 text-blue-800' : ($announcement->type == 'location' ? 'bg-green-100 text-green-800' : ($announcement->type == 'colocation' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ $announcement->type_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->status == 'active' ? 'bg-green-100 text-green-800' : ($announcement->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $announcement->status_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $announcement->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.announcements.show', $announcement) }}" class="text-blue-600 hover:text-blue-900">
                                                Voir
                                            </a>
                                            @if($announcement->status == 'pending')
                                                <form action="{{ route('admin.announcements.approve', $announcement) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Approuver cette annonce ?')">
                                                        Approuver
                                                    </button>
                                                </form>
                                                <button onclick="showRefuseModal({{ $announcement->id }})" class="text-red-600 hover:text-red-900">
                                                    Refuser
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Actions en lot -->
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200" style="display: none;" id="bulk-actions">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">
                                <span id="selected-count">0</span> annonce(s) sélectionnée(s)
                            </span>
                            <div class="flex space-x-2">
                                <button type="button" onclick="bulkAction('approve')" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                    Approuver
                                </button>
                                <button type="button" onclick="bulkAction('refuse')" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                    Refuser
                                </button>
                                <button type="button" onclick="bulkAction('delete')" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $announcements->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de refus -->
<div id="refuse-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Refuser l'annonce</h3>
            <form id="refuse-form" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Raison du refus (optionnel)</label>
                    <textarea name="reason" id="reason" rows="3" 
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Expliquez pourquoi cette annonce est refusée..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideRefuseModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Refuser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.announcement-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.announcement-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = checkedBoxes.length;
        } else {
            bulkActions.style.display = 'none';
        }
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    window.bulkAction = function(action) {
        const checkedBoxes = document.querySelectorAll('.announcement-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Veuillez sélectionner au moins une annonce.');
            return;
        }

        const actionNames = {
            'approve': 'approuver',
            'refuse': 'refuser',
            'delete': 'supprimer'
        };

        if (confirm(`Êtes-vous sûr de vouloir ${actionNames[action]} les annonces sélectionnées ?`)) {
            // Supprimer les anciens inputs cachés s'ils existent
            const existingInputs = bulkForm.querySelectorAll('input[name="action"], input[name="announcement_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            // Ajouter l'action
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            bulkForm.appendChild(actionInput);
            
            // Ajouter les IDs des annonces sélectionnées
            checkedBoxes.forEach(checkbox => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'announcement_ids[]';
                idInput.value = checkbox.value;
                bulkForm.appendChild(idInput);
            });
            
            bulkForm.submit();
        }
    };

    window.showRefuseModal = function(announcementId) {
        const modal = document.getElementById('refuse-modal');
        const form = document.getElementById('refuse-form');
        form.action = '{{ route("admin.announcements.refuse", ":id") }}'.replace(':id', announcementId);
        modal.classList.remove('hidden');
    };

    window.hideRefuseModal = function() {
        const modal = document.getElementById('refuse-modal');
        modal.classList.add('hidden');
        document.getElementById('reason').value = '';
    };
});
</script>
@endsection