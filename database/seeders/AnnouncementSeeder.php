<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ne pas exécuter en production
        if (app()->environment('production')) {
            $this->command->warn('Les annonces de test ne sont pas exécutées en production.');
            return;
        }

        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé. Veuillez d\'abord créer des utilisateurs.');
            return;
        }

        $announcements = [
            [
                'title' => 'Appartement 2 pièces centre-ville Bangkok',
                'description' => 'Magnifique appartement de 60m² dans le quartier de Sukhumvit. Entièrement meublé, proche du BTS, vue sur la ville. Disponible immédiatement pour location longue durée.',
                'type' => 'location',
                'price' => 25000,
                'currency' => 'THB',
                'country' => 'Thaïlande',
                'city' => 'Bangkok',
                'address' => 'Sukhumvit Road, Watthana',
                'status' => 'active',
            ],
            [
                'title' => 'Cours de français particuliers',
                'description' => 'Professeur de français natif propose des cours particuliers pour tous niveaux. Expérience de 5 ans dans l\'enseignement. Méthode adaptée à chaque élève.',
                'type' => 'service',
                'price' => 800,
                'currency' => 'THB',
                'country' => 'Thaïlande',
                'city' => 'Bangkok',
                'status' => 'active',
            ],
            [
                'title' => 'Vélo tout terrain Trek',
                'description' => 'Vélo Trek en excellent état, peu utilisé. Parfait pour les balades en ville ou les sorties nature. Vendu avec casque et antivol.',
                'type' => 'vente',
                'price' => 8500,
                'currency' => 'THB',
                'country' => 'Thaïlande',
                'city' => 'Chiang Mai',
                'status' => 'active',
            ],
            [
                'title' => 'Colocation dans maison avec jardin',
                'description' => 'Recherche colocataire pour partager une belle maison avec jardin. Chambre privée avec salle de bain. Ambiance détendue et internationale.',
                'type' => 'colocation',
                'price' => 15000,
                'currency' => 'THB',
                'country' => 'Thaïlande',
                'city' => 'Phuket',
                'status' => 'active',
            ],
            [
                'title' => 'Ordinateur portable MacBook Pro',
                'description' => 'MacBook Pro 13" 2020, 8GB RAM, 256GB SSD. En très bon état, vendu avec chargeur et housse de protection. Idéal pour le travail ou les études.',
                'type' => 'vente',
                'price' => 1200,
                'currency' => 'EUR',
                'country' => 'France',
                'city' => 'Paris',
                'status' => 'pending',
            ],
            [
                'title' => 'Studio meublé Shibuya',
                'description' => 'Petit studio meublé dans le quartier branché de Shibuya. Proche des transports, commerces et restaurants. Parfait pour un séjour de courte ou moyenne durée.',
                'type' => 'location',
                'price' => 120000,
                'currency' => 'JPY',
                'country' => 'Japon',
                'city' => 'Tokyo',
                'status' => 'active',
            ],
        ];

        foreach ($announcements as $announcementData) {
            $user = $users->random();
            
            Announcement::create(array_merge($announcementData, [
                'user_id' => $user->id,
                'slug' => Str::slug($announcementData['title']),
                'views' => rand(1, 50),
                'expiration_date' => rand(0, 1) ? now()->addMonths(rand(1, 6)) : null,
            ]));
        }

        $this->command->info('Annonces de test créées avec succès!');
    }
}
