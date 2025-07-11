@extends('layout')

@section('title', $announcement->title . ' - ' . $countryModel->name_fr)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Accueil</a>
                </li>
                <li>
                    <span class="text-gray-400">/</span>
                </li>
                <li>
                    <a href="{{ route('country.index', $countryModel->slug) }}" class="text-gray-500 hover:text-gray-700 flex items-center">
                        <span class="mr-1">{{ $countryModel->emoji }}</span>
                        {{ $countryModel->name_fr }}
                    </a>
                </li>
                <li>
                    <span class="text-gray-400">/</span>
                </li>
                <li>
                    <a href="{{ route('country.annonces', $countryModel->slug) }}" class="text-gray-500 hover:text-gray-700">Annonces</a>
                </li>
                <li>
                    <span class="text-gray-400">/</span>
                </li>
                <li>
                    <span class="text-gray-900">{{ Str::limit($announcement->title, 30) }}</span>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Images -->
                    @php
                        // Traitement sécurisé des images
                        $rawImages = $announcement->images;
                        
                        if (is_string($rawImages)) {
                            $images = json_decode($rawImages, true) ?: [];
                        } elseif (is_array($rawImages)) {
                            $images = $rawImages;
                        } else {
                            $images = [];
                        }
                        
                        // Filtrer les éléments vides et réindexer
                        $images = array_values(array_filter($images, function($image) {
                            return !empty($image) && (is_string($image) || (is_array($image) && isset($image['path'])));
                        }));
                    @endphp
                    @if($images && count($images) > 0)
                        <div class="relative">
                            <div class="overflow-hidden rounded-t-lg">
                                <div id="image-carousel" class="relative">
                                    @foreach($images as $index => $image)
                                        @php
                                            $imagePath = is_string($image) ? $image : (is_array($image) && isset($image['path']) ? $image['path'] : '');
                                        @endphp
                                        @if($imagePath)
                                            <img src="{{ Storage::url($imagePath) }}" 
                                                 alt="{{ $announcement->title }} - Image {{ $index + 1 }}" 
                                                 class="w-full h-96 object-cover {{ $index > 0 ? 'hidden' : '' }}"
                                                 data-index="{{ $index }}">
                                        @endif
                                    @endforeach
                                </div>
                                
                                @if(count($images) > 1)
                                    <button id="prev-image" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button id="next-image" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Indicateurs -->
                                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                                        @foreach($images as $index => $image)
                                            <button class="image-indicator w-2 h-2 rounded-full {{ $index == 0 ? 'bg-white' : 'bg-white bg-opacity-50' }}" data-index="{{ $index }}"></button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            @if(count($images) > 1)
                                <div class="p-4 grid grid-cols-4 sm:grid-cols-6 gap-2">
                                    @foreach($images as $index => $image)
                                        @php
                                            $imagePath = is_string($image) ? $image : (is_array($image) && isset($image['path']) ? $image['path'] : '');
                                        @endphp
                                        @if($imagePath)
                                            <button class="thumbnail {{ $index == 0 ? 'ring-2 ring-blue-500' : '' }}" data-index="{{ $index }}">
                                                <img src="{{ Storage::url($imagePath) }}" 
                                                     alt="Miniature {{ $index + 1 }}" 
                                                     class="w-full h-16 object-cover rounded">
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Informations -->
                    <div class="p-6">
                        <!-- En-tête -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $announcement->type == 'vente' ? 'bg-blue-100 text-blue-800' : ($announcement->type == 'location' ? 'bg-green-100 text-green-800' : ($announcement->type == 'colocation' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ $announcement->type_display }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <span class="mr-1">{{ $countryModel->emoji }}</span>
                                        {{ $countryModel->name_fr }}
                                    </span>
                                    @if(!$announcement->isActive())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            {{ $announcement->status_display }}
                                        </span>
                                    @endif
                                </div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $announcement->title }}</h1>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900">{{ $announcement->formatted_price }}</p>
                                @if($announcement->type == 'location' || $announcement->type == 'colocation')
                                    <p class="text-sm text-gray-600">par mois</p>
                                @endif
                            </div>
                        </div>

                        <!-- Localisation -->
                        <div class="flex items-center text-gray-600 mb-6">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $announcement->city }}, {{ $announcement->country }}</span>
                            @if($announcement->address)
                                <span class="ml-1">- {{ $announcement->address }}</span>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="prose max-w-none mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">Description</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $announcement->description }}</p>
                        </div>

                        <!-- Métadonnées -->
                        <div class="flex items-center justify-between text-sm text-gray-500 pt-6 border-t">
                            <div class="flex items-center space-x-4">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ $announcement->views }} {{ $announcement->views > 1 ? 'vues' : 'vue' }}
                                </span>
                                <span>Publiée {{ $announcement->created_at->diffForHumans() }}</span>
                                @if($announcement->expiration_date)
                                    <span>Expire le {{ $announcement->expiration_date->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            
                            @if(Auth::check() && $announcement->canBeEditedBy(Auth::user()))
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('announcements.edit', $announcement) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Modifier
                                    </a>
                                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Annonces similaires -->
                @if($similarAnnouncements->isNotEmpty())
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Autres annonces {{ $announcement->type_display }} en {{ $countryModel->name_fr }}</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($similarAnnouncements as $similar)
                                <a href="{{ route('country.announcement.show', [$countryModel->slug, $similar]) }}" class="flex bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    @php
                                        $similarImages = is_array($similar->images) ? array_values(array_filter($similar->images)) : [];
                                    @endphp
                                    @if($similarImages && count($similarImages) > 0)
                                        <img src="{{ Storage::url($similarImages[0]) }}" alt="{{ $similar->title }}" class="w-32 h-32 object-cover rounded-l-lg">
                                    @else
                                        <div class="w-32 h-32 bg-gray-200 rounded-l-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="p-4 flex-1">
                                        <h3 class="font-medium text-gray-900 line-clamp-1">{{ $similar->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $similar->city }}</p>
                                        <p class="text-lg font-semibold text-gray-900 mt-2">{{ $similar->formatted_price }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Vendeur -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Annonceur</h2>
                    
                    <div class="flex items-center mb-4">
                        @if($announcement->user->avatar)
                            <img src="{{ Storage::url('avatars/' . $announcement->user->avatar) }}" alt="{{ $announcement->user->name }}" class="h-12 w-12 rounded-full mr-3 border-2 border-blue-500">
                        @else
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg font-bold mr-3 border-2 border-blue-500">
                                {{ substr($announcement->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('public.profile', $announcement->user->name) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                {{ $announcement->user->name }}
                            </a>
                            <p class="text-sm text-gray-600">
                                Membre depuis {{ $announcement->user->created_at->format('F Y') }}
                            </p>
                        </div>
                    </div>

                    @auth
                        @if(Auth::id() !== $announcement->user_id)
                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-medium" onclick="contactSeller()">
                                Contacter l'annonceur
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-medium">
                            Connectez-vous pour contacter
                        </a>
                    @endauth
                </div>

                <!-- Retour aux annonces du pays -->
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <span class="mr-2">{{ $countryModel->emoji }}</span>
                        Annonces {{ $countryModel->name_fr }}
                    </h3>
                    <p class="text-sm text-blue-800 mb-3">Découvrez toutes les autres annonces disponibles dans ce pays</p>
                    <a href="{{ route('country.annonces', $countryModel->slug) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Voir toutes les annonces
                    </a>
                </div>

                <!-- Conseils de sécurité -->
                <div class="bg-yellow-50 rounded-lg p-6">
                    <h3 class="font-semibold text-yellow-900 mb-2">Conseils de sécurité</h3>
                    <ul class="text-sm text-yellow-800 space-y-2">
                        <li class="flex items-start">
                            <svg class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Rencontrez toujours le vendeur dans un lieu public</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Ne payez jamais avant d'avoir vu l'article</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Méfiez-vous des prix trop attractifs</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if($images && count($images) > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentImageIndex = 0;
    const images = document.querySelectorAll('#image-carousel img');
    const indicators = document.querySelectorAll('.image-indicator');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    function showImage(index) {
        // Cacher toutes les images
        images.forEach(img => img.classList.add('hidden'));
        // Afficher l'image sélectionnée
        images[index].classList.remove('hidden');
        
        // Mettre à jour les indicateurs
        indicators.forEach((indicator, i) => {
            if (i === index) {
                indicator.classList.remove('bg-opacity-50');
            } else {
                indicator.classList.add('bg-opacity-50');
            }
        });
        
        // Mettre à jour les miniatures
        thumbnails.forEach((thumbnail, i) => {
            if (i === index) {
                thumbnail.classList.add('ring-2', 'ring-blue-500');
            } else {
                thumbnail.classList.remove('ring-2', 'ring-blue-500');
            }
        });
        
        currentImageIndex = index;
    }
    
    // Navigation avec les boutons
    document.getElementById('prev-image')?.addEventListener('click', function() {
        const newIndex = currentImageIndex === 0 ? images.length - 1 : currentImageIndex - 1;
        showImage(newIndex);
    });
    
    document.getElementById('next-image')?.addEventListener('click', function() {
        const newIndex = currentImageIndex === images.length - 1 ? 0 : currentImageIndex + 1;
        showImage(newIndex);
    });
    
    // Click sur les indicateurs
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function() {
            showImage(index);
        });
    });
    
    // Click sur les miniatures
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            showImage(index);
        });
    });
});
</script>
@endif

<script>
function contactSeller() {
    // Ici, vous pourriez implémenter un système de messagerie interne
    // Pour l'instant, on affiche juste une alerte
    alert('Fonctionnalité de messagerie à venir. En attendant, utilisez les moyens de contact fournis par l\'annonceur.');
}
</script>
@endsection