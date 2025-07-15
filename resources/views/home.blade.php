@extends('layout')

@section('title', 'Accueil - Sekaijin')

@section('content')
<!-- Hero Section with Wallpaper -->
<div class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-24 relative overflow-hidden cursor-pointer" style="background-image: url('/images/wallpaper_sekaijin.webp'); background-size: cover; background-position: center; background-repeat: no-repeat;" onclick="window.location.href='/thailande'">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
            Bienvenue sur 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-pink-400">
                Sekaijin
            </span>
        </h1>
        <p class="text-xl md:text-2xl mb-10 text-blue-100 max-w-3xl mx-auto">
            Rejoignez la communauté française la plus active de Thaïlande ! 
            <span class="block mt-2 text-lg md:text-xl text-yellow-300 font-semibold">
                🇹🇭 Plus de {{ $thailandMembers }} compatriotes vous attendent
            </span>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center items-center">
            @guest
                <a href="/inscription" id="hero-btn" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transform hover:scale-105 transition duration-300 shadow-lg w-full sm:w-auto z-10 relative" onclick="event.stopPropagation();">
                    👥 Rejoindre la communauté
                </a>
            @endguest
            <a href="/thailande" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-600 transition duration-300 inline-block w-full sm:w-auto text-center z-10 relative" onclick="event.stopPropagation()">
                Découvrir la Thaïlande
            </a>
        </div>
    </div>
    <!-- Floating shapes -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-yellow-400 rounded-full opacity-20 animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-16 h-16 bg-pink-400 rounded-full opacity-20 animate-bounce"></div>
</div>

<!-- Interactive Map Section -->
<div class="bg-white">
    <div id="map" class="h-[400px] md:h-[500px] lg:h-[600px] w-full"></div>
</div>

<!-- Thailand Focus Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">🇹🇭 Communauté Thaïlande</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Découvrez l'actualité, les événements et les discussions de votre communauté en Thaïlande
            </p>
        </div>
        
        @include('partials.country-content', [
            'country' => $thailand,
            'news' => $thailandNews,
            'articles' => $thailandArticles,
            'events' => $thailandEvents,
            'totalMembers' => $totalMembers,
            'thailandMembers' => $thailandMembers,
            'isLast' => true
        ])
    </div>
</div>

<!-- Features Section -->
<div class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Pourquoi rejoindre Sekaijin ?</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Découvrez ce qui fait de Sekaijin la communauté incontournable des expatriés français
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center group">
                <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">Communauté Active</h3>
                <p class="text-gray-600 leading-relaxed">
                    Connectez-vous avec des milliers d'expatriés français partageant vos expériences.
                </p>
            </div>
            
            <div class="text-center group">
                <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">Conseils Pratiques</h3>
                <p class="text-gray-600 leading-relaxed">
                    Accédez à des guides et conseils pour faciliter votre vie d'expatrié.
                </p>
            </div>
            
            <div class="text-center group">
                <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">Réseau Mondial</h3>
                <p class="text-gray-600 leading-relaxed">
                    Présents dans plus de 150 pays, trouvez des compatriotes près de chez vous.
                </p>
            </div>
        </div>
    </div>

<!-- Real Stats Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Notre Communauté en Chiffres</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold text-blue-600 mb-2">{{ $totalMembers }}</div>
                <div class="text-gray-600">Membres Inscrits</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-green-600 mb-2">{{ $thailandMembers }}</div>
                <div class="text-gray-600">En Thaïlande</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-purple-600 mb-2">{{ $thailandNews->count() + $thailandArticles->count() }}</div>
                <div class="text-gray-600">Publications</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $thailandEvents->count() }}</div>
                <div class="text-gray-600">Événements</div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Members Section -->
