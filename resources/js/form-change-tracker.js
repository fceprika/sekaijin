/**
 * Form Change Tracker
 * Détecte et track les changements dans les formulaires
 */

class FormChangeTracker {
    constructor(formSelector, options = {}) {
        this.form = typeof formSelector === 'string' ? document.querySelector(formSelector) : formSelector;
        this.options = {
            excludeFields: ['_token'], // Champs à exclure du tracking
            showVisualIndicators: true,
            submitButtonSelector: 'button[type="submit"]',
            ...options
        };
        
        this.originalValues = new Map();
        this.changedFields = new Set();
        this.submitButtons = [];
        
        if (this.form) {
            this.init();
        }
    }

    init() {
        this.captureOriginalValues();
        this.bindEvents();
        this.initializeSubmitButtons();
        this.updateSubmitButtonState();
    }

    captureOriginalValues() {
        const formData = new FormData(this.form);
        
        // Capturer les valeurs des inputs, selects, textareas
        const inputs = this.form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (this.shouldTrackField(input)) {
                let value = this.getFieldValue(input);
                this.originalValues.set(input.name, value);
            }
        });

        // Capturer les valeurs des checkboxes spéciales
        const checkboxes = this.form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (this.shouldTrackField(checkbox)) {
                this.originalValues.set(checkbox.name, checkbox.checked);
            }
        });
    }

    getFieldValue(field) {
        switch (field.type) {
            case 'checkbox':
                return field.checked;
            case 'radio':
                // Pour les radio buttons, on prend la valeur sélectionnée du groupe
                const radioGroup = this.form.querySelectorAll(`input[name="${field.name}"]`);
                for (let radio of radioGroup) {
                    if (radio.checked) return radio.value;
                }
                return null;
            case 'file':
                return field.files.length > 0 ? 'file_selected' : '';
            default:
                return field.value;
        }
    }

    shouldTrackField(field) {
        return field.name && 
               !this.options.excludeFields.includes(field.name) &&
               !field.disabled &&
               !field.hasAttribute('data-no-track');
    }

    bindEvents() {
        // Écouter les changements sur tous les champs
        const inputs = this.form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (this.shouldTrackField(input)) {
                const events = this.getEventsForField(input);
                events.forEach(event => {
                    input.addEventListener(event, () => {
                        this.checkFieldChange(input);
                    });
                });
            }
        });
    }

    getEventsForField(field) {
        switch (field.type) {
            case 'file':
                return ['change'];
            case 'checkbox':
            case 'radio':
                return ['change'];
            case 'select-one':
            case 'select-multiple':
                return ['change'];
            default:
                return ['input', 'blur'];
        }
    }

    checkFieldChange(field) {
        if (!this.shouldTrackField(field)) return;

        const currentValue = this.getFieldValue(field);
        const originalValue = this.originalValues.get(field.name);
        
        const hasChanged = this.valuesAreDifferent(currentValue, originalValue);

        if (hasChanged) {
            this.changedFields.add(field.name);
            this.markFieldAsChanged(field);
        } else {
            this.changedFields.delete(field.name);
            this.markFieldAsUnchanged(field);
        }

        this.updateSubmitButtonState();
        this.triggerChangeEvent();
    }

    valuesAreDifferent(current, original) {
        // Gestion spéciale pour les fichiers
        if (typeof current === 'string' && current === 'file_selected') {
            return true; // Un fichier a été sélectionné
        }
        
        // Gestion des valeurs nulles/undefined/vides
        const normalizedCurrent = current === null || current === undefined ? '' : String(current);
        const normalizedOriginal = original === null || original === undefined ? '' : String(original);
        
        return normalizedCurrent !== normalizedOriginal;
    }

    markFieldAsChanged(field) {
        if (!this.options.showVisualIndicators) return;
        
        field.classList.add('field-changed');
        
        // Ajouter un indicateur visuel
        this.addChangeIndicator(field);
    }

    markFieldAsUnchanged(field) {
        if (!this.options.showVisualIndicators) return;
        
        field.classList.remove('field-changed');
        
        // Supprimer l'indicateur visuel
        this.removeChangeIndicator(field);
    }

    addChangeIndicator(field) {
        // Éviter les doublons
        if (field.closest('div').querySelector('.change-indicator')) return;
        
        const indicator = document.createElement('span');
        indicator.className = 'change-indicator text-orange-600 font-medium text-xs absolute -bottom-5 left-0';
        indicator.innerHTML = '● Modifié';
        indicator.setAttribute('title', 'Ce champ a été modifié');
        
        // Trouver le conteneur parent du champ (div avec relative positioning)
        const fieldContainer = field.closest('div');
        if (fieldContainer) {
            // S'assurer que le conteneur a position relative
            if (!fieldContainer.classList.contains('relative')) {
                fieldContainer.classList.add('relative');
            }
            fieldContainer.appendChild(indicator);
        }
    }

    removeChangeIndicator(field) {
        const fieldContainer = field.closest('div');
        if (fieldContainer) {
            const indicators = fieldContainer.querySelectorAll('.change-indicator');
            indicators.forEach(indicator => indicator.remove());
        }
    }

    initializeSubmitButtons() {
        this.submitButtons = Array.from(this.form.querySelectorAll(this.options.submitButtonSelector));
        
        // Stocker les textes originaux
        this.submitButtons.forEach(button => {
            button.dataset.originalText = button.textContent;
        });
    }

    updateSubmitButtonState() {
        const hasChanges = this.hasChanges();
        
        this.submitButtons.forEach(button => {
            if (hasChanges) {
                button.disabled = false;
                button.textContent = button.dataset.originalText;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
                button.classList.add('hover:from-blue-700', 'hover:to-purple-700');
            } else {
                button.disabled = true;
                button.textContent = 'Aucun changement détecté';
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.classList.remove('hover:from-blue-700', 'hover:to-purple-700');
            }
        });
    }

    triggerChangeEvent() {
        const event = new CustomEvent('formChanged', {
            detail: {
                hasChanges: this.hasChanges(),
                changedFields: Array.from(this.changedFields),
                changedCount: this.changedFields.size
            }
        });
        
        this.form.dispatchEvent(event);
    }

    hasChanges() {
        return this.changedFields.size > 0;
    }

    getChangedFields() {
        return Array.from(this.changedFields);
    }

    getChangedData() {
        const changedData = {};
        
        this.changedFields.forEach(fieldName => {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                changedData[fieldName] = this.getFieldValue(field);
            }
        });
        
        return changedData;
    }

    reset() {
        // Remettre à zéro le tracking
        this.changedFields.clear();
        
        // Supprimer tous les indicateurs visuels
        this.form.querySelectorAll('.change-indicator').forEach(indicator => {
            indicator.remove();
        });
        
        this.form.querySelectorAll('.field-changed').forEach(field => {
            field.classList.remove('field-changed');
        });
        
        this.updateSubmitButtonState();
        this.triggerChangeEvent();
    }

    refreshOriginalValues() {
        // Mettre à jour les valeurs originales (après sauvegarde réussie par exemple)
        this.originalValues.clear();
        this.captureOriginalValues();
        this.reset();
    }

    // Méthode pour forcer la vérification de tous les champs
    checkAllFields() {
        const inputs = this.form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (this.shouldTrackField(input)) {
                this.checkFieldChange(input);
            }
        });
    }
}

// Exporter pour utilisation globale
window.FormChangeTracker = FormChangeTracker;