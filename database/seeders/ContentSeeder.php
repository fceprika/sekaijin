<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\User;
use App\Models\News;
use App\Models\Article;
use App\Models\Event;
use Carbon\Carbon;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get countries
        $thailand = Country::where('slug', 'thailande')->first();
        $japan = Country::where('slug', 'japon')->first();
        
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

        $jean = User::updateOrCreate(
            ['email' => 'jean.martin@email.fr'],
            [
                'name' => 'jean_martin',
                'first_name' => 'Jean',
                'last_name' => 'Martin',
                'country_residence' => 'Japon',
                'city_residence' => 'Tokyo',
                'role' => 'premium',
                'password' => bcrypt('password123'),
                'bio' => 'Développeur web freelance basé à Tokyo depuis 2 ans.',
                'is_verified' => true,
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

        // Create News for Japan
        News::create([
            'title' => 'Festival de la francophonie 2025 à Tokyo',
            'excerpt' => 'Le plus grand événement culturel français de l\'année au Japon approche.',
            'content' => 'Le plus grand événement culturel français de l\'année au Japon approche. Programme détaillé, invités spéciaux et informations pratiques pour ne rien manquer.',
            'category' => 'culture',
            'country_id' => $japan->id,
            'author_id' => $jean->id,
            'published_at' => Carbon::now()->subWeek(),
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

        // Create Articles for Japan
        Article::create([
            'title' => 'Travailler en remote depuis le Japon',
            'slug' => 'travailler-remote-japon',
            'excerpt' => 'Mon expérience du télétravail international : défis, avantages et conseils pratiques.',
            'content' => 'Mon expérience du télétravail international depuis Tokyo : les défis techniques, les avantages culturels et tous mes conseils pratiques pour réussir.',
            'category' => 'travail',
            'country_id' => $japan->id,
            'author_id' => $jean->id,
            'reading_time' => 8,
            'likes' => 28,
            'views' => 198,
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

        // Create Events for Japan
        Event::create([
            'title' => 'Soirée cinéma français',
            'slug' => 'soiree-cinema-francais',
            'description' => 'Projection d\'un film français récent suivi d\'un débat et d\'un pot.',
            'category' => 'culturel',
            'country_id' => $japan->id,
            'organizer_id' => $jean->id,
            'start_date' => Carbon::now()->addWeeks(3)->setTime(18, 30),
            'end_date' => Carbon::now()->addWeeks(3)->setTime(21, 0),
            'location' => 'Cinéma Toho Shibuya',
            'address' => 'Shibuya, Tokyo',
            'price' => 8.00,
            'max_participants' => 25,
            'current_participants' => 18,
        ]);

        Event::create([
            'title' => 'Atelier cuisine française',
            'slug' => 'atelier-cuisine-francaise',
            'description' => 'Apprenez à cuisiner des plats traditionnels avec un chef français.',
            'category' => 'gastronomie',
            'country_id' => $japan->id,
            'organizer_id' => $jean->id,
            'start_date' => Carbon::now()->addMonth()->setTime(19, 0),
            'end_date' => Carbon::now()->addMonth()->setTime(22, 0),
            'location' => 'École culinaire française',
            'address' => 'Roppongi, Tokyo',
            'price' => 45.00,
            'max_participants' => 12,
            'current_participants' => 6,
        ]);
    }
}