@if($recentMembers->count() > 0)
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">👋 Nouveaux Membres</h2>
            <p class="text-xl text-gray-600">Accueillez les derniers arrivants de la communauté</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($recentMembers as $member)
            <a href="{{ route('public.profile', $member->name) }}" class="block bg-white rounded-xl p-6 text-center shadow-sm hover:shadow-md transition duration-300 cursor-pointer transform hover:scale-105">
                @if($member->avatar)
                    <div class="w-16 h-16 mx-auto mb-4">
                        <img src="{{ asset('storage/avatars/' . $member->avatar) }}" 
                             alt="Photo de {{ $member->name }}" 
                             class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                    </div>
                @else
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xl mx-auto mb-4">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                @endif
                <h3 class="font-semibold text-gray-800 mb-2">{{ $member->name }}</h3>
                <p class="text-gray-600 text-sm mb-3">📍 {{ $member->country_residence }}</p>
                @if($member->city_residence)
                    <p class="text-gray-500 text-sm mb-3">{{ $member->city_residence }}</p>
                @endif
                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                    Rejoint {{ $member->created_at->diffForHumans() }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Country Coordinates Script -->
<script src="/js/country-coordinates.js" nonce="{{ $csp_nonce ?? '' }}"></script>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Mapbox with secure API proxy
    $.get('/api/map-config')
        .done(function(config) {
            if (!config.accessToken) {
                console.error('Map service not available');
                $('#map').html('<div class="flex items-center justify-center h-full text-gray-500">Carte temporairement indisponible</div>');
                return;
            }
            
            mapboxgl.accessToken = config.accessToken;
            initializeMap(config);
        })
        .fail(function() {
            console.error('Failed to load map configuration');
            $('#map').html('<div class="flex items-center justify-center h-full text-gray-500">Erreur de chargement de la carte</div>');
        });
});

function initializeMap(config) {
    const map = new mapboxgl.Map({
        container: 'map',
        style: config.mapStyle,
        center: config.center,
        zoom: config.zoom,
        projection: 'globe',
        // Désactiver la collecte de données pour éviter les erreurs d'ad blocker
        collectResourceTiming: false,
        trackResize: true,
        // Configuration du zoom 2x plus rapide
        scrollZoom: {
            around: 'center'
        },
        doubleClickZoom: true,
        touchZoomRotate: {
            around: 'center'
        }
    });
    
    // Ajouter les contrôles de navigation
    map.addControl(new mapboxgl.NavigationControl());
    
    // Configurer le zoom 2x plus rapide
    map.scrollZoom.setWheelZoomRate(1/200); // Plus petit = zoom plus rapide (défaut: 1/450)
    
    // Variables globales pour la gestion des marqueurs
    window.allMembers = [];
    window.currentMarkers = [];
    
    // Charger les membres individuels avec localisation uniquement
    map.on('load', function() {
        $.get('/api/members-with-location')
            .done(function(response) {
                window.allMembers = response.members || response; // Handle both old and new response formats
                updateMarkersForZoom(map);
            })
            .fail(function() {
                console.error('Erreur lors du chargement des membres');
            });
    });
    
    // Mettre à jour les marqueurs quand le zoom change avec debouncing
    let zoomTimeout;
    map.on('zoomend', function() {
        clearTimeout(zoomTimeout);
        zoomTimeout = setTimeout(function() {
            updateMarkersForZoom(map);
        }, 150); // Debounce de 150ms pour éviter les appels multiples
    });
}

function updateMarkersForZoom(map) {
    // Supprimer tous les marqueurs existants
    window.currentMarkers.forEach(marker => marker.remove());
    window.currentMarkers = [];
    
    const zoom = map.getZoom();
    
    // Calculer la tolérance de clustering basée sur le zoom
    let tolerance;
    if (zoom < 6) {
        tolerance = 2.0; // Clustering très large pour vue mondiale
    } else if (zoom < 8) {
        tolerance = 1.0; // Clustering large pour vue continentale
    } else if (zoom < 10) {
        tolerance = 0.3; // Clustering moyen pour vue pays
    } else if (zoom < 12) {
        tolerance = 0.05; // Clustering serré pour vue régionale
    } else if (zoom < 14) {
        tolerance = 0.01; // Clustering très serré pour vue ville
    } else {
        tolerance = 0.001; // Pas de clustering pour vue détaillée
    }
    
    // Grouper les membres par proximité selon le niveau de zoom
    const clusters = clusterNearbyMembers(window.allMembers, tolerance);
    
    clusters.forEach(function(cluster) {
        if (cluster.length === 1) {
            // Marqueur simple pour un membre isolé
            const marker = addSingleMemberMarker(map, cluster[0]);
            window.currentMarkers.push(marker);
        } else {
            // Marqueur cluster pour plusieurs membres proches
            const marker = addClusterMarker(map, cluster);
            window.currentMarkers.push(marker);
        }
    });
}

function clusterNearbyMembers(members, tolerance) {
    const clusters = [];
    const processed = new Set();
    
    members.forEach((member, index) => {
        if (processed.has(index)) return;
        
        const cluster = [member];
        processed.add(index);
        
        // Chercher les membres proches
        members.forEach((otherMember, otherIndex) => {
            if (processed.has(otherIndex)) return;
            
            const distance = Math.sqrt(
                Math.pow(member.latitude - otherMember.latitude, 2) +
                Math.pow(member.longitude - otherMember.longitude, 2)
            );
            
            if (distance < tolerance) {
                cluster.push(otherMember);
                processed.add(otherIndex);
            }
        });
        
        clusters.push(cluster);
    });
    
    return clusters;
}

