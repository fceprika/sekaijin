@extends('layout')

@section('title', 'Accueil - Sekaijin')

@section('content')
<!-- Hero Section with Gradient -->
<div class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
            Bienvenue sur 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-pink-400">
                Sekaijin
            </span>
        </h1>
        <p class="text-xl md:text-2xl mb-10 text-blue-100 max-w-3xl mx-auto">
            La communauté des expatriés français à travers le monde
        </p>
        <div class="space-x-4">
            <button id="hero-btn" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transform hover:scale-105 transition duration-300 shadow-lg">
                Rejoindre la communauté
            </button>
            <a href="/about" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-600 transition duration-300 inline-block">
                En savoir plus
            </a>
        </div>
    </div>
    <!-- Floating shapes -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-yellow-400 rounded-full opacity-20 animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-16 h-16 bg-pink-400 rounded-full opacity-20 animate-bounce"></div>
</div>

<!-- Interactive Map Section -->
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Notre Communauté dans le Monde</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Découvrez où se trouvent les membres de Sekaijin à travers le globe
            </p>
        </div>
        
        <!-- Map Container -->
        <div class="bg-gray-100 rounded-2xl p-2 md:p-4 shadow-lg">
            <div id="map" class="h-[250px] md:h-[400px] lg:h-[500px] w-full rounded-xl"></div>
        </div>
        
        <!-- Map Legend -->
        <div class="mt-6 text-center">
            <div class="inline-flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-6 bg-gray-50 px-4 md:px-6 py-3 rounded-lg">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Membres de la communauté</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs md:text-sm text-gray-500">Cliquez sur un membre pour voir son profil</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Content Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        @include('partials.country-content', [
            'country' => $thailand,
            'news' => $thailandNews,
            'articles' => $thailandArticles,
            'events' => $thailandEvents,
            'isLast' => false
        ])

        @include('partials.country-content', [
            'country' => $japan,
            'news' => $japanNews,
            'articles' => $japanArticles,
            'events' => $japanEvents,
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

<!-- Stats Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold text-blue-600 mb-2">25K+</div>
                <div class="text-gray-600">Membres Actifs</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-green-600 mb-2">150</div>
                <div class="text-gray-600">Pays Couverts</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-purple-600 mb-2">24/7</div>
                <div class="text-gray-600">Entraide</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-indigo-600 mb-2">5 ans</div>
                <div class="text-gray-600">D'expérience</div>
            </div>
        </div>
    </div>
</div>

<!-- Country Coordinates Script -->
<script src="/js/country-coordinates.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero button functionality
    $('#hero-btn').click(function() {
        window.location.href = '/inscription';
    });
    
    // Initialize Mapbox
    mapboxgl.accessToken = '{{ config('services.mapbox.access_token') }}';
    
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [100.5018, 13.7563], // Centré sur la Thaïlande (Bangkok)
        zoom: 2,
        projection: 'globe',
        // Désactiver la collecte de données pour éviter les erreurs d'ad blocker
        collectResourceTiming: false,
        trackResize: true
    });
    
    // Ajouter les contrôles de navigation
    map.addControl(new mapboxgl.NavigationControl());
    
    // Charger les membres individuels avec localisation uniquement
    map.on('load', function() {
        $.get('/api/members-with-location')
            .done(function(data) {
                addIndividualMembersToMap(map, data);
            })
            .fail(function() {
                console.error('Erreur lors du chargement des membres');
            });
    });
});

function addIndividualMembersToMap(map, members) {
    members.forEach(function(member) {
        // Créer un élément HTML pour le marqueur individuel (plus petit, vert)
        const markerElement = document.createElement('div');
        markerElement.className = 'individual-member-marker';
        markerElement.style.width = '12px';
        markerElement.style.height = '12px';
        markerElement.style.backgroundColor = '#10B981'; // Green-500
        markerElement.style.borderRadius = '50%';
        markerElement.style.border = '2px solid white';
        markerElement.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
        markerElement.style.cursor = 'pointer';
        
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
        
        // Ajouter le marqueur à la carte
        new mapboxgl.Marker(markerElement)
            .setLngLat([member.longitude, member.latitude])
            .setPopup(popup)
            .addTo(map);
    });
}
</script>
@endsection