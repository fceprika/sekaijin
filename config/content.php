<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Article Categories
    |--------------------------------------------------------------------------
    |
    | Define the available categories for articles with their display names
    | and associated colors for UI components.
    |
    */
    'article_categories' => [
        'témoignage' => [
            'label' => 'Témoignage',
            'color' => 'bg-blue-100 text-blue-800',
            'description' => 'Expériences personnelles et récits d\'expatriation'
        ],
        'guide-pratique' => [
            'label' => 'Guide pratique',
            'color' => 'bg-green-100 text-green-800',
            'description' => 'Conseils pratiques pour la vie à l\'étranger'
        ],
        'travail' => [
            'label' => 'Travail',
            'color' => 'bg-purple-100 text-purple-800',
            'description' => 'Carrière et opportunités professionnelles'
        ],
        'lifestyle' => [
            'label' => 'Lifestyle',
            'color' => 'bg-yellow-100 text-yellow-800',
            'description' => 'Mode de vie et culture locale'
        ],
        'cuisine' => [
            'label' => 'Cuisine',
            'color' => 'bg-orange-100 text-orange-800',
            'description' => 'Gastronomie et spécialités culinaires'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | News Categories
    |--------------------------------------------------------------------------
    |
    | Define the available categories for news/actualités with their display
    | names and associated colors for UI components.
    |
    */
    'news_categories' => [
        'administrative' => [
            'label' => 'Administratif',
            'color' => 'bg-gray-100 text-gray-800',
            'description' => 'Démarches administratives et réglementations'
        ],
        'vie-pratique' => [
            'label' => 'Vie pratique',
            'color' => 'bg-blue-100 text-blue-800',
            'description' => 'Informations utiles du quotidien'
        ],
        'culture' => [
            'label' => 'Culture',
            'color' => 'bg-purple-100 text-purple-800',
            'description' => 'Événements culturels et traditions locales'
        ],
        'economie' => [
            'label' => 'Économie',
            'color' => 'bg-green-100 text-green-800',
            'description' => 'Actualités économiques et financières'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Categories
    |--------------------------------------------------------------------------
    |
    | Define the available categories for events with their display names
    | and associated colors for UI components.
    |
    */
    'event_categories' => [
        'networking' => [
            'label' => 'Networking',
            'color' => 'bg-blue-100 text-blue-800',
            'description' => 'Rencontres professionnelles et réseautage'
        ],
        'social' => [
            'label' => 'Social',
            'color' => 'bg-green-100 text-green-800',
            'description' => 'Événements sociaux et rencontres amicales'
        ],
        'culture' => [
            'label' => 'Culture',
            'color' => 'bg-purple-100 text-purple-800',
            'description' => 'Activités culturelles et artistiques'
        ],
        'sport' => [
            'label' => 'Sport',
            'color' => 'bg-orange-100 text-orange-800',
            'description' => 'Activités sportives et bien-être'
        ],
        'education' => [
            'label' => 'Éducation',
            'color' => 'bg-yellow-100 text-yellow-800',
            'description' => 'Formations et ateliers éducatifs'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Settings
    |--------------------------------------------------------------------------
    |
    | General settings for content management across the platform.
    |
    */
    'settings' => [
        'default_reading_speed' => 200, // words per minute
        'max_reading_time' => 60, // minutes
        'excerpt_max_length' => 500, // characters
        'title_max_length' => 255, // characters
        'featured_content_limit' => 3, // number of featured items per section
    ],
];