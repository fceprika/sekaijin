<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\User;
use App\Models\News;
use App\Models\Article;
use App\Models\Event;

class ThailandContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer la Thaïlande
        $thailand = Country::create([
            'name_fr' => 'Thaïlande',
            'slug' => 'thailande',
            'emoji' => '🇹🇭',
            'description' => 'La Thaïlande, terre de sourires et de temples dorés, accueille une communauté française dynamique. Découvrez Bangkok, Chiang Mai, Phuket et les îles paradisiaques à travers les yeux des expatriés français.',
            'latitude' => 15.8700,
            'longitude' => 100.9925,
            'population' => 69950000,
            'currency' => 'Baht thaïlandais (THB)',
            'timezone' => 'UTC+7',
            'language' => 'Thaï',
            'is_active' => true,
        ]);

        // Créer un utilisateur admin pour être l'auteur du contenu
        $admin = User::firstOrCreate([
            'email' => 'admin@sekaijin.fr'
        ], [
            'name' => 'Admin',
            'first_name' => 'Équipe',
            'last_name' => 'Sekaijin',
            'country_residence' => 'Thaïlande',
            'city_residence' => 'Bangkok',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
            'is_verified' => true,
            'country_id' => $thailand->id,
        ]);

        // 1. Actualité : Ouverture du site
        News::create([
            'title' => 'Sekaijin.fr est désormais disponible !',
            'excerpt' => 'Découvrez la nouvelle plateforme dédiée aux expatriés français en Thaïlande et dans le monde entier.',
            'content' => "
<p>Nous sommes ravis de vous annoncer l'ouverture officielle de <strong>Sekaijin.fr</strong>, votre nouvelle plateforme communautaire dédiée aux expatriés français !</p>

<h3>🌍 Une communauté mondiale</h3>
<p>Sekaijin (世界人 - \"citoyen du monde\" en japonais) a pour mission de connecter les Français expatriés aux quatre coins du monde. Que vous soyez en Thaïlande, au Japon, en Australie ou ailleurs, vous trouverez ici votre communauté.</p>

<h3>✨ Nos fonctionnalités</h3>
<ul>
<li><strong>Profils personnalisés</strong> : Créez votre profil d'expatrié avec vos informations, réseaux sociaux et localisation</li>
<li><strong>Carte interactive</strong> : Découvrez les membres de votre région sur notre carte mondiale</li>
<li><strong>Contenu par pays</strong> : Actualités, articles de blog et événements spécifiques à votre pays de résidence</li>
<li><strong>Système de rôles</strong> : Devenez ambassadeur de votre région et partagez votre expertise</li>
</ul>

<h3>🇹🇭 Commençons par la Thaïlande</h3>
<p>Nous lançons la plateforme avec un focus sur la <strong>Thaïlande</strong>, pays qui accueille une importante communauté française. Bangkok, Chiang Mai, Phuket, Koh Samui... partagez vos expériences et découvertes !</p>

<p>Rejoignez-nous dès maintenant et contribuez à créer la plus grande communauté d'expatriés français au monde !</p>
            ",
            'category' => 'annonce',
            'country_id' => $thailand->id,
            'author_id' => $admin->id,
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
        ]);

        // 2. Article de blog : Comment utiliser Sekaijin.fr
        Article::create([
            'title' => 'Comment utiliser Sekaijin.fr : Guide complet pour les nouveaux membres',
            'slug' => 'comment-utiliser-sekaijin-guide-complet',
            'excerpt' => 'Découvrez toutes les fonctionnalités de Sekaijin.fr et apprenez à tirer le meilleur parti de notre plateforme communautaire.',
            'content' => "
<p>Bienvenue sur Sekaijin.fr ! Cette plateforme a été créée spécialement pour vous, expatriés français, afin de vous connecter avec d'autres membres de notre communauté mondiale.</p>

<h2>🔐 1. Créer votre compte</h2>
<p>L'inscription est simple et rapide :</p>
<ul>
<li>Cliquez sur <strong>\"Rejoindre la communauté\"</strong> depuis la page d'accueil</li>
<li>Renseignez votre pseudo, email, pays de résidence et mot de passe</li>
<li>Acceptez les conditions d'utilisation</li>
<li>Vous êtes automatiquement connecté après l'inscription !</li>
</ul>

<h2>👤 2. Compléter votre profil</h2>
<p>Un profil complet vous permettra d'être mieux trouvé par les autres membres :</p>
<ul>
<li><strong>Informations personnelles</strong> : Prénom, nom, date de naissance, téléphone</li>
<li><strong>Localisation</strong> : Pays et ville de résidence, pays de destination si vous êtes en France</li>
<li><strong>Réseaux sociaux</strong> : YouTube, Instagram, TikTok, LinkedIn, Twitter, Facebook, Telegram</li>
<li><strong>Bio</strong> : Présentez-vous en quelques mots</li>
<li><strong>Géolocalisation</strong> : Acceptez le partage de position pour apparaître sur la carte</li>
</ul>

<h2>🗺️ 3. Explorer la carte interactive</h2>
<p>La carte mondiale vous permet de :</p>
<ul>
<li>Découvrir les membres près de chez vous</li>
<li>Voir la densité de Français expatriés par pays</li>
<li>Cliquer sur les marqueurs pour voir les profils</li>
<li>Filtrer par pays ou région</li>
</ul>

<h2>🏠 4. Naviguer par pays</h2>
<p>Chaque pays dispose de sa propre section avec :</p>
<ul>
<li><strong>Actualités</strong> : Informations administratives, vie pratique, culture, économie</li>
<li><strong>Blog</strong> : Articles de témoignages, guides pratiques, conseils travail et lifestyle</li>
<li><strong>Événements</strong> : Rencontres, formations, activités culturelles et sociales</li>
</ul>

<h2>💬 5. Interagir avec la communauté</h2>
<ul>
<li><strong>Liker les articles</strong> qui vous intéressent</li>
<li><strong>Partager vos expériences</strong> via les commentaires</li>
<li><strong>Contacter directement</strong> les membres via leurs profils publics</li>
<li><strong>Participer aux événements</strong> organisés près de chez vous</li>
</ul>

<h2>🎯 6. Devenir ambassadeur</h2>
<p>Les membres actifs peuvent devenir ambassadeurs de leur région et :</p>
<ul>
<li>Publier des actualités et articles</li>
<li>Organiser des événements locaux</li>
<li>Modérer les contenus de leur zone</li>
<li>Accueillir les nouveaux arrivants</li>
</ul>

<h2>🔐 7. Vie privée et sécurité</h2>
<p>Votre sécurité est notre priorité :</p>
<ul>
<li>Vos coordonnées exactes sont floutées sur la carte (rayon de ~10km)</li>
<li>Vous contrôlez la visibilité de vos informations</li>
<li>Possibilité de masquer votre profil de la carte</li>
<li>Signalement possible des contenus inappropriés</li>
</ul>

<h2>📱 8. Optimisation mobile</h2>
<p>Sekaijin.fr est entièrement responsive :</p>
<ul>
<li>Navigation fluide sur smartphone et tablette</li>
<li>Carte interactive optimisée tactile</li>
<li>Interface adaptée aux écrans mobiles</li>
</ul>

<h2>💡 Conseils pour bien commencer</h2>
<ul>
<li><strong>Complétez votre profil</strong> dès l'inscription</li>
<li><strong>Ajoutez une photo</strong> pour humaniser votre profil</li>
<li><strong>Explorez votre région</strong> via la section pays</li>
<li><strong>Participez aux discussions</strong> et événements</li>
<li><strong>Partagez vos bons plans</strong> avec la communauté</li>
</ul>

<p>Bienvenue dans la famille Sekaijin ! N'hésitez pas à nous contacter si vous avez des questions. 🌏</p>
            ",
            'category' => 'guide-pratique',
            'country_id' => $thailand->id,
            'author_id' => $admin->id,
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now(),
            'views' => 0,
            'likes' => 0,
            'reading_time' => 8,
        ]);

        // 3. Événement : Ouverture du site
        Event::create([
            'title' => 'Lancement officiel de Sekaijin.fr',
            'slug' => 'lancement-officiel-sekaijin-thailande',
            'description' => 'Célébrons ensemble l\'ouverture de la plateforme communautaire des expatriés français !',
            'full_description' => "
<p>🎉 <strong>Événement historique !</strong> Nous célébrons aujourd'hui le lancement officiel de Sekaijin.fr, votre nouvelle plateforme communautaire dédiée aux expatriés français.</p>

<h3>🌟 Au programme</h3>
<ul>
<li><strong>10h00 - 10h30</strong> : Présentation officielle de la plateforme</li>
<li><strong>10h30 - 11h00</strong> : Démonstration des fonctionnalités principales</li>
<li><strong>11h00 - 11h30</strong> : Session questions/réponses avec l'équipe</li>
<li><strong>11h30 - 12h00</strong> : Networking et échanges entre membres</li>
<li><strong>12h00 - 13h00</strong> : Apéritif de lancement</li>
</ul>

<h3>🎯 Pourquoi participer ?</h3>
<ul>
<li><strong>Être parmi les premiers</strong> à découvrir toutes les fonctionnalités</li>
<li><strong>Rencontrer l'équipe</strong> fondatrice de Sekaijin</li>
<li><strong>Poser vos questions</strong> et proposer vos idées</li>
<li><strong>Networker</strong> avec d'autres expatriés français</li>
<li><strong>Devenir ambassadeur</strong> de votre région</li>
</ul>

<h3>📋 Informations pratiques</h3>
<ul>
<li><strong>Date</strong> : Dimanche 6 juillet 2025</li>
<li><strong>Horaire</strong> : 10h00 - 13h00 (heure de Bangkok)</li>
<li><strong>Format</strong> : Événement en ligne via Zoom</li>
<li><strong>Langue</strong> : Français</li>
<li><strong>Tarif</strong> : Gratuit</li>
</ul>

<h3>🎁 Cadeaux de bienvenue</h3>
<p>Tous les participants recevront :</p>
<ul>
<li>Un badge \"Membre fondateur\" sur leur profil</li>
<li>L'accès prioritaire aux nouvelles fonctionnalités</li>
<li>Un guide PDF complet d'utilisation de la plateforme</li>
<li>La possibilité de devenir ambassadeur de leur région</li>
</ul>

<h3>👥 Qui peut participer ?</h3>
<ul>
<li>Tous les expatriés français, où qu'ils soient dans le monde</li>
<li>Les Français envisageant une expatriation</li>
<li>Les familles franco-étrangères</li>
<li>Les professionnels travaillant avec des expatriés</li>
</ul>

<p><strong>Inscription obligatoire</strong> - Places limitées à 100 participants pour garantir la qualité des échanges.</p>

<p>Rejoignez-nous pour faire partie de l'histoire de Sekaijin ! 🌍</p>
            ",
            'category' => 'communautaire',
            'country_id' => $thailand->id,
            'organizer_id' => $admin->id,
            'start_date' => '2025-07-06 10:00:00',
            'end_date' => '2025-07-06 13:00:00',
            'location' => 'En ligne',
            'address' => null,
            'is_online' => true,
            'online_link' => 'https://zoom.us/j/sekaijin-launch',
            'price' => 0,
            'max_participants' => 100,
            'current_participants' => 0,
            'is_published' => true,
            'is_featured' => true,
        ]);

        echo "✅ Données de contenu pour la Thaïlande créées avec succès !\n";
        echo "🇹🇭 Pays : Thaïlande\n";
        echo "📰 Actualité : Ouverture du site\n";
        echo "📝 Article : Guide d'utilisation\n";
        echo "🎉 Événement : Lancement officiel\n";
    }
}