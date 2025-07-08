<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class MapDataSeeder extends Seeder
{
    public function run()
    {
        // Coordonnées réalistes pour différentes villes
        $locations = [
            // Thaïlande
            'Bangkok' => [
                'lat' => 13.7563,
                'lng' => 100.5018,
                'variations' => [
                    [13.7463, 100.4918], // Silom
                    [13.7663, 100.5118], // Sukhumvit
                    [13.7363, 100.5218], // Chatuchak
                    [13.7263, 100.4818], // Thonburi
                ]
            ],
            'Chiang Mai' => [
                'lat' => 18.7883,
                'lng' => 98.9853,
                'variations' => [
                    [18.7783, 98.9753], // Old City
                    [18.7983, 98.9953], // Nimman
                    [18.8083, 99.0053], // Chang Phueak
                ]
            ],
            'Phuket' => [
                'lat' => 7.8804,
                'lng' => 98.3923,
                'variations' => [
                    [7.8904, 98.4023], // Patong
                    [7.8704, 98.3823], // Kata
                    [7.8604, 98.3723], // Karon
                ]
            ],
            'Pattaya' => [
                'lat' => 12.9236,
                'lng' => 100.8825,
                'variations' => [
                    [12.9336, 100.8925], // Central Pattaya
                    [12.9136, 100.8725], // Jomtien
                ]
            ],
            
            // Japon
            'Tokyo' => [
                'lat' => 35.6762,
                'lng' => 139.6503,
                'variations' => [
                    [35.6862, 139.6603], // Shibuya
                    [35.6662, 139.6403], // Shinjuku
                    [35.6962, 139.6703], // Ginza
                ]
            ],
            'Osaka' => [
                'lat' => 34.6937,
                'lng' => 135.5023,
                'variations' => [
                    [34.7037, 135.5123], // Namba
                    [34.6837, 135.4923], // Umeda
                ]
            ],
            'Kyoto' => [
                'lat' => 35.0116,
                'lng' => 135.7681,
                'variations' => [
                    [35.0216, 135.7781], // Central Kyoto
                    [35.0016, 135.7581], // Gion
                ]
            ],
            
            // Vietnam
            'Ho Chi Minh' => [
                'lat' => 10.8231,
                'lng' => 106.6297,
                'variations' => [
                    [10.8331, 106.6397], // District 1
                    [10.8131, 106.6197], // District 3
                ]
            ],
            'Hanoi' => [
                'lat' => 21.0285,
                'lng' => 105.8542,
                'variations' => [
                    [21.0385, 105.8642], // Old Quarter
                    [21.0185, 105.8442], // French Quarter
                ]
            ]
        ];

        // Récupérer tous les utilisateurs
        $users = User::all();

        foreach ($users as $user) {
            $city = $user->city_residence;
            
            // Vérifier si on a des coordonnées pour cette ville
            if (isset($locations[$city])) {
                $locationData = $locations[$city];
                
                // Choisir des coordonnées aléatoires parmi les variations
                $coords = $locationData['variations'][array_rand($locationData['variations'])];
                
                // Ajouter une petite randomisation pour plus de réalisme (±0.01 degré ~ 1km)
                $lat = $coords[0] + (mt_rand(-100, 100) / 10000);
                $lng = $coords[1] + (mt_rand(-100, 100) / 10000);
                
                $user->update([
                    'latitude' => round($lat, 6),
                    'longitude' => round($lng, 6),
                    'is_visible_on_map' => true, // Rendre visible par défaut
                    'city_detected' => $city
                ]);
                
                $this->command->info("✅ {$user->name} - {$city}: {$lat}, {$lng}");
            } else {
                // Pour les villes non définies, utiliser des coordonnées par pays
                $countryCoords = $this->getCountryDefaultCoords($user->country_residence);
                if ($countryCoords) {
                    $lat = $countryCoords[0] + (mt_rand(-200, 200) / 1000); // ±0.2 degré
                    $lng = $countryCoords[1] + (mt_rand(-200, 200) / 1000);
                    
                    $user->update([
                        'latitude' => round($lat, 6),
                        'longitude' => round($lng, 6),
                        'is_visible_on_map' => true,
                        'city_detected' => $user->city_residence
                    ]);
                    
                    $this->command->info("🌍 {$user->name} - {$user->country_residence}: {$lat}, {$lng}");
                }
            }
        }

        $this->command->info('🗺️ Coordonnées mises à jour pour tous les utilisateurs !');
    }

    private function getCountryDefaultCoords($country)
    {
        $countries = [
            'Thaïlande' => [13.7563, 100.5018], // Bangkok
            'Japon' => [35.6762, 139.6503], // Tokyo
            'Vietnam' => [10.8231, 106.6297], // Ho Chi Minh
        ];

        return $countries[$country] ?? null;
    }
}