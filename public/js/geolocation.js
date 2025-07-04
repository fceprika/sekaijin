/**
 * Geolocation Service for Sekaijin
 * Handles voluntary location sharing with privacy protection
 */

class GeolocationService {
    constructor() {
        this.isSupported = 'geolocation' in navigator;
        this.currentPosition = null;
        this.isLoading = false;
    }

    /**
     * Check if geolocation is supported
     */
    isGeolocationSupported() {
        return this.isSupported;
    }

    /**
     * Get user's current position with privacy protection
     */
    async getCurrentPosition() {
        if (!this.isSupported) {
            throw new Error('La géolocalisation n\'est pas supportée par votre navigateur.');
        }

        if (this.isLoading) {
            throw new Error('Localisation en cours...');
        }

        this.isLoading = true;

        try {
            const position = await this.getPositionPromise();
            this.currentPosition = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
            };

            // Get city name from coordinates
            const cityName = await this.getCityFromCoordinates(
                position.coords.latitude, 
                position.coords.longitude
            );

            this.currentPosition.city = cityName;
            this.isLoading = false;
            
            return this.currentPosition;
        } catch (error) {
            this.isLoading = false;
            throw this.handleGeolocationError(error);
        }
    }

    /**
     * Promisify the geolocation API
     */
    getPositionPromise() {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                resolve,
                reject,
                {
                    enableHighAccuracy: false, // Better for privacy
                    timeout: 10000,
                    maximumAge: 300000 // 5 minutes cache
                }
            );
        });
    }

    /**
     * Get city name from coordinates using reverse geocoding
     */
    async getCityFromCoordinates(latitude, longitude) {
        try {
            // Use a free geocoding service (Nominatim OpenStreetMap)
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=10&addressdetails=1`
            );

            if (!response.ok) {
                throw new Error('Erreur de géocodage');
            }

            const data = await response.json();
            
            // Extract city name from various possible fields
            const address = data.address || {};
            const city = address.city || 
                        address.town || 
                        address.village || 
                        address.county || 
                        address.state || 
                        'Ville inconnue';

            return city;
        } catch (error) {
            console.warn('Impossible de détecter la ville:', error);
            return 'Ville inconnue';
        }
    }

    /**
     * Handle geolocation errors with user-friendly messages
     */
    handleGeolocationError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                return new Error('Autorisation refusée. Vous devez autoriser la géolocalisation dans votre navigateur.');
            case error.POSITION_UNAVAILABLE:
                return new Error('Position indisponible. Vérifiez votre connexion et réessayez.');
            case error.TIMEOUT:
                return new Error('Délai d\'attente dépassé. Réessayez dans quelques instants.');
            default:
                return new Error('Erreur de géolocalisation: ' + error.message);
        }
    }

    /**
     * Save user location to the server
     */
    async saveLocationToServer(csrfToken) {
        if (!this.currentPosition) {
            throw new Error('Aucune position disponible. Veuillez d\'abord obtenir votre localisation.');
        }

        try {
            const response = await fetch('/api/update-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    latitude: this.currentPosition.latitude,
                    longitude: this.currentPosition.longitude,
                    city: this.currentPosition.city
                })
            });

            if (!response.ok) {
                throw new Error('Erreur lors de l\'enregistrement de la localisation');
            }

            const data = await response.json();
            return data;
        } catch (error) {
            throw new Error('Impossible d\'enregistrer votre localisation: ' + error.message);
        }
    }

    /**
     * Clear current position data
     */
    clearPosition() {
        this.currentPosition = null;
    }
}

// UI Helper functions
class LocationUI {
    constructor(geolocationService) {
        this.geo = geolocationService;
    }

    /**
     * Show location sharing modal/form
     */
    showLocationModal(onSuccess, onError) {
        const modal = document.getElementById('locationModal');
        const enableBtn = document.getElementById('enableLocationBtn');
        const skipBtn = document.getElementById('skipLocationBtn');
        const statusDiv = document.getElementById('locationStatus');

        if (!modal) {
            console.error('Modal de localisation non trouvé');
            return;
        }

        // Show modal
        modal.style.display = 'block';
        modal.classList.add('show');

        // Handle enable button
        enableBtn.onclick = async () => {
            try {
                enableBtn.disabled = true;
                enableBtn.textContent = 'Localisation en cours...';
                statusDiv.textContent = 'Obtention de votre position...';

                const position = await this.geo.getCurrentPosition();
                statusDiv.innerHTML = `
                    <div class="text-green-600">
                        ✓ Position détectée: ${position.city}<br>
                        <small class="text-gray-600">Votre position sera rendue approximative pour protéger votre vie privée</small>
                    </div>
                `;

                // Show save button
                const saveBtn = document.createElement('button');
                saveBtn.textContent = 'Enregistrer ma position';
                saveBtn.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-2';
                saveBtn.onclick = async () => {
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        await this.geo.saveLocationToServer(csrfToken);
                        
                        statusDiv.innerHTML = '<div class="text-green-600">✓ Localisation enregistrée avec succès!</div>';
                        
                        setTimeout(() => {
                            modal.style.display = 'none';
                            modal.classList.remove('show');
                            if (onSuccess) onSuccess(position);
                        }, 1500);
                    } catch (error) {
                        statusDiv.innerHTML = `<div class="text-red-600">Erreur: ${error.message}</div>`;
                        if (onError) onError(error);
                    }
                };
                
                statusDiv.appendChild(saveBtn);
                
            } catch (error) {
                statusDiv.innerHTML = `<div class="text-red-600">Erreur: ${error.message}</div>`;
                enableBtn.disabled = false;
                enableBtn.textContent = 'Autoriser la géolocalisation';
                if (onError) onError(error);
            }
        };

        // Handle skip button
        skipBtn.onclick = () => {
            modal.style.display = 'none';
            modal.classList.remove('show');
        };
    }

    /**
     * Update location sharing checkbox state
     */
    updateLocationSharingUI(isEnabled) {
        const checkbox = document.getElementById('shareLocationCheckbox');
        const statusDiv = document.getElementById('locationSharingStatus');
        
        if (checkbox) {
            checkbox.checked = isEnabled;
        }
        
        if (statusDiv) {
            statusDiv.innerHTML = isEnabled ? 
                '<span class="text-green-600">✓ Partage de localisation activé</span>' :
                '<span class="text-gray-600">Partage de localisation désactivé</span>';
        }
    }
}

// Make services available globally
window.GeolocationService = GeolocationService;
window.LocationUI = LocationUI;