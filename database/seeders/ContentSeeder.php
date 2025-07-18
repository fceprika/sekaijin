<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Country;
use App\Models\Event;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Thailand only for focused content
        $thailand = Country::where('slug', 'thailande')->first();

        if (! $thailand) {
            $this->command->warn('Thailand country not found. Please run the countries seeder first.');

            return;
        }

        // Get or create sample users
        $admin = User::updateOrCreate(
            ['email' => 'admin@sekaijin.fr'],
            [
                'name' => 'admin_sekaijin',
                'country_residence' => 'Thaïlande',
                'role' => 'admin',
                'password' => bcrypt('password123'),
                'is_verified' => true,
            ]
        );

        $marie = User::updateOrCreate(
            ['email' => 'marie.dupont@email.fr'],
            [
                'name' => 'marie_dupont',
                'first_name' => 'Marie',
                'last_name' => 'Dupont',
                'country_residence' => 'Thaïlande',
                'city_residence' => 'Bangkok',
                'role' => 'ambassador',
                'password' => bcrypt('password123'),
                'bio' => 'Expatriée en Thaïlande depuis 3 ans, passionnée de voyage et de culture asiatique.',
                'is_verified' => true,
            ]
        );

        $pierre = User::updateOrCreate(
            ['email' => 'pierre.dupont@email.fr'],
            [
                'name' => 'pierre_dupont',
                'first_name' => 'Pierre',
                'last_name' => 'Dupont',
                'country_residence' => 'Thaïlande',
                'city_residence' => 'Phuket',
                'role' => 'premium',
                'password' => bcrypt('password123'),
                'bio' => 'Entrepreneur digital nomad à Phuket depuis 4 ans.',
                'is_verified' => true,
            ]
        );

        $sophie = User::updateOrCreate(
            ['email' => 'sophie.bernard@email.fr'],
            [
                'name' => 'sophie_bernard',
                'first_name' => 'Sophie',
                'last_name' => 'Bernard',
                'country_residence' => 'Thaïlande',
                'city_residence' => 'Chiang Mai',
                'role' => 'free',
                'password' => bcrypt('password123'),
                'bio' => 'Professeure de français en ligne depuis Chiang Mai.',
                'is_verified' => false,
            ]
        );

        $lucas = User::updateOrCreate(
            ['email' => 'lucas.martin@email.fr'],
            [
                'name' => 'lucas_martin',
                'first_name' => 'Lucas',
                'last_name' => 'Martin',
                'country_residence' => 'Thaïlande',
                'city_residence' => 'Koh Samui',
                'role' => 'free',
                'password' => bcrypt('password123'),
                'bio' => 'Photographe voyageur basé à Koh Samui.',
                'is_verified' => false,
            ]
        );

        // Create News for Thailand
        News::create([
            'title' => 'Nouvelle réglementation visa pour la Thaïlande',
            'excerpt' => 'Le gouvernement thaïlandais a annoncé de nouvelles mesures concernant les visas long séjour pour les expatriés français.',
            'content' => 'Le gouvernement thaïlandais a annoncé de nouvelles mesures concernant les visas long séjour pour les expatriés français. Ces changements entreront en vigueur le mois prochain et simplifient grandement les démarches administratives.',
            'category' => 'administrative',
            'country_id' => $thailand->id,
            'author_id' => $admin->id,
            'is_featured' => true,
            'published_at' => Carbon::now()->subHours(2),
        ]);

        News::create([
            'title' => 'Nouveau consulat français ouvert à Chiang Mai',
            'excerpt' => 'Pour faciliter les démarches administratives des expatriés français, un nouveau consulat a ouvert ses portes.',
            'content' => 'Pour faciliter les démarches administratives des expatriés français, un nouveau consulat a ouvert ses portes dans la région. Découvrez les services disponibles et les horaires d\'ouverture.',
            'category' => 'vie-pratique',
            'country_id' => $thailand->id,
            'author_id' => $marie->id,
            'published_at' => Carbon::now()->subDay(),
        ]);

        News::create([
            'title' => 'Mousson 2025 : conseils pour les expatriés',
            'excerpt' => 'Préparez-vous à la saison des pluies avec nos conseils pratiques pour vivre sereinement en Thaïlande.',
            'content' => 'La saison des pluies approche en Thaïlande. Découvrez tous nos conseils pour bien vivre cette période : équipements indispensables, activités à faire, précautions sanitaires et astuces pour profiter de cette saison unique.',
            'category' => 'vie-pratique',
            'country_id' => $thailand->id,
            'author_id' => $pierre->id,
            'published_at' => Carbon::now()->subDays(2),
        ]);

        News::create([
            'title' => 'Nouvelle école française accréditée à Bangkok',
            'excerpt' => 'Une nouvelle école suit le programme français pour les enfants d\'expatriés.',
            'content' => 'Une nouvelle école française a été accréditée par l\'AEFE à Bangkok. Inscriptions ouvertes pour la rentrée 2025, programme bilingue français-anglais disponible.',
            'category' => 'culture',
            'country_id' => $thailand->id,
            'author_id' => $sophie->id,
            'published_at' => Carbon::now()->subDays(3),
        ]);

        // Create Articles for Thailand
        Article::create([
            'title' => 'Mon premier mois en Thaïlande : entre choc culturel et émerveillements',
            'slug' => 'premier-mois-thailande-choc-culturel',
            'excerpt' => 'Récit authentique d\'une expatriation récente : les difficultés rencontrées et les belles surprises.',
            'content' => 'Récit authentique d\'une expatriation récente : les difficultés rencontrées, les belles surprises et tous les conseils que j\'aurais aimé avoir avant de partir. Un témoignage sincère pour préparer votre propre aventure.',
            'category' => 'témoignage',
            'country_id' => $thailand->id,
            'author_id' => $marie->id,
            'is_featured' => true,
            'reading_time' => 5,
            'likes' => 23,
            'views' => 156,
            'published_at' => Carbon::now()->subDay(),
        ]);

        Article::create([
            'title' => 'Guide complet pour s\'installer en Thaïlande',
            'slug' => 'guide-complet-installation-thailande',
            'excerpt' => 'Tous les conseils pratiques pour réussir votre expatriation : logement, banque, santé, transport...',
            'content' => 'Tous les conseils pratiques pour réussir votre expatriation : logement, banque, santé, transport. Un guide exhaustif pour les nouveaux arrivants.',
            'category' => 'guide-pratique',
            'country_id' => $thailand->id,
            'author_id' => $marie->id,
            'reading_time' => 12,
            'likes' => 45,
            'views' => 278,
            'published_at' => Carbon::now()->subDays(3),
        ]);

        Article::create([
            'title' => 'Les meilleures îles de Thaïlande pour les expatriés',
            'slug' => 'meilleures-iles-thailande-expatries',
            'excerpt' => 'Koh Samui, Phuket, Koh Phangan... Découvrez les avantages et inconvénients de chaque île.',
            'content' => 'Comparatif détaillé des meilleures îles thaïlandaises pour s\'installer : coût de la vie, communauté expat, internet, activités. Mon retour d\'expérience après avoir vécu sur 4 îles différentes.',
            'category' => 'voyage',
            'country_id' => $thailand->id,
            'author_id' => $lucas->id,
            'reading_time' => 10,
            'likes' => 34,
            'views' => 267,
            'published_at' => Carbon::now()->subDays(5),
        ]);

        Article::create([
            'title' => 'Cuisine thaï pour débutants : mes 10 plats préférés',
            'slug' => 'cuisine-thai-debutants-10-plats',
            'excerpt' => 'Découvrez la richesse de la gastronomie thaïlandaise avec mes recommandations testées et approuvées.',
            'content' => 'Guide gastronomique pour expatriés : mes 10 plats thaï favoris, où les trouver, comment les commander et quelques recettes faciles pour les faire chez soi.',
            'category' => 'gastronomie',
            'country_id' => $thailand->id,
            'author_id' => $marie->id,
            'reading_time' => 7,
            'likes' => 56,
            'views' => 423,
            'published_at' => Carbon::now()->subWeek(),
        ]);

        // Create Events for Thailand
        Event::create([
            'title' => 'Grand Apéro Français de Mars',
            'slug' => 'apero-francais-mars-2025',
            'description' => 'Retrouvons-nous pour un apéro convivial au cœur de Bangkok !',
            'full_description' => 'Retrouvons-nous pour un apéro convivial au cœur de Bangkok ! L\'occasion parfaite de rencontrer de nouveaux expatriés français et partager nos expériences.',
            'category' => 'networking',
            'country_id' => $thailand->id,
            'organizer_id' => $marie->id,
            'start_date' => Carbon::now()->addDays(10)->setTime(19, 0),
            'end_date' => Carbon::now()->addDays(10)->setTime(22, 0),
            'location' => 'Restaurant Le Bistrot',
            'address' => 'Sukhumvit Road, Bangkok',
            'price' => 15.00,
            'max_participants' => 30,
            'current_participants' => 24,
            'is_featured' => true,
        ]);

        Event::create([
            'title' => 'Conférence : Fiscalité pour expatriés',
            'slug' => 'conference-fiscalite-expat',
            'description' => 'Tout savoir sur vos obligations fiscales en tant qu\'expatrié français.',
            'category' => 'conférence',
            'country_id' => $thailand->id,
            'organizer_id' => $admin->id,
            'start_date' => Carbon::now()->addWeeks(2)->setTime(14, 0),
            'end_date' => Carbon::now()->addWeeks(2)->setTime(16, 0),
            'is_online' => true,
            'online_link' => 'https://zoom.us/j/123456789',
            'price' => 0,
            'max_participants' => 50,
            'current_participants' => 12,
        ]);

        Event::create([
            'title' => 'Weekend découverte à Chiang Mai',
            'slug' => 'weekend-decouverte-chiang-mai',
            'description' => 'Escapade organisée pour découvrir la capitale du Nord de la Thaïlande.',
            'full_description' => 'Weekend organisé pour découvrir Chiang Mai : temples, marchés locaux, cours de cuisine thaï et rencontres avec la communauté française locale.',
            'category' => 'voyage',
            'country_id' => $thailand->id,
            'organizer_id' => $pierre->id,
            'start_date' => Carbon::now()->addWeeks(3)->setTime(8, 0),
            'end_date' => Carbon::now()->addWeeks(3)->addDays(2)->setTime(18, 0),
            'location' => 'Chiang Mai',
            'address' => 'Centre-ville de Chiang Mai',
            'price' => 120.00,
            'max_participants' => 15,
            'current_participants' => 8,
        ]);

        Event::create([
            'title' => 'Cours de thaï pour francophones',
            'slug' => 'cours-thai-francophones',
            'description' => 'Apprenez les bases du thaï avec une méthode adaptée aux francophones.',
            'full_description' => 'Cours de thaï débutant spécialement conçu pour les expatriés français. Méthode ludique et pratique pour apprendre les expressions essentielles du quotidien.',
            'category' => 'apprentissage',
            'country_id' => $thailand->id,
            'organizer_id' => $sophie->id,
            'start_date' => Carbon::now()->addMonth()->setTime(19, 0),
            'end_date' => Carbon::now()->addMonth()->setTime(21, 0),
            'location' => 'Centre culturel français',
            'address' => 'Silom, Bangkok',
            'price' => 25.00,
            'max_participants' => 20,
            'current_participants' => 14,
        ]);
    }
}
