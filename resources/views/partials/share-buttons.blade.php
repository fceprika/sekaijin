{{-- Composant réutilisable pour les boutons de partage --}}
<div class="share-buttons-container">
    {{-- Bouton de partage principal --}}
    <button class="share-button inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
            data-url="{{ $url }}"
            data-title="{{ $title }}"
            data-text="{{ $text ?? '' }}">
        <i class="fas fa-share-alt"></i>
        <span>{{ $label ?? 'Partager' }}</span>
    </button>

    {{-- Boutons spécifiques (optionnel) --}}
    @if(isset($showPlatforms) && $showPlatforms)
    <div class="hidden md:flex items-center space-x-2 ml-4">
        <button class="share-button p-2 text-gray-600 hover:text-blue-600 transition-colors"
                data-platform="facebook"
                data-url="{{ $url }}"
                data-title="{{ $title }}"
                data-text="{{ $text ?? '' }}"
                title="Partager sur Facebook">
            <i class="fab fa-facebook text-lg"></i>
        </button>
        
        <button class="share-button p-2 text-gray-600 hover:text-blue-400 transition-colors"
                data-platform="twitter"
                data-url="{{ $url }}"
                data-title="{{ $title }}"
                data-text="{{ $text ?? '' }}"
                title="Partager sur Twitter">
            <i class="fab fa-twitter text-lg"></i>
        </button>
        
        <button class="share-button p-2 text-gray-600 hover:text-blue-700 transition-colors"
                data-platform="linkedin"
                data-url="{{ $url }}"
                data-title="{{ $title }}"
                data-text="{{ $text ?? '' }}"
                title="Partager sur LinkedIn">
            <i class="fab fa-linkedin text-lg"></i>
        </button>
        
        <button class="share-button p-2 text-gray-600 hover:text-green-600 transition-colors"
                data-platform="whatsapp"
                data-url="{{ $url }}"
                data-title="{{ $title }}"
                data-text="{{ $text ?? '' }}"
                title="Partager sur WhatsApp">
            <i class="fab fa-whatsapp text-lg"></i>
        </button>
        
        <button class="share-button p-2 text-gray-600 hover:text-blue-500 transition-colors"
                data-platform="telegram"
                data-url="{{ $url }}"
                data-title="{{ $title }}"
                data-text="{{ $text ?? '' }}"
                title="Partager sur Telegram">
            <i class="fab fa-telegram text-lg"></i>
        </button>
        
        <button class="share-button p-2 text-gray-600 hover:text-red-600 transition-colors"
                data-platform="email"
                data-url="{{ $url }}"
                data-title="{{ $title }}"
                data-text="{{ $text ?? '' }}"
                title="Partager par email">
            <i class="fas fa-envelope text-lg"></i>
        </button>
        
        <button class="share-button p-2 text-gray-600 hover:text-gray-800 transition-colors"
                data-platform="copy"
                data-url="{{ $url }}"
                data-title="{{ $title }}"
                data-text="{{ $text ?? '' }}"
                title="Copier le lien">
            <i class="fas fa-copy text-lg"></i>
        </button>
    </div>
    @endif
</div>

{{-- Style pour les boutons en ligne --}}
@if(isset($inline) && $inline)
<style>
.share-buttons-container {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.share-buttons-container .share-button {
    flex-shrink: 0;
}

@media (max-width: 640px) {
    .share-buttons-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .share-buttons-container .share-button {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endif