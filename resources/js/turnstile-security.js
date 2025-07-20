/**
 * Turnstile Security Manager
 * 
 * Ce module sécurise automatiquement tous les formulaires avec Turnstile
 * en empêchant leur soumission tant que la vérification n'est pas terminée.
 */

class TurnstileSecurityManager {
    constructor() {
        this.verifiedTokens = new Set();
        this.formStates = new Map();
        this.initializeOnDOMReady();
    }

    initializeOnDOMReady() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initialize());
        } else {
            this.initialize();
        }
    }

    initialize() {
        console.log('🔒 Initialisation du gestionnaire de sécurité Turnstile');
        
        // Trouver tous les formulaires avec Turnstile
        const turnstileElements = document.querySelectorAll('.cf-turnstile');
        
        turnstileElements.forEach(turnstileEl => {
            const form = turnstileEl.closest('form');
            if (form) {
                this.secureForm(form, turnstileEl);
            }
        });

        // Configurer les callbacks globaux pour intercepter les réponses Turnstile
        this.setupGlobalCallbacks();
    }

    secureForm(form, turnstileElement) {
        const formId = form.id || `form_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
        if (!form.id) form.id = formId;

        console.log(`🔒 Sécurisation du formulaire: ${formId}`);

        // Trouver tous les boutons de soumission dans ce formulaire
        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        
        // État initial: boutons désactivés
        const initialState = {
            isVerified: false,
            turnstileLoaded: false,
            submitButtons: Array.from(submitButtons),
            originalCallbacks: {
                success: turnstileElement.getAttribute('data-callback'),
                error: turnstileElement.getAttribute('data-error-callback')
            }
        };

        this.formStates.set(formId, initialState);

        // Désactiver les boutons immédiatement
        this.disableSubmitButtons(formId, '🔄 Vérification de sécurité...');

        // Configurer les callbacks
        const uniqueCallbacks = this.generateUniqueCallbacks(formId);
        
        // Mettre à jour les attributs seulement si on a créé de nouveaux callbacks
        if (uniqueCallbacks.success && !initialState.originalCallbacks.success) {
            turnstileElement.setAttribute('data-callback', uniqueCallbacks.success);
        }
        
        if (uniqueCallbacks.error && !initialState.originalCallbacks.error) {
            turnstileElement.setAttribute('data-error-callback', uniqueCallbacks.error);
        }

        // Intercepter la soumission du formulaire
        form.addEventListener('submit', (e) => this.handleFormSubmit(e, formId));

        // Détecter quand Turnstile est chargé
        this.waitForTurnstileLoad(turnstileElement, formId);
    }

    generateUniqueCallbacks(formId) {
        const state = this.formStates.get(formId);
        const originalSuccessCallback = state.originalCallbacks.success;
        const originalErrorCallback = state.originalCallbacks.error;

        // Approche simplifiée : toujours encapsuler les callbacks existants
        if (originalSuccessCallback) {
            // Sauvegarder l'original s'il existe
            const originalFunction = window[originalSuccessCallback];
            
            // Remplacer par notre wrapper
            window[originalSuccessCallback] = (token) => {
                console.log(`🔓 Callback Turnstile success intercepté pour ${formId}`);
                
                // Notre gestionnaire en premier
                this.handleTurnstileSuccess(formId, token);
                
                // Puis l'original si il existait
                if (originalFunction && typeof originalFunction === 'function') {
                    originalFunction(token);
                }
            };
        }

        if (originalErrorCallback) {
            const originalFunction = window[originalErrorCallback];
            
            window[originalErrorCallback] = (error) => {
                console.log(`❌ Callback Turnstile error intercepté pour ${formId}`);
                
                this.handleTurnstileError(formId, error);
                
                if (originalFunction && typeof originalFunction === 'function') {
                    originalFunction(error);
                }
            };
        }

        // Si aucun callback n'existe, créer les nôtres
        if (!originalSuccessCallback) {
            const successCallback = `turnstileSuccess_${formId.replace(/[^a-zA-Z0-9]/g, '_')}`;
            window[successCallback] = (token) => {
                console.log(`🔓 Callback Turnstile success créé pour ${formId}`);
                this.handleTurnstileSuccess(formId, token);
            };
            
            return { 
                success: successCallback, 
                error: originalErrorCallback || `turnstileError_${formId.replace(/[^a-zA-Z0-9]/g, '_')}` 
            };
        }

        return {
            success: originalSuccessCallback,
            error: originalErrorCallback
        };
    }

    waitForTurnstileLoad(turnstileElement, formId) {
        // Marquer comme chargé immédiatement car Turnstile charge de façon asynchrone
        console.log(`✅ Turnstile en cours de chargement pour ${formId}`);
        const state = this.formStates.get(formId);
        state.turnstileLoaded = true;
        
        // Mise à jour initiale - le bouton restera désactivé jusqu'à la vérification
        this.updateButtonState(formId);

        // Fallback: Si aucune vérification après 15 secondes, permettre la soumission
        setTimeout(() => {
            const currentState = this.formStates.get(formId);
            if (!currentState.isVerified) {
                console.warn(`⚠️ Timeout Turnstile pour ${formId} - Activation de secours`);
                currentState.isVerified = true;
                this.updateButtonState(formId);
            }
        }, 15000);
    }

    handleTurnstileSuccess(formId, token) {
        console.log(`✅ Turnstile vérifié avec succès pour ${formId}:`, token);
        
        const state = this.formStates.get(formId);
        if (state) {
            state.isVerified = true;
            this.verifiedTokens.add(token);
            this.updateButtonState(formId);
        }
    }

    handleTurnstileError(formId, error) {
        console.error(`❌ Erreur Turnstile pour ${formId}:`, error);
        
        const state = this.formStates.get(formId);
        if (state) {
            state.isVerified = false;
            this.disableSubmitButtons(formId, '❌ Erreur de vérification - Rechargez la page');
        }
    }

    updateButtonState(formId) {
        const state = this.formStates.get(formId);
        
        if (state.isVerified) {
            this.enableSubmitButtons(formId);
        } else if (state.turnstileLoaded) {
            this.disableSubmitButtons(formId, '🔄 En attente de vérification...');
        } else {
            this.disableSubmitButtons(formId, '🔄 Chargement de la sécurité...');
        }
    }

    enableSubmitButtons(formId) {
        const state = this.formStates.get(formId);
        if (!state) return;

        state.submitButtons.forEach(button => {
            button.disabled = false;
            
            // Sauvegarder le contenu original avant toute modification (si pas déjà fait)
            if (!button.dataset.originalContent) {
                button.dataset.originalContent = button.innerHTML;
            }
            
            // Restaurer complètement le contenu original
            button.innerHTML = button.dataset.originalContent;
            
            // Ajouter une classe pour indiquer que la vérification est réussie
            button.classList.add('turnstile-verified');
            button.classList.remove('turnstile-pending', 'turnstile-error');
        });

        console.log(`✅ Boutons de soumission activés pour ${formId}`);
    }

    disableSubmitButtons(formId, message = '🔄 Vérification en cours...') {
        const state = this.formStates.get(formId);
        if (!state) return;

        state.submitButtons.forEach(button => {
            button.disabled = true;
            
            // Sauvegarder le contenu original s'il n'est pas déjà sauvegardé
            if (!button.dataset.originalContent) {
                button.dataset.originalContent = button.innerHTML;
            }
            
            // Remplacer simplement le contenu du bouton par le message
            button.textContent = message;
            
            // Ajouter des classes CSS pour le style
            button.classList.add('turnstile-pending');
            button.classList.remove('turnstile-verified', 'turnstile-error');
        });

        console.log(`🔒 Boutons de soumission désactivés pour ${formId}: ${message}`);
    }

    handleFormSubmit(event, formId) {
        const state = this.formStates.get(formId);
        
        if (!state || !state.isVerified) {
            event.preventDefault();
            event.stopImmediatePropagation();
            
            console.warn(`🚫 Soumission bloquée pour ${formId} - Turnstile non vérifié`);
            
            // Afficher un message d'erreur à l'utilisateur
            this.showUserError('Veuillez patienter pendant la vérification de sécurité...');
            
            return false;
        }

        console.log(`✅ Soumission autorisée pour ${formId} - Turnstile vérifié`);
        return true;
    }

    showUserError(message) {
        // Créer ou réutiliser une alerte
        let alert = document.getElementById('turnstile-security-alert');
        if (!alert) {
            alert = document.createElement('div');
            alert.id = 'turnstile-security-alert';
            alert.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';
            document.body.appendChild(alert);
        }

        alert.innerHTML = `
            <span class="mr-2">🔐</span>
            <span>${message}</span>
        `;
        
        alert.style.display = 'flex';
        
        // Auto-masquer après 4 secondes
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.display = 'none';
            }
        }, 4000);
    }

    setupGlobalCallbacks() {
        // Callback de secours pour détecter les réussites Turnstile non interceptées
        const originalOnload = window.onTurnstileLoad;
        window.onTurnstileLoad = () => {
            console.log('🔄 Détection du chargement global de Turnstile');
            if (originalOnload) originalOnload();
        };
    }

    // Méthode publique pour déboguer
    getFormStates() {
        return Object.fromEntries(this.formStates);
    }
}

// Initialiser automatiquement
const turnstileSecurity = new TurnstileSecurityManager();

// Exporter pour utilisation globale
window.TurnstileSecurityManager = turnstileSecurity;

// Debug en développement
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    window.debugTurnstile = () => turnstileSecurity.getFormStates();
}