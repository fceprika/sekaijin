@extends('admin.layout')

@section('title', 'Dashboard Administration')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Administration</h1>
                <p class="text-gray-600 mt-2">Vue d'ensemble de la gestion de contenu Sekaijin</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Connecté en tant que</p>
                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Administrateur
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Articles Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Articles</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['articles_total'] }}</p>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="text-sm text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            {{ $stats['articles_published'] }} publiés
                        </span>
                        <span class="text-sm text-yellow-600">
                            <i class="fas fa-edit mr-1"></i>
                            {{ $stats['articles_draft'] }} brouillons
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-blue-100 rounded-full">
                    <i class="fas fa-newspaper text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.articles') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition duration-200">
                    Gérer les articles →
                </a>
            </div>
        </div>

        <!-- News Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Actualités</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['news_total'] }}</p>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="text-sm text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            {{ $stats['news_published'] }} publiées
                        </span>
                        <span class="text-sm text-yellow-600">
                            <i class="fas fa-edit mr-1"></i>
                            {{ $stats['news_draft'] }} brouillons
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-purple-100 rounded-full">
                    <i class="fas fa-bullhorn text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.news') }}" 
                   class="text-purple-600 hover:text-purple-800 text-sm font-medium transition duration-200">
                    Gérer les actualités →
                </a>
            </div>
        </div>

        <!-- Users Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Membres</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['users_total'] }}</p>
                    <p class="text-sm text-gray-500 mt-2">Utilisateurs inscrits</p>
                </div>
                <div class="p-4 bg-green-100 rounded-full">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Countries Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pays</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['countries_active'] }}</p>
                    <p class="text-sm text-gray-500 mt-2">Pays actifs</p>
                </div>
                <div class="p-4 bg-orange-100 rounded-full">
                    <i class="fas fa-globe text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Actions rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.articles.create') }}" 
               class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition duration-200">
                <i class="fas fa-plus text-blue-600 mr-2"></i>
                <span class="text-blue-800 font-medium">Nouvel article</span>
            </a>
            
            <a href="{{ route('admin.news.create') }}" 
               class="flex items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition duration-200">
                <i class="fas fa-plus text-purple-600 mr-2"></i>
                <span class="text-purple-800 font-medium">Nouvelle actualité</span>
            </a>
            
            <a href="{{ route('admin.articles') }}?status=draft" 
               class="flex items-center justify-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg border border-yellow-200 transition duration-200">
                <i class="fas fa-edit text-yellow-600 mr-2"></i>
                <span class="text-yellow-800 font-medium">Brouillons articles</span>
            </a>
            
            <a href="{{ route('admin.news') }}?status=draft" 
               class="flex items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg border border-orange-200 transition duration-200">
                <i class="fas fa-edit text-orange-600 mr-2"></i>
                <span class="text-orange-800 font-medium">Brouillons actualités</span>
            </a>
        </div>
    </div>

    <!-- Recent Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Articles -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Articles récents</h2>
                <a href="{{ route('admin.articles') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Voir tous →
                </a>
            </div>
            
            @if($recent_articles->count() > 0)
                <div class="space-y-4">
                    @foreach($recent_articles as $article)
                        <a href="{{ route('admin.articles.edit', $article) }}" class="flex items-start justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">{{ $article->title }}</h3>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $article->author->name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $article->country ? $article->country->name_fr : 'Non défini' }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $article->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($article->is_published)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Publié</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Brouillon</span>
                                @endif
                                <div class="text-blue-600 group-hover:text-blue-800 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucun article pour le moment</p>
            @endif
        </div>

        <!-- Recent News -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Actualités récentes</h2>
                <a href="{{ route('admin.news') }}" 
                   class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                    Voir toutes →
                </a>
            </div>
            
            @if($recent_news->count() > 0)
                <div class="space-y-4">
                    @foreach($recent_news as $news)
                        <a href="{{ route('admin.news.edit', $news) }}" class="flex items-start justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-800 mb-1 group-hover:text-purple-600 transition-colors">{{ $news->title }}</h3>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $news->author->name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $news->country ? $news->country->name_fr : 'Non défini' }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $news->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($news->status === 'published')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Publiée</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Brouillon</span>
                                @endif
                                <div class="text-purple-600 group-hover:text-purple-800 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucune actualité pour le moment</p>
            @endif
        </div>
    </div>
</div>
@endsection