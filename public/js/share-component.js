/**
 * Composant de partage social avec support Web Share API
 * Compatible avec mobile (iOS/Android) et desktop
 */
class ShareComponent {
    constructor() {
        this.isWebShareSupported = 'share' in navigator;
        this.isMobile = this.checkIfMobile();
        this.init();
    }

    checkIfMobile() {
        // Détecter si c'est un appareil mobile
        const userAgent = navigator.userAgent || navigator.vendor || window.opera;
        return /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(userAgent.toLowerCase());
    }

    init() {
        // Ajouter les événements aux boutons de partage existants
        document.addEventListener('click', (e) => {
            if (e.target.matches('.share-button') || e.target.closest('.share-button')) {
                e.preventDefault();
                this.handleShare(e.target.closest('.share-button'));
            }
        });
    }

    async handleShare(button) {
        const url = button.dataset.url || window.location.href;
        const title = button.dataset.title || document.title;
        const text = button.dataset.text || '';
        const platform = button.dataset.platform;
        const forceMenu = button.dataset.forceMenu === 'true';

        // Si c'est un bouton de partage générique
        if (!platform) {
            // Si forceMenu est activé, toujours afficher le menu
            if (forceMenu) {
                this.showShareMenu(button, url, title, text);
                return;
            }
            
            // Utiliser Web Share API uniquement sur mobile ET si supporté
            if (this.isMobile && this.isWebShareSupported) {
                try {
                    await this.shareWithWebAPI(url, title, text);
                    return;
                } catch (error) {
                    console.log('Web Share API non utilisée:', error);
                    // Fallback vers le menu de partage personnalisé
                    this.showShareMenu(button, url, title, text);
                    return;
                }
            } else {
                // Sur desktop ou si Web Share n'est pas supporté, afficher le menu
                this.showShareMenu(button, url, title, text);
                return;
            }
        }

        // Si c'est un bouton de plateforme spécifique
        if (platform) {
            this.shareOnPlatform(platform, url, title, text);
            return;
        }

        // Fallback: afficher le menu de partage
        this.showShareMenu(button, url, title, text);
    }

    async shareWithWebAPI(url, title, text) {
        const shareData = {
            title: title,
            text: text,
            url: url
        };

        try {
            // Vérifier la compatibilité du contenu
            if (navigator.canShare && !navigator.canShare(shareData)) {
                throw new Error('Cannot share this content');
            }

            await navigator.share(shareData);
            return true;
        } catch (error) {
            // L'utilisateur a annulé le partage
            if (error.name === 'AbortError') {
                return false;
            }
            
            // Erreur de permission ou autre
            if (error.name === 'NotAllowedError') {
                console.warn('Web Share API: Permission denied');
                throw error;
            }
            
            // Autres erreurs
            console.warn('Web Share API failed:', error.message);
            throw error;
        }
    }

    shareOnPlatform(platform, url, title, text) {
        const encodedUrl = encodeURIComponent(url);
        const encodedTitle = encodeURIComponent(title);
        const encodedText = encodeURIComponent(text);
        
        const shareUrls = {
            facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
            twitter: `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`,
            linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`,
            whatsapp: `https://wa.me/?text=${encodedTitle} ${encodedUrl}`,
            telegram: `https://t.me/share/url?url=${encodedUrl}&text=${encodedTitle}`,
            email: `mailto:?subject=${encodedTitle}&body=${encodedText} ${encodedUrl}`,
            copy: 'copy'
        };

        if (platform === 'copy') {
            this.copyToClipboard(url);
            return;
        }

        if (shareUrls[platform]) {
            // Ouvrir dans une nouvelle fenêtre avec dimensions optimisées
            const width = 600;
            const height = 400;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            
            window.open(
                shareUrls[platform],
                'share',
                `width=${width},height=${height},left=${left},top=${top},toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1`
            );
        }
    }

    async copyToClipboard(text) {
        try {
            // Vérifier si l'API Clipboard est disponible
            if (!navigator.clipboard) {
                throw new Error('Clipboard API not available');
            }
            
            await navigator.clipboard.writeText(text);
            this.showNotification('Lien copié dans le presse-papier !', 'success');
            return true;
        } catch (err) {
            // Fallback pour les navigateurs plus anciens
            try {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                textArea.style.opacity = '0';
                textArea.setAttribute('readonly', '');
                textArea.setAttribute('aria-hidden', 'true');
                
                document.body.appendChild(textArea);
                textArea.select();
                textArea.setSelectionRange(0, 99999);
                
                const successful = document.execCommand('copy');
                document.body.removeChild(textArea);
                
                if (successful) {
                    this.showNotification('Lien copié dans le presse-papier !', 'success');
                    return true;
                } else {
                    throw new Error('execCommand failed');
                }
            } catch (fallbackErr) {
                console.error('Copy to clipboard failed:', fallbackErr);
                this.showNotification('Impossible de copier le lien', 'error');
                return false;
            }
        }
    }

