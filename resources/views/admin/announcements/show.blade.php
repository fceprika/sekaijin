@extends('admin.layout')

@section('title', 'Détail de l\'annonce')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
            <div class="py-6 md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <a href="{{ route('admin.announcements') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:leading-9 sm:truncate">
                            Détail de l'annonce
                        </h1>
                        <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $announcement->status == 'active' ? 'bg-green-100 text-green-800' : ($announcement->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $announcement->status_display }}
                        </span>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                    @if($announcement->status == 'pending')
                        <form action="{{ route('admin.announcements.approve', $announcement) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700" onclick="return confirm('Approuver cette annonce ?')">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approuver
                            </button>
                        </form>
                        <button onclick="showRefuseModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Refuser
                        </button>
                    @endif
                    @if($announcement->status == 'active')
                        <a href="{{ route('announcements.show', $announcement) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Voir en ligne
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Images -->
                    @php
                        $images = is_array($announcement->images) ? array_values(array_filter($announcement->images)) : [];
                    @endphp
                    @if($images && count($images) > 0)
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Images ({{ count($images) }})</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($images as $image)
                                    <img src="{{ Storage::url($image) }}" alt="Image de l'annonce" class="w-full h-32 object-cover rounded-lg">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Informations principales -->
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations principales</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Titre</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $announcement->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->type == 'vente' ? 'bg-blue-100 text-blue-800' : ($announcement->type == 'location' ? 'bg-green-100 text-green-800' : ($announcement->type == 'colocation' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ $announcement->type_display }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Prix</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $announcement->formatted_price }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Localisation</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $announcement->city }}, {{ $announcement->country }}
                                    @if($announcement->address)
                                        <br>{{ $announcement->address }}
                                    @endif
                                </dd>
                            </div>
                            @if($announcement->expiration_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date d'expiration</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $announcement->expiration_date->format('d/m/Y') }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Vues</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $announcement->views }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Description -->
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">{{ $announcement->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Informations sur l'annonceur -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Annonceur</h3>
                    <div class="flex items-center">
                        @if($announcement->user->avatar)
                            <img src="{{ Storage::url('avatars/' . $announcement->user->avatar) }}" alt="{{ $announcement->user->name }}" class="h-12 w-12 rounded-full mr-3 border-2 border-blue-500">
                        @else
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg font-bold mr-3 border-2 border-blue-500">
                                {{ substr($announcement->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $announcement->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $announcement->user->email }}</p>
                            @if($announcement->user->country_residence)
                                <p class="text-sm text-gray-600">{{ $announcement->user->country_residence }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600">
                            Membre depuis {{ $announcement->user->created_at->format('F Y') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Rôle : {{ $announcement->user->getRoleDisplayName() }}
                        </p>
                        <a href="{{ route('public.profile', $announcement->user->name) }}" target="_blank" class="mt-2 inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                            Voir le profil public
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Métadonnées -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Métadonnées</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $announcement->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900 break-all">{{ $announcement->slug }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Créée le</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $announcement->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Modifiée le</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $announcement->updated_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions admin -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions admin</h3>
                    <div class="space-y-3">
                        @if($announcement->status == 'pending')
                            <form action="{{ route('admin.announcements.approve', $announcement) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700" onclick="return confirm('Approuver cette annonce ?')">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approuver l'annonce
                                </button>
                            </form>
                            <button onclick="showRefuseModal()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Refuser l'annonce
                            </button>
                        @endif
                        @if($announcement->status == 'active')
                            <a href="{{ route('announcements.show', $announcement) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Voir l'annonce en ligne
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de refus -->
<div id="refuse-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Refuser l'annonce</h3>
            <form action="{{ route('admin.announcements.refuse', $announcement) }}" method="POST">
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
function showRefuseModal() {
    document.getElementById('refuse-modal').classList.remove('hidden');
}

function hideRefuseModal() {
    document.getElementById('refuse-modal').classList.add('hidden');
    document.getElementById('reason').value = '';
}
</script>
@endsection