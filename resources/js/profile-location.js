/**
 * Profile Location Management Module
 * Handles location-related functionality for user profile forms
 */

// Configuration des états des boutons pour éviter la duplication CSS
const BUTTON_STATES = {
    active: {
        auto: 'w-full bg-gradient-to-r from-blue-600 to-green-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-green-700 transition duration-200 flex items-center justify-center shadow-lg ring-2 ring-blue-300',
        manual: 'w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-4 rounded-lg font-medium hover:from-orange-600 hover:to-red-700 transition duration-200 flex items-center justify-center shadow-lg ring-2 ring-orange-300'
    },
    inactive: {
        auto: 'w-full bg-gradient-to-r from-blue-400 to-green-400 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-500 hover:to-green-500 transition duration-200 flex items-center justify-center shadow-md opacity-75',
        manual: 'w-full bg-gradient-to-r from-orange-400 to-red-400 text-white py-3 px-4 rounded-lg font-medium hover:from-orange-500 hover:to-red-500 transition duration-200 flex items-center justify-center shadow-md opacity-75'
    }
};

// Configuration des icônes et états
const BUTTON_ICONS = {
    auto: {
        loading: '🔍',
        success: '✅', 
        error: '❌',
        default: '🌍'
    },
    manual: {
        active: '✏️',
        default: '📝'
    }
};

class ProfileLocationManager {
    constructor() {
        this.currentController = null; // Pour l'annulation des requêtes
        this.debounceTimer = null;
        this.elements = this.initializeElements();
        this.bindEvents();
        this.initializeState();
    }

    initializeElements() {
        return {
            countryResidence: document.getElementById('country_residence'),
            cityResidence: document.getElementById('city_residence'),
            cityLoading: document.getElementById('city-loading'),
            destinationContainer: document.getElementById('interest-country-container'),
            destinationCountry: document.getElementById('interest_country'),
            autoLocationBtn: document.getElementById('auto-location-btn'),
            manualLocationBtn: document.getElementById('manual-location-btn'),
            autoLocationIcon: document.getElementById('auto-location-icon'),
            autoLocationText: document.getElementById('auto-location-text'),
            editManualLocationBtn: document.getElementById('edit-manual-location'),
            clearLocationBtn: document.getElementById('clear-location-btn'),
            autoLocationSection: document.getElementById('auto-location-section'),
            manualLocationSection: document.getElementById('manual-location-section'),
            shareLocationCheckbox: document.getElementById('share_location'),
            shareLocationHidden: document.getElementById('share_location_hidden'),
            locationRequirement: document.getElementById('location-requirement'),
            detectedCountryDisplay: document.getElementById('detected-country-display'),
            detectedCityDisplay: document.getElementById('detected-city-display'),
            detectedCountryValue: document.getElementById('detected-country-value'),
            detectedCityValue: document.getElementById('detected-city-value'),
            detectedLatitude: document.getElementById('detected-latitude'),
            detectedLongitude: document.getElementById('detected-longitude')
        };
    }

    bindEvents() {
        // Écouter les changements de pays
        if (this.elements.countryResidence) {
            this.elements.countryResidence.addEventListener('change', (e) => {
                // Plus besoin de gérer l'affichage conditionnel
                this.loadCitiesForCountryDebounced(e.target.value);
            });
        }

        // Boutons de mode
        if (this.elements.autoLocationBtn) {
            this.elements.autoLocationBtn.addEventListener('click', () => this.handleAutoLocation());
        }

        if (this.elements.manualLocationBtn) {
            this.elements.manualLocationBtn.addEventListener('click', () => this.handleManualLocation());
        }

        if (this.elements.editManualLocationBtn) {
            this.elements.editManualLocationBtn.addEventListener('click', () => this.switchToManualMode());
        }

        if (this.elements.clearLocationBtn) {
            this.elements.clearLocationBtn.addEventListener('click', () => this.handleClearLocation());
        }

        // Checkbox de localisation
        if (this.elements.shareLocationCheckbox) {
            this.elements.shareLocationCheckbox.addEventListener('change', () => this.updateLocationSharingState());
        }

        if (this.elements.cityResidence) {
            this.elements.cityResidence.addEventListener('change', () => this.updateLocationSharingState());
        }
    }

    initializeState() {
        // Plus besoin de gérer l'affichage conditionnel
        this.updateButtonStates();
        this.initializeRequiredState();
        
        // Charger les villes si un pays est déjà sélectionné
        if (this.elements.countryResidence?.value) {
            this.loadCitiesForCountry(this.elements.countryResidence.value);
        } else {
            this.updateLocationSharingState();
        }
    }

    // Le champ pays d'intérêt est maintenant toujours visible
    toggleDestinationCountry() {
        // Plus besoin de logique conditionnelle
    }