    showShareMenu(button, url, title, text) {
        // Supprimer le menu existant s'il y en a un
        const existingMenu = document.querySelector('.share-menu');
        if (existingMenu) {
            existingMenu.remove();
        }

        const menu = document.createElement('div');
        menu.className = 'share-menu';
        menu.innerHTML = `
            <div class="share-menu-overlay" aria-hidden="true"></div>
            <div class="share-menu-content" role="dialog" aria-modal="true" aria-labelledby="share-menu-title">
                <div class="share-menu-header">
                    <h3 id="share-menu-title">Partager</h3>
                    <button class="share-menu-close" aria-label="Fermer le menu de partage">&times;</button>
                </div>
                <div class="share-menu-options" role="group" aria-label="Options de partage">
                    <button class="share-option" data-platform="facebook" aria-label="Partager sur Facebook">
                        <i class="fab fa-facebook" aria-hidden="true"></i>
                        <span>Facebook</span>
                    </button>
                    <button class="share-option" data-platform="twitter" aria-label="Partager sur Twitter">
                        <i class="fab fa-twitter" aria-hidden="true"></i>
                        <span>Twitter</span>
                    </button>
                    <button class="share-option" data-platform="linkedin" aria-label="Partager sur LinkedIn">
                        <i class="fab fa-linkedin" aria-hidden="true"></i>
                        <span>LinkedIn</span>
                    </button>
                    <button class="share-option" data-platform="whatsapp" aria-label="Partager sur WhatsApp">
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        <span>WhatsApp</span>
                    </button>
                    <button class="share-option" data-platform="telegram" aria-label="Partager sur Telegram">
                        <i class="fab fa-telegram" aria-hidden="true"></i>
                        <span>Telegram</span>
                    </button>
                    <button class="share-option" data-platform="email" aria-label="Partager par email">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <span>Email</span>
                    </button>
                    <button class="share-option" data-platform="copy" aria-label="Copier le lien">
                        <i class="fas fa-copy" aria-hidden="true"></i>
                        <span>Copier le lien</span>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(menu);

        // Utiliser AbortController pour un nettoyage propre des event listeners
        const controller = new AbortController();
        const signal = controller.signal;

        // Fonction de nettoyage
        const cleanup = () => {
            controller.abort();
            // Restaurer le focus sur le bouton qui a ouvert le menu
            if (button && button.focus) {
                button.focus();
            }
            if (menu.parentNode) {
                menu.remove();
            }
        };

        // Ajouter les événements avec signal pour nettoyage automatique
        menu.querySelector('.share-menu-close').addEventListener('click', cleanup, { signal });
        menu.querySelector('.share-menu-overlay').addEventListener('click', cleanup, { signal });

        // Navigation au clavier et focus trapping
        const focusableElements = menu.querySelectorAll('button, [tabindex]:not([tabindex="-1"])');
        const firstFocusableElement = focusableElements[0];
        const lastFocusableElement = focusableElements[focusableElements.length - 1];

        // Focus trapping et navigation
        menu.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                cleanup();
                return;
            }
            
            if (e.key === 'Tab') {
                // Focus trapping
                if (e.shiftKey) {
                    // Shift + Tab
                    if (document.activeElement === firstFocusableElement) {
                        e.preventDefault();
                        lastFocusableElement.focus();
                    }
                } else {
                    // Tab
                    if (document.activeElement === lastFocusableElement) {
                        e.preventDefault();
                        firstFocusableElement.focus();
                    }
                }
            }
            
            // Navigation avec les flèches
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                const shareOptions = menu.querySelectorAll('.share-option');
                const currentIndex = Array.from(shareOptions).indexOf(document.activeElement);
                
                if (currentIndex !== -1) {
                    let nextIndex;
                    if (e.key === 'ArrowDown') {
                        nextIndex = (currentIndex + 1) % shareOptions.length;
                    } else {
                        nextIndex = (currentIndex - 1 + shareOptions.length) % shareOptions.length;
                    }
                    shareOptions[nextIndex].focus();
                }
            }
        }, { signal });

        // Gestion des options de partage
        menu.querySelectorAll('.share-option').forEach(option => {
            option.addEventListener('click', (e) => {
                try {
                    const platform = e.currentTarget.dataset.platform;
                    this.shareOnPlatform(platform, url, title, text);
                    cleanup();
                } catch (error) {
                    console.error('Error sharing on platform:', error);
                    this.showNotification('Erreur lors du partage', 'error');
                }
            }, { signal });
        });

        // Animation d'entrée et focus initial
        requestAnimationFrame(() => {
            menu.classList.add('show');
            // Donner le focus au premier élément focusable après l'animation
            setTimeout(() => {
                if (firstFocusableElement && firstFocusableElement.focus) {
                    firstFocusableElement.focus();
                }
            }, 100);
        });
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `share-notification ${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Méthode pour créer un bouton de partage programmatiquement
    createShareButton(options = {}) {
        const {
            url = window.location.href,
            title = document.title,
            text = '',
            platform = null,
            className = 'share-button',
            icon = 'fas fa-share-alt',
            label = 'Partager'
        } = options;

        const button = document.createElement('button');
        button.className = className;
        button.dataset.url = url;
        button.dataset.title = title;
        button.dataset.text = text;
        if (platform) button.dataset.platform = platform;

        button.innerHTML = `
            <i class="${icon}"></i>
            ${label ? `<span>${label}</span>` : ''}
        `;

        return button;
    }
}

// Initialiser le composant au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    window.shareComponent = new ShareComponent();
});