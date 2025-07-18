<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUsersLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-locations {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing users with realistic location data for the map';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('🌍 Mise à jour des localisations des utilisateurs pour la nouvelle carte');

        if ($dryRun) {
            $this->warn('🔍 Mode DRY-RUN activé - Aucune modification ne sera faite');
        }

        $users = User::all();
        $updatedCount = 0;

        // Définir des coordonnées approximatives pour les villes principales
        $cityCoordinates = $this->getCityCoordinates();

        foreach ($users as $user) {
            $this->line("Processing user: {$user->name} ({$user->country_residence})");

            // Simuler que 70% des utilisateurs activent le partage de localisation
            $shouldBeVisible = rand(1, 100) <= 70;

            if (! $shouldBeVisible) {
                $this->line('  → Utilisateur choisi pour ne pas être visible sur la carte');
                if (! $dryRun) {
                    $user->update([
                        'is_visible_on_map' => false,
                        'latitude' => null,
                        'longitude' => null,
                        'city_detected' => null,
                    ]);
                }
                continue;
            }

            // Obtenir les coordonnées basées sur la ville ou le pays
            $coordinates = $this->getUserCoordinates($user, $cityCoordinates);

            if ($coordinates) {
                $this->line("  → Ajout de localisation: {$coordinates['city']} ({$coordinates['lat']}, {$coordinates['lng']})");

                if (! $dryRun) {
                    // Utiliser la méthode du modèle qui applique la randomisation pour la vie privée
                    $user->is_visible_on_map = true;
                    $user->save();

                    $user->updateLocation(
                        $coordinates['lat'],
                        $coordinates['lng'],
                        $coordinates['city']
                    );
                }

                $updatedCount++;
            } else {
                $this->line('  → Aucune coordonnée trouvée pour cette localisation');
            }
        }

        if ($dryRun) {
            $this->info('📊 Résultats (DRY-RUN):');
            $this->info("  - {$updatedCount} utilisateurs seraient mis à jour avec une localisation");
            $this->info('  - ' . ($users->count() - $updatedCount) . ' utilisateurs resteraient sans localisation');
        } else {
            $this->info('✅ Mise à jour terminée!');
            $this->info("  - {$updatedCount} utilisateurs mis à jour avec une localisation");
            $this->info('  - ' . ($users->count() - $updatedCount) . ' utilisateurs sans localisation');
        }

        return 0;
    }

    /**
     * Get coordinates for a user based on their city or country.
     */
    private function getUserCoordinates(User $user, array $cityCoordinates): ?array
    {
        $country = $user->country_residence;
        $city = $user->city_residence;

        // Rechercher d'abord par ville spécifique SI elle correspond au pays
        if ($city && $country) {
            $searchKey = strtolower($city);
            if (isset($cityCoordinates[$searchKey])) {
                $cityData = $cityCoordinates[$searchKey];
                // Vérifier que la ville correspond au pays de résidence
                if (isset($cityData['country']) && $cityData['country'] === $country) {
                    return [
                        'lat' => $cityData['lat'],
                        'lng' => $cityData['lng'],
                        'city' => $city,
                    ];
                }
            }
        }

        // Sinon, rechercher par pays et prendre une ville principale
        if ($country) {
            foreach ($cityCoordinates as $cityName => $coords) {
                if (isset($coords['country']) && $coords['country'] === $country) {
                    return [
                        'lat' => $coords['lat'],
                        'lng' => $coords['lng'],
                        'city' => $coords['display_name'] ?? ucfirst($cityName),
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Get predefined coordinates for major cities.
     */
    private function getCityCoordinates(): array
    {
        return [
            // Thaïlande
            'bangkok' => ['lat' => 13.7563, 'lng' => 100.5018, 'country' => 'Thaïlande', 'display_name' => 'Bangkok'],
            'pattaya' => ['lat' => 12.9236, 'lng' => 100.8825, 'country' => 'Thaïlande', 'display_name' => 'Pattaya'],
            'jomtien' => ['lat' => 12.8886, 'lng' => 100.8719, 'country' => 'Thaïlande', 'display_name' => 'Jomtien'],
            'chiang mai' => ['lat' => 18.7883, 'lng' => 98.9853, 'country' => 'Thaïlande', 'display_name' => 'Chiang Mai'],
            'phuket' => ['lat' => 7.8804, 'lng' => 98.3923, 'country' => 'Thaïlande', 'display_name' => 'Phuket'],

            // Japon
            'tokyo' => ['lat' => 35.6762, 'lng' => 139.6503, 'country' => 'Japon', 'display_name' => 'Tokyo'],
            'osaka' => ['lat' => 34.6937, 'lng' => 135.5023, 'country' => 'Japon', 'display_name' => 'Osaka'],
            'kyoto' => ['lat' => 35.0116, 'lng' => 135.7681, 'country' => 'Japon', 'display_name' => 'Kyoto'],

            // Singapour
            'singapour' => ['lat' => 1.3521, 'lng' => 103.8198, 'country' => 'Singapour', 'display_name' => 'Singapour'],

            // États-Unis
            'new york' => ['lat' => 40.7128, 'lng' => -74.0060, 'country' => 'États-Unis', 'display_name' => 'New York'],
            'los angeles' => ['lat' => 34.0522, 'lng' => -118.2437, 'country' => 'États-Unis', 'display_name' => 'Los Angeles'],
            'miami' => ['lat' => 25.7617, 'lng' => -80.1918, 'country' => 'États-Unis', 'display_name' => 'Miami'],

            // Allemagne
            'berlin' => ['lat' => 52.5200, 'lng' => 13.4050, 'country' => 'Allemagne', 'display_name' => 'Berlin'],
            'munich' => ['lat' => 48.1351, 'lng' => 11.5820, 'country' => 'Allemagne', 'display_name' => 'Munich'],

            // Australie
            'sydney' => ['lat' => -33.8688, 'lng' => 151.2093, 'country' => 'Australie', 'display_name' => 'Sydney'],
            'melbourne' => ['lat' => -37.8136, 'lng' => 144.9631, 'country' => 'Australie', 'display_name' => 'Melbourne'],

            // Canada
            'montréal' => ['lat' => 45.5017, 'lng' => -73.5673, 'country' => 'Canada', 'display_name' => 'Montréal'],
            'toronto' => ['lat' => 43.6532, 'lng' => -79.3832, 'country' => 'Canada', 'display_name' => 'Toronto'],
            'montreal' => ['lat' => 45.5017, 'lng' => -73.5673, 'country' => 'Canada', 'display_name' => 'Montréal'],

            // Espagne
            'madrid' => ['lat' => 40.4168, 'lng' => -3.7038, 'country' => 'Espagne', 'display_name' => 'Madrid'],
            'barcelone' => ['lat' => 41.3851, 'lng' => 2.1734, 'country' => 'Espagne', 'display_name' => 'Barcelone'],

            // Royaume-Uni
            'londres' => ['lat' => 51.5074, 'lng' => -0.1278, 'country' => 'Royaume-Uni', 'display_name' => 'Londres'],
            'london' => ['lat' => 51.5074, 'lng' => -0.1278, 'country' => 'Royaume-Uni', 'display_name' => 'Londres'],

            // Suisse
            'zurich' => ['lat' => 47.3769, 'lng' => 8.5417, 'country' => 'Suisse', 'display_name' => 'Zurich'],
            'genève' => ['lat' => 46.2044, 'lng' => 6.1432, 'country' => 'Suisse', 'display_name' => 'Genève'],

            // Émirats arabes unis
            'dubaï' => ['lat' => 25.2048, 'lng' => 55.2708, 'country' => 'Émirats arabes unis', 'display_name' => 'Dubaï'],
            'dubai' => ['lat' => 25.2048, 'lng' => 55.2708, 'country' => 'Émirats arabes unis', 'display_name' => 'Dubaï'],

            // Italie
            'rome' => ['lat' => 41.9028, 'lng' => 12.4964, 'country' => 'Italie', 'display_name' => 'Rome'],
            'milan' => ['lat' => 45.4642, 'lng' => 9.1900, 'country' => 'Italie', 'display_name' => 'Milan'],

            // France
            'paris' => ['lat' => 48.8566, 'lng' => 2.3522, 'country' => 'France', 'display_name' => 'Paris'],
            'lyon' => ['lat' => 45.7640, 'lng' => 4.8357, 'country' => 'France', 'display_name' => 'Lyon'],
            'marseille' => ['lat' => 43.2965, 'lng' => 5.3698, 'country' => 'France', 'display_name' => 'Marseille'],

            // Belgique
            'bruxelles' => ['lat' => 50.8503, 'lng' => 4.3517, 'country' => 'Belgique', 'display_name' => 'Bruxelles'],
            'brussels' => ['lat' => 50.8503, 'lng' => 4.3517, 'country' => 'Belgique', 'display_name' => 'Bruxelles'],

            // Pays-Bas
            'amsterdam' => ['lat' => 52.3676, 'lng' => 4.9041, 'country' => 'Pays-Bas', 'display_name' => 'Amsterdam'],
            'rotterdam' => ['lat' => 51.9244, 'lng' => 4.4777, 'country' => 'Pays-Bas', 'display_name' => 'Rotterdam'],

            // Suède
            'stockholm' => ['lat' => 59.3293, 'lng' => 18.0686, 'country' => 'Suède', 'display_name' => 'Stockholm'],
            'göteborg' => ['lat' => 57.7089, 'lng' => 11.9746, 'country' => 'Suède', 'display_name' => 'Göteborg'],

            // Autriche
            'vienne' => ['lat' => 48.2082, 'lng' => 16.3738, 'country' => 'Autriche', 'display_name' => 'Vienne'],
            'vienna' => ['lat' => 48.2082, 'lng' => 16.3738, 'country' => 'Autriche', 'display_name' => 'Vienne'],

            // Corée du Sud
            'séoul' => ['lat' => 37.5665, 'lng' => 126.9780, 'country' => 'Corée du Sud', 'display_name' => 'Séoul'],
            'seoul' => ['lat' => 37.5665, 'lng' => 126.9780, 'country' => 'Corée du Sud', 'display_name' => 'Séoul'],
        ];
    }
}
