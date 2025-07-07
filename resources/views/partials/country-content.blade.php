@php
    $countrySlug = $country->slug ?? '';
    $countryName = $country->name_fr ?? 'Pays';
    $countryEmoji = $country->emoji ?? '🌍';
@endphp

<!-- {{ $countryName }} Section -->
<div class="mb-8">
    <div class="flex items-center mb-6">
        <span class="text-3xl mr-3">{{ $countryEmoji }}</span>
        <h2 class="text-2xl font-bold text-gray-800">{{ $countryName }}</h2>
    </div>

    <!-- {{ $countryName }} - 2x2 Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6{{ $isLast ?? false ? '' : ' mb-12' }}">
        <!-- Actualités (Top Left) -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">📰 Actualités</h2>
                <a href="/{{ $countrySlug }}/actualites" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
            </div>
            
            <div class="space-y-6">
                @if($country && $news->count() > 0)
                    @foreach($news as $index => $newsItem)
                        <a href="{{ route('country.news.show', [$countrySlug, $newsItem->id]) }}" class="block">
                            <article class="{{ $index === 0 ? 'border border-orange-200 rounded-lg p-4 hover:border-orange-400 hover:shadow-md' : 'p-4 border-b border-gray-100 hover:bg-gray-50 rounded-lg' }} transition-all duration-200 cursor-pointer">
                                @if($index === 0)
                                    <div class="flex items-center space-x-2 mb-3">
                                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">{{ $newsItem->priority === 'high' ? 'Important' : 'Actualité' }}</span>
                                        <span class="text-sm text-gray-500">{{ $newsItem->created_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">{{ $newsItem->created_at->diffForHumans() }}</span>
                                @endif
                                <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $newsItem->title }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{!! strip_tags(Str::limit($newsItem->content, 150)) !!}</p>
                                <span class="text-sm text-gray-500">Publié par {{ $newsItem->author->name }}</span>
                            </article>
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">📰</div>
                        <p class="text-gray-500">Aucune actualité pour l'instant</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Blog (Top Right) -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">✍️ Blog</h2>
                <a href="/{{ $countrySlug }}/blog" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
            </div>
            
            <div class="space-y-6">
                @if($country && $articles->count() > 0)
                    @foreach($articles as $index => $article)
                        @php
                            $categoryColors = [
                                'voyage' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'hover' => 'hover:bg-blue-50 hover:border hover:border-blue-200'],
                                'mode-de-vie' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'hover' => 'hover:bg-yellow-50 hover:border hover:border-yellow-200'],
                                'culture' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'hover' => 'hover:bg-purple-50 hover:border hover:border-purple-200'],
                                'apprentissage' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'hover' => 'hover:bg-green-50 hover:border hover:border-green-200'],
                                'gastronomie' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'hover' => 'hover:bg-orange-50 hover:border hover:border-orange-200'],
                            ];
                            $colors = $categoryColors[$article->category] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'hover' => 'hover:bg-gray-50 hover:border hover:border-gray-200'];
                        @endphp
                        <a href="{{ route('country.article.show', [$countrySlug, $article->slug]) }}" class="block">
                            <article class="{{ $index === 0 ? 'bg-gray-50 rounded-lg border border-transparent ' : 'border border-transparent ' }}p-4 {{ $colors['hover'] }} rounded-lg transition-all duration-200 cursor-pointer">
                                <div class="flex items-center space-x-2 mb-3">
                                    <span class="{{ $colors['bg'] }} {{ $colors['text'] }} px-3 py-1 rounded-full text-sm font-medium">{{ ucfirst(str_replace('-', ' ', $article->category)) }}</span>
                                    <span class="text-sm text-gray-500">{{ $article->created_at->diffForHumans() }}</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $article->title }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{!! strip_tags(Str::limit($article->excerpt ?? $article->content, 150)) !!}</p>
                                <span class="text-sm text-gray-500">Par {{ $article->author->name }}</span>
                            </article>
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">✍️</div>
                        <p class="text-gray-500">Aucun article pour l'instant</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Communauté (Bottom Left) -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">👥 Communauté</h2>
                <a href="/{{ $countrySlug }}/communaute" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
            </div>
            
            <div class="space-y-6">
                @if($countrySlug === 'thailande')
                    <div class="p-4 hover:bg-gray-50 rounded-lg transition duration-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">S</div>
                            <div>
                                <div class="font-medium text-gray-800">Sophie Bernard</div>
                                <span class="text-sm text-gray-500">Il y a 2 heures</span>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-3">🦷 Quelqu'un connaît un bon dentiste francophone à Bangkok? Besoin d'urgence !</p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>💬 12 réponses</span>
                            <span class="text-xs">🔥 Populaire</span>
                        </div>
                    </div>
                    
                    <div class="p-4 hover:bg-gray-50 rounded-lg transition duration-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">L</div>
                            <div>
                                <div class="font-medium text-gray-800">Lucas Martin</div>
                                <span class="text-sm text-gray-500">Il y a 1 jour</span>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-3">📸 Spots photos secrets à Koh Samui à partager avec vous tous !</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span>💬 8 réponses</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 text-center">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-lg font-bold text-blue-600">{{ $totalMembers ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Membres total</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-green-600">{{ $thailandMembers ?? 0 }}</div>
                                <div class="text-xs text-gray-500">En Thaïlande</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">👥</div>
                        <p class="text-gray-500">Rejoignez la discussion !</p>
                        <a href="/{{ $countrySlug }}/communaute" class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                            Voir la communauté
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Événements (Bottom Right) -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">📅 Événements</h2>
                <a href="/{{ $countrySlug }}/evenements" class="text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
            </div>
            
            @if($country && $events->count() > 0)
                @php $event = $events->first() @endphp
                <div class="bg-gradient-to-r {{ $countrySlug === 'japon' ? 'from-pink-50 to-red-50' : 'from-green-50 to-blue-50' }} rounded-lg p-5 border {{ $countrySlug === 'japon' ? 'border-pink-100' : 'border-green-100' }}">
                    <h3 class="font-semibold text-gray-800 mb-3 text-base">{{ $event->title }}</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <span>📅 {{ $event->start_date->format('l j F Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span>🕒 {{ $event->start_date->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span>📍 @if($event->is_online) En ligne @else {{ $event->location }} @endif</span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">{!! strip_tags(Str::limit($event->description, 120)) !!}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Par {{ $event->organizer->name }}</span>
                        <a href="{{ route('country.event.show', [$countrySlug, $event->id]) }}" class="bg-{{ $countrySlug === 'japon' ? 'red' : 'green' }}-600 text-white px-3 py-2 rounded font-medium hover:bg-{{ $countrySlug === 'japon' ? 'red' : 'green' }}-700 transition duration-200">
                            Voir détails
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 text-4xl mb-2">📅</div>
                    <p class="text-gray-500">Aucun événement à venir</p>
                </div>
            @endif
        </div>
    </div>
</div>