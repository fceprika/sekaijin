@extends('layout')

@section('title', 'Actualités ' . $countryModel->name_fr . ' - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        📰 <span class="ml-2">Actualités {{ $countryModel->emoji }} {{ $countryModel->name_fr }}</span>
                    </h1>
                    <p class="text-gray-600 mt-1">Les dernières nouvelles de la communauté française</p>
                </div>
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isAmbassador())
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter une actualité
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- News List -->
            <div class="lg:col-span-2">
                @if($featuredNews->count() > 0)
                    <!-- Featured News -->
                    @php $featuredNewsItem = $featuredNews->first() @endphp
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                                    À la une
                                </span>
                                <span class="mx-2">•</span>
                                <span>{{ $featuredNewsItem->published_at->diffForHumans() }}</span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                                <a href="{{ route('country.news.show', [$countryModel->slug, $featuredNewsItem->slug]) }}" class="hover:text-blue-600">
                                    {{ $featuredNewsItem->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 mb-4">
                                {{ $featuredNewsItem->excerpt }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr($featuredNewsItem->author->name, 0, 1)) }}
                                    </div>
                                    <a href="{{ $featuredNewsItem->author->getPublicProfileUrl() }}" class="text-sm text-gray-700 font-medium hover:text-blue-600">
                                        {{ $featuredNewsItem->author->name }}
                                        @if($featuredNewsItem->author->is_verified)
                                            <i class="fas fa-check-circle text-blue-500 ml-1"></i>
                                        @endif
                                    </a>
                                </div>
                                <a href="{{ route('country.news.show', [$countryModel->slug, $featuredNewsItem->slug]) }}" class="text-blue-600 hover:text-blue-800 font-medium">Lire la suite →</a>
                            </div>
                        </div>
                    </article>
                @endif

                <!-- Regular News -->
                @if($news->count() > 0)
                    <div class="space-y-6">
                        @foreach($news as $newsItem)
                            @if(!($featuredNews->count() > 0 && $newsItem->id === $featuredNews->first()->id))
                                <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer group">
                                    <a href="{{ route('country.news.show', [$countryModel->slug, $newsItem->slug]) }}" class="block p-6">
                                    <div class="flex items-center text-sm text-gray-500 mb-2">
                                        @php
                                            $categoryColor = match($newsItem->category) {
                                                'administrative' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                                'vie-pratique' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                                'culture' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                                                'economie' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                                                default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                                            };
                                        @endphp
                                        <span class="{{ $categoryColor['bg'] }} {{ $categoryColor['text'] }} px-2 py-1 rounded-full text-xs font-medium">
                                            {{ ucfirst($newsItem->category) }}
                                        </span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $newsItem->published_at->diffForHumans() }}</span>
                                        @if($newsItem->is_featured)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors">
                                        {{ $newsItem->title }}
                                    </h3>
                                    <p class="text-gray-600 mb-4">
                                        {{ $newsItem->excerpt }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            @php
                                                $avatarColor = match($newsItem->category) {
                                                    'administrative' => 'bg-blue-500',
                                                    'vie-pratique' => 'bg-green-500', 
                                                    'culture' => 'bg-purple-500',
                                                    'economie' => 'bg-orange-500',
                                                    default => 'bg-gray-500',
                                                };
                                            @endphp
                                            <div class="w-8 h-8 {{ $avatarColor }} rounded-full flex items-center justify-center text-white font-bold text-xs">
                                                {{ strtoupper(substr($newsItem->author->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm text-gray-700 font-medium">
                                                {{ $newsItem->author->name }}
                                                @if($newsItem->author->is_verified)
                                                    <i class="fas fa-check-circle text-blue-500 ml-1"></i>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-xs text-gray-500">{{ $newsItem->views }} vues</span>
                                            <span class="text-blue-600 group-hover:text-blue-800 font-medium transition-colors">Lire la suite →</span>
                                        </div>
                                    </div>
                                    </a>
                                </article>
                            @endif
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        {{ $news->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📰</div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune actualité pour le moment</h3>
                        <p class="text-gray-500 mb-6">Les dernières nouvelles apparaîtront ici.</p>
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->user()->isAmbassador())
                                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                    Publier la première actualité
                                </button>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Catégories</h3>
                    <div class="space-y-2">
                        @php
                            $categories = [
                                'administrative' => ['count' => $news->where('category', 'administrative')->count(), 'color' => 'blue'],
                                'vie-pratique' => ['count' => $news->where('category', 'vie-pratique')->count(), 'color' => 'green'],
                                'culture' => ['count' => $news->where('category', 'culture')->count(), 'color' => 'purple'],
                                'economie' => ['count' => $news->where('category', 'economie')->count(), 'color' => 'orange'],
                            ];
                        @endphp
                        @foreach($categories as $category => $data)
                            @if($data['count'] > 0)
                                <a href="#" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-200">
                                    <span class="text-gray-700">{{ ucfirst(str_replace('-', ' ', $category)) }}</span>
                                    <span class="bg-{{ $data['color'] }}-100 text-{{ $data['color'] }}-800 px-2 py-1 rounded-full text-xs">{{ $data['count'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Newsletter {{ $countryModel->name_fr }}</h3>
                    <p class="text-gray-600 text-sm mb-4">Recevez les dernières actualités directement dans votre boîte mail.</p>
                    <div class="space-y-3">
                        <input type="email" placeholder="votre@email.com" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200">
                            S'abonner
                        </button>
                    </div>
                </div>

                <!-- Latest News -->
                @if($featuredNews->count() > 1)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Autres actualités vedettes</h3>
                        <div class="space-y-3">
                            @foreach($featuredNews->skip(1)->take(3) as $sidebarNews)
                                <div class="border-b border-gray-100 pb-3 last:border-b-0">
                                    <h4 class="text-sm font-medium text-gray-800 mb-1">
                                        <a href="{{ route('country.news.show', [$countryModel->slug, $sidebarNews->slug]) }}" class="hover:text-blue-600">
                                            {{ $sidebarNews->title }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-gray-500">{{ $sidebarNews->published_at->format('d/m/Y') }} • {{ $sidebarNews->views }} vues</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection