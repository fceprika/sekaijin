<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Country;
use App\Models\Event;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Créer des pays de test
        $thailand = Country::firstOrCreate([
            'slug' => 'thailande',
        ], [
            'name_fr' => 'Thaïlande',
            'emoji' => '🇹🇭',
            'description' => 'Royaume de Thaïlande - Destination prisée des expatriés français',
        ]);

        $japan = Country::firstOrCreate([
            'slug' => 'japon',
        ], [
            'name_fr' => 'Japon',
            'emoji' => '🇯🇵',
            'description' => 'Archipel du Japon - Culture riche et opportunités professionnelles',
        ]);

        $vietnam = Country::firstOrCreate([
            'slug' => 'vietnam',
        ], [
            'name_fr' => 'Vietnam',
            'emoji' => '🇻🇳',
            'description' => 'République socialiste du Vietnam - Dynamisme économique en Asie',
        ]);

        // Créer des utilisateurs de test avec différents rôles
        $admin = User::where('email', 'admin@sekaijin.fr')->first();
        if (! $admin) {
            $admin = User::create([
                'name' => 'AdminTest',
                'email' => 'admin@sekaijin.fr',
                'first_name' => 'Alexandre',
                'last_name' => 'Administrateur',
                'password' => Hash::make('password123'),
                'country_residence' => 'Thaïlande',
                'city_residence' => 'Bangkok',
                'bio' => 'Administrateur de la plateforme Sekaijin. Expatrié en Thaïlande depuis 5 ans.',
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
        }

        $ambassador = User::where('email', 'ambassadeur@sekaijin.fr')->first();
        if (! $ambassador) {
            $ambassador = User::create([
                'name' => 'AmbassadeurTH',
                'email' => 'ambassadeur@sekaijin.fr',
                'first_name' => 'Sophie',
                'last_name' => 'Ambassadrice',
                'password' => Hash::make('password123'),
                'country_residence' => 'Thaïlande',
                'city_residence' => 'Chiang Mai',
                'bio' => 'Ambassadrice Sekaijin pour la Thaïlande. Organisatrice d\'événements communautaires.',
                'role' => 'ambassador',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
        }

        $premium = User::where('email', 'premium@sekaijin.fr')->first();
        if (! $premium) {
            $premium = User::create([
                'name' => 'PremiumUser',
                'email' => 'premium@sekaijin.fr',
                'first_name' => 'Marc',
                'last_name' => 'Premium',
                'password' => Hash::make('password123'),
                'country_residence' => 'Japon',
                'city_residence' => 'Tokyo',
                'bio' => 'Membre premium passionné par la culture japonaise et la technologie.',
                'role' => 'premium',
                'is_verified' => true,
                'youtube_username' => '@marcaujapan',
                'email_verified_at' => now(),
            ]);
        }

        $members = [];
        for ($i = 1; $i <= 8; $i++) {
            $country = ['Thaïlande', 'Japon', 'Vietnam'][array_rand(['Thaïlande', 'Japon', 'Vietnam'])];
            $cities = [
                'Thaïlande' => ['Bangkok', 'Chiang Mai', 'Phuket', 'Pattaya'],
                'Japon' => ['Tokyo', 'Osaka', 'Kyoto', 'Hiroshima'],
                'Vietnam' => ['Ho Chi Minh', 'Hanoi', 'Da Nang', 'Hue'],
            ];

            $member = User::where('email', "membre{$i}@sekaijin.fr")->first();
            if (! $member) {
                $member = User::create([
                    'name' => "Membre{$i}",
                    'email' => "membre{$i}@sekaijin.fr",
                    'first_name' => ['Pierre', 'Marie', 'Jean', 'Claire', 'Paul', 'Emma', 'Louis', 'Julie'][$i - 1],
                    'last_name' => ['Dupont', 'Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert', 'Petit', 'Durand'][$i - 1],
                    'password' => Hash::make('password123'),
                    'country_residence' => $country,
                    'city_residence' => $cities[$country][array_rand($cities[$country])],
                    'bio' => "Expatrié français en {$country}. Passionné par la découverte culturelle et les nouvelles expériences.",
                    'role' => 'free',
                    'is_verified' => $i <= 5,
                    'email_verified_at' => $i <= 5 ? now() : null,
                ]);
            }
            $members[] = $member;
        }

        // Créer des actualités pour la Thaïlande
        $newsCategories = ['administrative', 'vie-pratique', 'culture', 'economie'];
        for ($i = 1; $i <= 6; $i++) {
            $existingNews = News::where('title', "Nouvelle réglementation visa pour les expatriés français #{$i}")->first();
            if (! $existingNews) {
                News::create([
                    'title' => "Nouvelle réglementation visa pour les expatriés français #{$i}",
                    'excerpt' => 'Les autorités thaïlandaises annoncent des changements importants concernant les visas de long séjour.',
                    'content' => "Contenu détaillé de l'actualité #{$i}. Les autorités thaïlandaises ont annoncé hier de nouvelles mesures concernant les visas de long séjour pour les expatriés. Ces changements, qui entreront en vigueur le mois prochain, visent à simplifier les démarches administratives tout en renforçant les contrôles.\n\nPrincipaux points :\n- Nouvelle procédure en ligne\n- Documents requis mis à jour\n- Délais de traitement réduits\n- Frais ajustés",
                    'category' => $newsCategories[array_rand($newsCategories)],
                    'country_id' => $thailand->id,
                    'author_id' => [$admin->id, $ambassador->id][array_rand([$admin->id, $ambassador->id])],
                    'is_featured' => $i <= 2,
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 30)),
                    'views' => rand(50, 500),
                ]);
            }
        }

        // Créer des articles pour la Thaïlande
        $articleCategories = ['témoignage', 'guide-pratique', 'travail', 'lifestyle', 'cuisine'];
        for ($i = 1; $i <= 8; $i++) {
            $existingArticle = Article::where('slug', "article-thailande-{$i}")->first();
            if (! $existingArticle) {
                Article::create([
                    'title' => "Mon expérience d'expatriation en Thaïlande #{$i}",
                    'slug' => "article-thailande-{$i}",
                    'excerpt' => 'Témoignage authentique sur la vie d\'expatrié français en Thaïlande.',
                    'content' => "Article détaillé #{$i}. Voici mon témoignage après {$i} années passées en Thaïlande en tant qu'expatrié français.\n\n## Les défis du début\n\nLes premiers mois n'ont pas été faciles. L'adaptation culturelle, la barrière de la langue et les démarches administratives représentaient autant d'obstacles à surmonter.\n\n## Les joies du quotidien\n\nAujourd'hui, je ne peux plus imaginer vivre ailleurs. La chaleur humaine des Thaïlandais, la richesse culturelle et la qualité de vie sont incomparables.\n\n## Mes conseils\n\n1. Apprenez quelques bases de thaï\n2. Respectez les coutumes locales\n3. Rejoignez la communauté française\n4. Explorez au-delà de Bangkok",
                    'category' => $articleCategories[array_rand($articleCategories)],
                    'country_id' => $thailand->id,
                    'author_id' => $members[array_rand($members)]->id,
                    'is_featured' => $i <= 3,
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 60)),
                    'views' => rand(100, 1000),
                    'likes' => rand(5, 50),
                    'reading_time' => rand(3, 12),
                ]);
            }
        }

        // Créer des événements pour la Thaïlande
        $eventCategories = ['networking', 'culturel', 'professionnel', 'loisirs', 'gastronomie'];
        for ($i = 1; $i <= 5; $i++) {
            $startDate = Carbon::now()->addDays(rand(1, 90));
            $isOnline = $i > 3;

            $existingEvent = Event::where('slug', "evenement-thailande-{$i}")->first();
            if (! $existingEvent) {
                Event::create([
                    'title' => "Événement communauté française #{$i}",
                    'slug' => "evenement-thailande-{$i}",
                    'description' => 'Rencontre mensuelle de la communauté française en Thaïlande.',
                    'full_description' => "Description complète de l'événement #{$i}.\n\nRejoignez-nous pour une soirée conviviale entre expatriés français en Thaïlande. Au programme :\n\n- Apéritif de bienvenue\n- Présentation des nouveaux arrivants\n- Échanges d'expériences\n- Networking professionnel\n- Informations pratiques\n\nCet événement est l'occasion idéale pour créer des liens et partager vos expériences d'expatriation.",
                    'category' => $eventCategories[array_rand($eventCategories)],
                    'country_id' => $thailand->id,
                    'organizer_id' => $ambassador->id,
                    'start_date' => $startDate,
                    'end_date' => $startDate->copy()->addHours(3),
                    'location' => $isOnline ? null : ['Bangkok', 'Chiang Mai', 'Phuket'][array_rand(['Bangkok', 'Chiang Mai', 'Phuket'])],
                    'address' => $isOnline ? null : "Restaurant Le Français, {$i}0 Sukhumvit Road, Bangkok",
                    'is_online' => $isOnline,
                    'online_link' => $isOnline ? 'https://zoom.us/j/123456789' : null,
                    'price' => $i % 2 === 0 ? 0 : rand(500, 2000),
                    'max_participants' => rand(20, 100),
                    'current_participants' => rand(5, 30),
                    'is_published' => true,
                    'is_featured' => $i <= 2,
                ]);
            }
        }

        // Créer quelques contenus pour le Japon
        for ($i = 1; $i <= 3; $i++) {
            $existingJapanNews = News::where('title', "Nouvelles opportunités d'emploi au Japon #{$i}")->first();
            if (! $existingJapanNews) {
                News::create([
                    'title' => "Nouvelles opportunités d'emploi au Japon #{$i}",
                    'excerpt' => 'Le marché de l\'emploi japonais s\'ouvre davantage aux talents étrangers.',
                    'content' => "Contenu de l'actualité japonaise #{$i}. Le gouvernement japonais continue ses efforts pour attirer les talents étrangers...",
                    'category' => $newsCategories[array_rand($newsCategories)],
                    'country_id' => $japan->id,
                    'author_id' => $premium->id,
                    'is_featured' => $i === 1,
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 20)),
                    'views' => rand(30, 300),
                ]);
            }

            $existingJapanArticle = Article::where('slug', "article-japon-{$i}")->first();
            if (! $existingJapanArticle) {
                Article::create([
                    'title' => "Vivre et travailler à Tokyo #{$i}",
                    'slug' => "article-japon-{$i}",
                    'excerpt' => 'Guide pratique pour s\'installer dans la capitale japonaise.',
                    'content' => "Article sur la vie à Tokyo #{$i}. Tokyo est une ville fascinante qui offre de nombreuses opportunités...",
                    'category' => $articleCategories[array_rand($articleCategories)],
                    'country_id' => $japan->id,
                    'author_id' => $premium->id,
                    'is_featured' => $i === 1,
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 40)),
                    'views' => rand(80, 800),
                    'likes' => rand(3, 30),
                    'reading_time' => rand(4, 10),
                ]);
            }
        }

        $this->command->info('Données dummy créées avec succès !');
        $this->command->info('Utilisateurs de test :');
        $this->command->info('- admin@sekaijin.fr (Admin) - password: password123');
        $this->command->info('- ambassadeur@sekaijin.fr (Ambassadeur) - password: password123');
        $this->command->info('- premium@sekaijin.fr (Premium) - password: password123');
        $this->command->info('- membre1@sekaijin.fr à membre8@sekaijin.fr (Free) - password: password123');
    }
}