function addSingleMemberMarker(map, member) {
    // Créer un élément HTML pour le marqueur avec icône bonhomme
    const markerElement = document.createElement('div');
    markerElement.className = 'individual-member-marker';
    markerElement.style.width = '28px';
    markerElement.style.height = '28px';
    markerElement.style.backgroundColor = '#FFFFFF';
    markerElement.style.borderRadius = '50%';
    markerElement.style.border = '2px solid #10B981';
    markerElement.style.boxShadow = '0 3px 6px rgba(0,0,0,0.4)';
    markerElement.style.cursor = 'pointer';
    markerElement.style.display = 'flex';
    markerElement.style.alignItems = 'center';
    markerElement.style.justifyContent = 'center';
    markerElement.style.fontSize = '16px';
    markerElement.style.willChange = 'transform';
    
    // Ajouter l'icône bonhomme
    markerElement.innerHTML = '👤';
    
    // Créer le popup avec les informations du membre
    const popup = new mapboxgl.Popup({
        offset: 15,
        closeButton: false
    }).setHTML(`
        <div class="text-center p-3 min-w-[200px]">
            <div class="flex items-center justify-center mb-2">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                    ${member.name.charAt(0).toUpperCase()}
                </div>
            </div>
            <h3 class="font-bold text-lg text-gray-800 mb-1">${member.name}</h3>
            <p class="text-sm text-gray-600 mb-1">${member.location}</p>
            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full mb-2">
                ${member.role}
            </span>
            <div class="mt-2">
                <a href="${member.profile_url}" target="_blank" 
                   class="inline-block bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600 transition">
                    Voir le profil
                </a>
            </div>
            ${member.updated_at ? `<p class="text-xs text-gray-400 mt-2">Position mise à jour: ${new Date(member.updated_at).toLocaleDateString('fr-FR')}</p>` : ''}
        </div>
    `);
    
    // Ajouter le marqueur à la carte et le retourner
    const marker = new mapboxgl.Marker(markerElement)
        .setLngLat([member.longitude, member.latitude])
        .setPopup(popup)
        .addTo(map);
        
    return marker;
}

function addClusterMarker(map, cluster) {
    // Calculer le centre du cluster
    const centerLat = cluster.reduce((sum, member) => sum + member.latitude, 0) / cluster.length;
    const centerLng = cluster.reduce((sum, member) => sum + member.longitude, 0) / cluster.length;
    
    // Créer un marqueur cluster
    const clusterElement = document.createElement('div');
    clusterElement.className = 'cluster-marker';
    clusterElement.style.width = '40px';
    clusterElement.style.height = '40px';
    clusterElement.style.backgroundColor = '#10B981';
    clusterElement.style.borderRadius = '50%';
    clusterElement.style.border = '3px solid #FFFFFF';
    clusterElement.style.boxShadow = '0 4px 8px rgba(0,0,0,0.4)';
    clusterElement.style.cursor = 'pointer';
    clusterElement.style.display = 'flex';
    clusterElement.style.alignItems = 'center';
    clusterElement.style.justifyContent = 'center';
    clusterElement.style.fontSize = '14px';
    clusterElement.style.fontWeight = 'bold';
    clusterElement.style.color = '#FFFFFF';
    clusterElement.style.willChange = 'transform';
    
    // Afficher le nombre de membres
    clusterElement.innerHTML = cluster.length;
    
    // Ajouter le marqueur cluster à la carte
    const marker = new mapboxgl.Marker(clusterElement)
        .setLngLat([centerLng, centerLat])
        .addTo(map);
    
    // Ajouter un gestionnaire de clic pour zoomer sur le cluster
    clusterElement.addEventListener('click', function() {
        // Calculer les limites du cluster
        const bounds = new mapboxgl.LngLatBounds();
        cluster.forEach(member => {
            bounds.extend([member.longitude, member.latitude]);
        });
        
        // Zoomer sur la zone du cluster avec un padding et vitesse constante
        map.fitBounds(bounds, {
            padding: 100,
            maxZoom: 15,
            duration: 800, // Animation plus courte (800ms au lieu de ~1500ms par défaut)
            easing: function(t) { return t; } // Animation linéaire au lieu de l'easing par défaut
        });
    });
    
    return marker;
}
</script>
@endsection