    loadCitiesForCountryDebounced(countryName) {
        // Annuler le timer précédent
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }

        // Annuler la requête précédente si elle existe
        if (this.currentController) {
            this.currentController.abort();
        }

        this.debounceTimer = setTimeout(() => {
            this.loadCitiesForCountry(countryName);
        }, 300); // 300ms de debounce
    }

    async loadCitiesForCountry(countryName) {
        if (!this.elements.cityResidence || !this.elements.cityLoading) return Promise.resolve();

        try {
            if (!countryName) {
                this.elements.cityResidence.innerHTML = '<option value="">Sélectionnez d\'abord un pays</option>';
                this.elements.cityResidence.disabled = true;
                this.elements.cityLoading.classList.add('hidden');
                this.updateLocationSharingState();
                return Promise.resolve();
            }

            // Créer un nouveau controller pour cette requête
            this.currentController = new AbortController();

            // Activer le champ ville et afficher le loading
            this.elements.cityResidence.disabled = false;
            this.elements.cityResidence.innerHTML = '<option value="">Chargement des villes...</option>';
            this.elements.cityLoading.classList.remove('hidden');

            const response = await fetch('/api/cities', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ country: countryName }),
                signal: this.currentController.signal
            });

            const data = await response.json();

            this.elements.cityLoading.classList.add('hidden');
            this.elements.cityResidence.innerHTML = '<option value="">Choisissez une ville</option>';

            if (data.success && data.cities && data.cities.length > 0) {
                data.cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    this.elements.cityResidence.appendChild(option);
                });

                // Restaurer la valeur sélectionnée si elle existe (pour les utilisateurs existants)
                const currentCityElement = document.querySelector('input[name="current_city_residence"]');
                const currentCity = currentCityElement ? currentCityElement.value : '';
                if (currentCity) {
                    this.elements.cityResidence.value = currentCity;
                }
            } else {
                this.elements.cityResidence.innerHTML = '<option value="">Aucune ville trouvée</option>';
            }

            this.updateLocationSharingState();
            this.currentController = null;
            
        } catch (error) {
            if (error.name === 'AbortError') {
                console.log('Requête de chargement des villes annulée');
                return;
            }
            
            console.error('Erreur lors du chargement des villes:', error);
            this.elements.cityLoading.classList.add('hidden');
            this.elements.cityResidence.innerHTML = '<option value="">Erreur de chargement</option>';
            this.updateLocationSharingState();
            this.currentController = null;
        }
    }

    async handleAutoLocation() {
        if (!navigator.geolocation) {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            return;
        }

        if (!this.elements.autoLocationIcon || !this.elements.autoLocationText || !this.elements.autoLocationBtn) return;

        this.elements.autoLocationIcon.textContent = '⏳';
        this.elements.autoLocationText.textContent = 'Localisation en cours...';
        this.elements.autoLocationBtn.disabled = true;

        try {
            const position = await new Promise((resolve, reject) => {
                // Essayer d'abord avec enableHighAccuracy à false
                const options = {
                    enableHighAccuracy: false,
                    timeout: 15000, // Augmenter le timeout à 15 secondes
                    maximumAge: 600000 // 10 minutes cache
                };
                
                navigator.geolocation.getCurrentPosition(
                    resolve,
                    (error) => {
                        // Si l'erreur est "position unavailable", réessayer avec d'autres options
                        if (error.code === 2) {
                            console.log('Position unavailable, retrying with different options...');
                            navigator.geolocation.getCurrentPosition(
                                resolve,
                                reject,
                                {
                                    enableHighAccuracy: true,
                                    timeout: 20000,
                                    maximumAge: 0
                                }
                            );
                        } else {
                            reject(error);
                        }
                    },
                    options
                );
            });

            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            const response = await fetch(`/api/geocode?lat=${lat}&lng=${lng}`);

            if (!response.ok) {
                throw new Error('Erreur de géocodage');
            }

            const data = await response.json();

            if (!data.success || !data.city || !data.countryName) {
                throw new Error('Impossible de déterminer votre localisation');
            }

            this.switchToAutoMode(data.countryName, data.city, lat, lng);

            this.elements.autoLocationIcon.textContent = '✅';
            this.elements.autoLocationText.textContent = 'Position détectée';
            this.elements.autoLocationBtn.disabled = false;

            this.updateButtonStates();

        } catch (error) {
            console.warn('Erreur de géolocalisation:', error);
            this.elements.autoLocationIcon.textContent = '❌';
            this.elements.autoLocationText.textContent = 'Erreur de localisation';
            this.elements.autoLocationBtn.disabled = false;

            let errorMessage = 'Erreur de localisation';
            let helpText = '';
            
            if (error.code === 1) {
                errorMessage = 'Autorisation refusée';
                helpText = 'Veuillez autoriser la géolocalisation dans les paramètres de votre navigateur';
            } else if (error.code === 2) {
                errorMessage = 'Position indisponible';
                helpText = 'Vérifiez que les services de localisation sont activés sur votre appareil';
                // Afficher une alerte plus détaillée pour ce cas
                console.error('GeolocationPositionError:', {
                    code: error.code,
                    message: error.message,
                    hint: 'Essayez de recharger la page ou utilisez la saisie manuelle'
                });
            } else if (error.code === 3) {
                errorMessage = 'Délai dépassé';
                helpText = 'La localisation prend trop de temps. Réessayez ou utilisez la saisie manuelle';
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            this.elements.autoLocationText.textContent = errorMessage;
            
            // Afficher un message d'aide si disponible
            if (helpText && window.showToast) {
                window.showToast(helpText, 'error');
            }
        }
    }

    handleManualLocation() {
        this.switchToManualMode();
        this.updateButtonStates();

        // Précharger les données existantes si disponibles
        setTimeout(() => {
            this.preloadExistingLocation();
        }, 100);
    }

    preloadExistingLocation() {
        const currentCountryElement = document.querySelector('input[name="current_country_residence"]');
        const currentCityElement = document.querySelector('input[name="current_city_residence"]');
        
        const existingCountry = currentCountryElement ? currentCountryElement.value : '';
        const existingCity = currentCityElement ? currentCityElement.value : '';

        if (existingCountry && this.elements.countryResidence && this.elements.countryResidence.value === existingCountry) {
            this.loadCitiesForCountry(existingCountry).then(() => {
                if (this.elements.cityResidence && existingCity) {
                    for (let option of this.elements.cityResidence.options) {
                        if (option.value === existingCity) {
                            this.elements.cityResidence.value = existingCity;
                            break;
                        }
                    }
                }
            });
        }
    }

    switchToAutoMode(country, city, lat, lng) {
        if (!this.elements.manualLocationSection || !this.elements.autoLocationSection) return;

        this.elements.manualLocationSection.classList.add('hidden');
        this.elements.countryResidence?.removeAttribute('required');

        this.elements.autoLocationSection.classList.remove('hidden');
        
        if (this.elements.detectedCountryDisplay) this.elements.detectedCountryDisplay.value = country;
        if (this.elements.detectedCityDisplay) this.elements.detectedCityDisplay.value = city;
        if (this.elements.detectedCountryValue) this.elements.detectedCountryValue.value = country;
        if (this.elements.detectedCityValue) this.elements.detectedCityValue.value = city;
        if (this.elements.detectedLatitude) this.elements.detectedLatitude.value = lat;
        if (this.elements.detectedLongitude) this.elements.detectedLongitude.value = lng;

        this.updateLocationSharingState();
        this.updateButtonStates();
    }

    switchToManualMode() {
        if (!this.elements.manualLocationSection || !this.elements.autoLocationSection) return;

        this.elements.manualLocationSection.classList.remove('hidden');
        this.elements.countryResidence?.setAttribute('required', 'required');

        this.elements.autoLocationSection.classList.add('hidden');

        // Vider champs cachés
        if (this.elements.detectedCountryValue) this.elements.detectedCountryValue.value = '';
        if (this.elements.detectedCityValue) this.elements.detectedCityValue.value = '';
        if (this.elements.detectedLatitude) this.elements.detectedLatitude.value = '';
        if (this.elements.detectedLongitude) this.elements.detectedLongitude.value = '';

        // Réinitialiser les dropdowns manuels seulement si pas de valeur existante
        if (!this.elements.countryResidence?.value) {
            if (this.elements.countryResidence) this.elements.countryResidence.value = '';
            if (this.elements.cityResidence) {
                this.elements.cityResidence.innerHTML = '<option value="">Sélectionnez d\'abord un pays</option>';
                this.elements.cityResidence.disabled = true;
            }
        } else {
            // Si un pays est déjà sélectionné, charger les villes correspondantes
            this.loadCitiesForCountry(this.elements.countryResidence.value);
        }

        this.updateLocationSharingState();
        this.updateButtonStates();
    }

    updateButtonStates() {
        if (!this.elements.autoLocationBtn || !this.elements.manualLocationBtn) return;

        const isAutoMode = !this.elements.autoLocationSection?.classList.contains('hidden');
        const isManualMode = !this.elements.manualLocationSection?.classList.contains('hidden');

        if (isAutoMode) {
            this.elements.autoLocationBtn.className = BUTTON_STATES.active.auto;
            this.elements.manualLocationBtn.className = BUTTON_STATES.inactive.manual;
        } else if (isManualMode) {
            this.elements.autoLocationBtn.className = BUTTON_STATES.inactive.auto;
            this.elements.manualLocationBtn.className = BUTTON_STATES.active.manual;
        } else {
            // État neutre - les deux boutons inactifs
            this.elements.autoLocationBtn.className = BUTTON_STATES.inactive.auto;
            this.elements.manualLocationBtn.className = BUTTON_STATES.inactive.manual;
        }
    }

    initializeRequiredState() {
        if (!this.elements.countryResidence || !this.elements.autoLocationSection || !this.elements.manualLocationSection) return;

        const isAutoMode = !this.elements.autoLocationSection.classList.contains('hidden');
        const isManualMode = !this.elements.manualLocationSection.classList.contains('hidden');

        if (isAutoMode) {
            this.elements.countryResidence.removeAttribute('required');
        } else if (isManualMode) {
            this.elements.countryResidence.setAttribute('required', 'required');
        }
    }

    updateLocationSharingState() {
        if (!this.elements.shareLocationCheckbox || !this.elements.shareLocationHidden || !this.elements.locationRequirement) return;

        // Vérifier si on est en mode automatique
        const isAutoMode = !this.elements.autoLocationSection?.classList.contains('hidden');
        const hasDetectedLocation = this.elements.detectedCityValue?.value !== '';

        // Vérifier si des coordonnées sont disponibles
        const hasDetectedCoordinates = this.elements.detectedLatitude?.value !== '';
        const hasManualCity = this.elements.cityResidence?.value && this.elements.cityResidence.value !== '';

        // Vérifier si l'utilisateur a déjà des données de localisation existantes
        const currentLatElement = document.querySelector('input[name="current_latitude"]');
        const currentLngElement = document.querySelector('input[name="current_longitude"]');
        const hasExistingLocation = (currentLatElement?.value && currentLngElement?.value);

        // Activer la checkbox si : ville sélectionnée OU mode automatique avec coordonnées détectées OU données existantes
        const shouldActivateCheckbox = hasManualCity || (isAutoMode && (hasDetectedLocation || hasDetectedCoordinates)) || hasExistingLocation;

        if (shouldActivateCheckbox) {
            this.elements.shareLocationCheckbox.disabled = false;
            this.elements.locationRequirement.classList.add('hidden');

            // Auto-cocher la checkbox seulement si c'est une première détection
            const isFirstTimeDetection = (hasDetectedCoordinates || hasManualCity) && !hasExistingLocation;
            if (isFirstTimeDetection && !this.elements.shareLocationCheckbox.checked) {
                this.elements.shareLocationCheckbox.checked = true;
            }
        } else {
            this.elements.shareLocationCheckbox.disabled = true;
            this.elements.locationRequirement.classList.remove('hidden');
        }

        // Synchroniser le champ caché avec la checkbox
        this.elements.shareLocationHidden.value = this.elements.shareLocationCheckbox.checked ? '1' : '0';
    }

    async handleClearLocation() {
        if (!confirm('Êtes-vous sûr de vouloir supprimer toutes vos données de localisation ? Cette action ne peut pas être annulée.')) {
            return;
        }

        try {
            if (this.elements.clearLocationBtn) {
                this.elements.clearLocationBtn.disabled = true;
                this.elements.clearLocationBtn.innerHTML = '<svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Suppression...';
            }

            const response = await fetch('/profile/clear-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                // Masquer la section "Localisation active"
                const currentLocationInfo = document.getElementById('current-location-info');
                if (currentLocationInfo) {
                    currentLocationInfo.style.display = 'none';
                }

                // Réinitialiser la checkbox
                if (this.elements.shareLocationCheckbox) {
                    this.elements.shareLocationCheckbox.checked = false;
                    this.elements.shareLocationCheckbox.disabled = true;
                }

                // Réinitialiser les modes
                this.elements.autoLocationSection?.classList.add('hidden');
                this.elements.manualLocationSection?.classList.add('hidden');

                // Vider les champs
                if (this.elements.detectedCountryValue) this.elements.detectedCountryValue.value = '';
                if (this.elements.detectedCityValue) this.elements.detectedCityValue.value = '';
                if (this.elements.detectedLatitude) this.elements.detectedLatitude.value = '';
                if (this.elements.detectedLongitude) this.elements.detectedLongitude.value = '';

                this.updateButtonStates();
                this.updateLocationSharingState();

                alert('Vos données de localisation ont été supprimées avec succès.');
            } else {
                throw new Error(data.message || 'Erreur lors de la suppression');
            }

        } catch (error) {
            console.error('Erreur lors de la suppression:', error);
            alert('Une erreur est survenue lors de la suppression. Veuillez réessayer.');
        } finally {
            if (this.elements.clearLocationBtn) {
                this.elements.clearLocationBtn.disabled = false;
                this.elements.clearLocationBtn.innerHTML = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>Supprimer';
            }
        }
    }
}

// Exporter pour utilisation globale
window.ProfileLocationManager = ProfileLocationManager;