<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testUsers = [
            [
                'name' => 'Pierre_Tokyo',
                'email' => 'pierre.tokyo@example.com',
                'country_residence' => 'Japon',
                'city_residence' => 'Tokyo',
                'first_name' => 'Pierre',
                'last_name' => 'Martin',
                'bio' => 'Expatrié depuis 5 ans au Japon, passionné de culture japonaise.',
            ],
            [
                'name' => 'Marie_NYC',
                'email' => 'marie.nyc@example.com',
                'country_residence' => 'États-Unis',
                'city_residence' => 'New York',
                'first_name' => 'Marie',
                'last_name' => 'Dupont',
                'bio' => 'Consultante en finance à Manhattan.',
            ],
            [
                'name' => 'Julien_Bangkok',
                'email' => 'julien.bangkok@example.com',
                'country_residence' => 'Thaïlande',
                'city_residence' => 'Bangkok',
                'first_name' => 'Julien',
                'last_name' => 'Leroy',
                'bio' => 'Entrepreneur digital nomade en Asie du Sud-Est.',
            ],
            [
                'name' => 'Sophie_Berlin',
                'email' => 'sophie.berlin@example.com',
                'country_residence' => 'Allemagne',
                'city_residence' => 'Berlin',
                'first_name' => 'Sophie',
                'last_name' => 'Bernard',
                'bio' => 'Ingénieure logiciel dans une startup berlinoise.',
            ],
            [
                'name' => 'Antoine_Sydney',
                'email' => 'antoine.sydney@example.com',
                'country_residence' => 'Australie',
                'city_residence' => 'Sydney',
                'first_name' => 'Antoine',
                'last_name' => 'Moreau',
                'bio' => 'Chef cuisinier, découvre la gastronomie australienne.',
            ],
            [
                'name' => 'Camille_Montreal',
                'email' => 'camille.montreal@example.com',
                'country_residence' => 'Canada',
                'city_residence' => 'Montréal',
                'first_name' => 'Camille',
                'last_name' => 'Rousseau',
                'bio' => 'Étudiante en master à McGill University.',
            ],
            [
                'name' => 'Lucas_Madrid',
                'email' => 'lucas.madrid@example.com',
                'country_residence' => 'Espagne',
                'city_residence' => 'Madrid',
                'first_name' => 'Lucas',
                'last_name' => 'Garcia',
                'bio' => 'Professeur de français dans une école internationale.',
            ],
            [
                'name' => 'Emma_Singapore',
                'email' => 'emma.singapore@example.com',
                'country_residence' => 'Singapour',
                'city_residence' => 'Singapour',
                'first_name' => 'Emma',
                'last_name' => 'Lefebvre',
                'bio' => 'Analyste financière dans le secteur bancaire asiatique.',
            ],
            [
                'name' => 'Thomas_London',
                'email' => 'thomas.london@example.com',
                'country_residence' => 'Royaume-Uni',
                'city_residence' => 'Londres',
                'first_name' => 'Thomas',
                'last_name' => 'Dubois',
                'bio' => 'Journaliste freelance couvrant l\'actualité européenne.',
            ],
            [
                'name' => 'Chloé_Tokyo',
                'email' => 'chloe.tokyo@example.com',
                'country_residence' => 'Japon',
                'city_residence' => 'Osaka',
                'first_name' => 'Chloé',
                'last_name' => 'Petit',
                'bio' => 'Traductrice et guide touristique au Japon.',
            ],
        ];

        foreach ($testUsers as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'), // Mot de passe par défaut
                'country_residence' => $userData['country_residence'],
                'city_residence' => $userData['city_residence'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'bio' => $userData['bio'],
                'email_verified_at' => now(),
                'created_at' => now()->subDays(rand(1, 365)), // Dates de création aléatoires
                'updated_at' => now(),
            ]);
        }

        $this->command->info('10 utilisateurs de test créés avec succès !');
        $this->command->info('Mot de passe par défaut : password123');
    }
}
