/**
 * Composant de partage social avec support Web Share API
 * Compatible avec mobile (iOS/Android) et desktop
 */
class ShareComponent {
    constructor() {
        this.isWebShareSupported = 'share' in navigator;
        this.init();
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

        // Si c'est un bouton de partage générique et que Web Share est supporté
        if (!platform && this.isWebShareSupported) {
            try {
                await this.shareWithWebAPI(url, title, text);
                return;
            } catch (error) {
                console.log('Web Share API non utilisée:', error);
                // Fallback vers le menu de partage personnalisé
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

        if (navigator.canShare && !navigator.canShare(shareData)) {
            throw new Error('Cannot share this content');
        }

        await navigator.share(shareData);
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
            await navigator.clipboard.writeText(text);
            this.showNotification('Lien copié dans le presse-papier !', 'success');
        } catch (err) {
            // Fallback pour les navigateurs plus anciens
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.select();
            textArea.setSelectionRange(0, 99999);
            
            try {
                document.execCommand('copy');
                this.showNotification('Lien copié dans le presse-papier !', 'success');
            } catch (err) {
                this.showNotification('Impossible de copier le lien', 'error');
            }
            
            document.body.removeChild(textArea);
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
            <div class="share-menu-overlay"></div>
            <div class="share-menu-content">
                <div class="share-menu-header">
                    <h3>Partager</h3>
                    <button class="share-menu-close">&times;</button>
                </div>
                <div class="share-menu-options">
                    <button class="share-option" data-platform="facebook">
                        <i class="fab fa-facebook"></i>
                        <span>Facebook</span>
                    </button>
                    <button class="share-option" data-platform="twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </button>
                    <button class="share-option" data-platform="linkedin">
                        <i class="fab fa-linkedin"></i>
                        <span>LinkedIn</span>
                    </button>
                    <button class="share-option" data-platform="whatsapp">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp</span>
                    </button>
                    <button class="share-option" data-platform="telegram">
                        <i class="fab fa-telegram"></i>
                        <span>Telegram</span>
                    </button>
                    <button class="share-option" data-platform="email">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                    </button>
                    <button class="share-option" data-platform="copy">
                        <i class="fas fa-copy"></i>
                        <span>Copier le lien</span>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(menu);

        // Ajouter les événements
        menu.querySelector('.share-menu-close').addEventListener('click', () => {
            menu.remove();
        });

        menu.querySelector('.share-menu-overlay').addEventListener('click', () => {
            menu.remove();
        });

        menu.querySelectorAll('.share-option').forEach(option => {
            option.addEventListener('click', (e) => {
                const platform = e.currentTarget.dataset.platform;
                this.shareOnPlatform(platform, url, title, text);
                menu.remove();
            });
        });

        // Animation d'entrée
        requestAnimationFrame(() => {
            menu.classList.add('show');
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