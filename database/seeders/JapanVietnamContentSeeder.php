<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Country;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class JapanVietnamContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get countries
        $japan = Country::where('slug', 'japon')->first();
        $vietnam = Country::where('slug', 'vietnam')->first();

        // Get admin user or create one
        $admin = User::where('role', 'admin')->first();
        if (! $admin) {
            $admin = User::where('email', 'admin@sekaijin.com')->first();
            if (! $admin) {
                $admin = User::create([
                    'name' => 'Admin Sekaijin',
                    'email' => 'admin@sekaijin.com',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                    'country_residence' => 'France',
                    'is_verified' => true,
                ]);
            }
        }

        // Create news for Japan
        if ($japan) {
            News::create([
                'title' => '🎌 Bienvenue au Japon sur Sekaijin !',
                'slug' => 'bienvenue-japon-sekaijin-ouverture',
                'excerpt' => 'Grande nouvelle ! La communauté Sekaijin s\'étend au pays du Soleil Levant. Découvrez dès maintenant notre section dédiée aux expatriés français au Japon.',
                'content' => '
<p>Konnichiwa ! C\'est avec une immense joie que nous annonçons l\'ouverture officielle de la section Japon sur Sekaijin. Après des mois de préparation et grâce à vos nombreuses demandes, nous sommes enfin prêts à accueillir la communauté française du Japon.</p>

<h3>🌸 Une communauté grandissante</h3>
<p>Avec plus de 13 000 Français résidant au Japon, principalement à Tokyo, Osaka et Kyoto, notre communauté ne cesse de grandir. Que vous soyez étudiant, salarié, entrepreneur ou simplement amoureux du Japon, Sekaijin est désormais votre nouvelle maison digitale.</p>

<h3>🍣 Ce que vous trouverez dans la section Japon</h3>
<ul>
<li><strong>Actualités locales</strong> : Restez informé des dernières nouvelles concernant la communauté française au Japon</li>
<li><strong>Guides pratiques</strong> : De l\'ouverture d\'un compte bancaire à la recherche d\'appartement, nous couvrons tout</li>
<li><strong>Témoignages</strong> : Partagez votre expérience unique de vie au Japon</li>
<li><strong>Événements communautaires</strong> : Rencontres, afterworks, et activités culturelles</li>
<li><strong>Conseils carrière</strong> : Opportunités professionnelles et conseils pour travailler au Japon</li>
</ul>

<h3>🗾 Nos ambassadeurs locaux</h3>
<p>Nous recherchons activement des ambassadeurs Sekaijin dans les principales villes japonaises. Si vous êtes motivé pour animer la communauté française de votre ville, contactez-nous !</p>

<h3>🎯 Prochaines étapes</h3>
<p>Dans les semaines à venir, nous organiserons :</p>
<ul>
<li>Un webinaire de bienvenue pour tous les membres</li>
<li>Des meetups à Tokyo, Osaka et Kyoto</li>
<li>Un guide complet "Vivre au Japon en 2025"</li>
</ul>

<p>Rejoignez-nous dès maintenant et faites partie de cette aventure extraordinaire. Ensemble, créons la plus belle communauté d\'expatriés français au Japon !</p>

<p><em>Yoroshiku onegaishimasu ! 🙏</em></p>
',
                'category' => 'administrative',
                'country_id' => $japan->id,
                'author_id' => $admin->id,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
                'views' => 0,
            ]);

            // Create article for Japan
            Article::create([
                'title' => 'Mon premier mois à Tokyo : entre rêve et réalité',
                'slug' => 'premier-mois-tokyo-reve-realite',
                'excerpt' => 'Après des années à rêver du Japon, j\'ai enfin franchi le pas. Voici mon témoignage après un mois d\'immersion totale dans la vie tokyoïte.',
                'content' => '
<p>Il y a exactement un mois, je posais mes valises à l\'aéroport de Narita, le cœur battant et les yeux brillants d\'excitation. Aujourd\'hui, installée dans mon petit appartement de Shibuya, je prends enfin le temps de partager avec vous mes premières impressions de cette aventure japonaise.</p>

<h3>🏠 La recherche d\'appartement : un parcours du combattant</h3>
<p>Commençons par le plus difficile : se loger à Tokyo. Si vous pensiez que trouver un appartement à Paris était compliqué, attendez de découvrir le marché immobilier tokyoïte ! Entre le système des "key money" (reikin), les cautions (shikikin), et les frais d\'agence qui peuvent représenter jusqu\'à 3 mois de loyer, préparez votre portefeuille.</p>

<p>Mon conseil : passez par une agence spécialisée pour les étrangers comme Fontana ou Ken Corporation. Oui, c\'est plus cher, mais au moins ils parlent anglais et comprennent nos besoins spécifiques.</p>

<h3>🚃 Le système de transport : une merveille d\'efficacité</h3>
<p>Ah, le métro de Tokyo ! Au début, c\'est intimidant avec ses multiples compagnies (JR, Tokyo Metro, Toei...) et ses plans qui ressemblent à des toiles d\'araignée. Mais une fois qu\'on a compris le système, c\'est un vrai bonheur. Les trains sont ponctuels à la seconde près, propres, et le personnel est d\'une politesse exemplaire.</p>

<p>Petit tip : téléchargez l\'application Hyperdia ou Google Maps en mode hors ligne. Ça vous sauvera la vie plus d\'une fois !</p>

<h3>🍜 La nourriture : bien plus que des sushis</h3>
<p>Oubliez tout ce que vous pensez savoir sur la cuisine japonaise. Ici, c\'est un festival permanent pour les papilles ! Des petits izakayas cachés dans les ruelles de Shinjuku aux restaurants de ramen ouverts 24h/24, chaque repas est une découverte.</p>

<p>Ma plus grande surprise ? Les konbini (convenience stores). Ces supérettes ouvertes 24/7 proposent des plats préparés d\'une qualité incroyable. Un onigiri à 150 yens (environ 1€) peut facilement faire office de petit-déjeuner ou d\'en-cas.</p>

<h3>👥 Les relations sociales : patience et persévérance</h3>
<p>C\'est probablement l\'aspect le plus challenging. Les Japonais sont polis, serviables, mais créer de vraies amitiés demande du temps. Ne vous découragez pas si vos collègues restent distants au début. C\'est normal, et ça ne veut pas dire qu\'ils ne vous apprécient pas.</p>

<p>Mon astuce : rejoignez des groupes d\'expatriés sur Facebook ou Meetup. La communauté française est très active et organise régulièrement des événements. C\'est un excellent moyen de se créer un réseau tout en gardant un pied dans notre culture.</p>

<h3>💼 Le monde du travail : rigueur et respect</h3>
<p>Travailler dans une entreprise japonaise, c\'est entrer dans un autre univers. La hiérarchie est très marquée, les processus de décision peuvent sembler interminables, et les heures supplémentaires sont monnaie courante. Mais il y a aussi beaucoup de positif : le respect mutuel, l\'attention aux détails, et l\'esprit d\'équipe.</p>

<h3>🌸 Les petits bonheurs du quotidien</h3>
<p>Malgré les défis, Tokyo me surprend chaque jour :</p>
<ul>
<li>La sécurité : je peux me promener seule à 3h du matin sans crainte</li>
<li>La propreté : pas une seule poubelle dans les rues, et pourtant tout est impeccable</li>
<li>Les distributeurs automatiques : on trouve vraiment de tout, même des parapluies !</li>
<li>Les toilettes high-tech : une expérience en soi 😄</li>
<li>Les cerisiers en fleurs : même si ce n\'est pas la saison, l\'attente en vaut la peine</li>
</ul>

<h3>📝 Bilan après un mois</h3>
<p>Est-ce que c\'est facile ? Non. Est-ce que ça vaut le coup ? Mille fois oui ! Tokyo est une ville qui vous pousse hors de votre zone de confort, mais qui vous récompense par des expériences uniques et une croissance personnelle incroyable.</p>

<p>Si vous hésitez encore à franchir le pas, mon conseil est simple : lancez-vous ! Oui, il y aura des moments de solitude, de frustration face à la barrière de la langue, d\'incompréhension culturelle. Mais il y aura aussi des moments de pure magie, des rencontres inoubliables, et la fierté d\'avoir osé vivre votre rêve.</p>

<p>Tokyo m\'a déjà transformée en un mois. J\'ai hâte de voir ce que les prochains mois me réservent !</p>

<p><em>Mata ne! À bientôt pour de nouvelles aventures tokyoïtes ! 🗼</em></p>
',
                'category' => 'témoignage',
                'country_id' => $japan->id,
                'author_id' => $admin->id,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'views' => 156,
                'likes' => 23,
                'reading_time' => 8,
            ]);
        }

        // Create news for Vietnam
        if ($vietnam) {
            News::create([
                'title' => '🇻🇳 Le Vietnam rejoint la famille Sekaijin !',
                'slug' => 'vietnam-rejoint-sekaijin-ouverture',
                'excerpt' => 'Xin chào ! Nous sommes ravis d\'annoncer l\'ouverture de notre section Vietnam. Rejoignez la communauté française grandissante du pays du Dragon.',
                'content' => '
<p>Xin chào et bienvenue ! C\'est un jour spécial pour Sekaijin alors que nous ouvrons officiellement notre section dédiée au Vietnam. Terre d\'accueil pour de plus en plus de Français, le Vietnam méritait sa place sur notre plateforme.</p>

<h3>🏮 Un pays en pleine transformation</h3>
<p>Le Vietnam attire chaque année davantage d\'expatriés français, séduits par son dynamisme économique, sa richesse culturelle et sa qualité de vie. De Hanoï la millénaire à Hô Chi Minh-Ville la trépidante, en passant par les plages paradisiaques de Da Nang, notre communauté s\'épanouit aux quatre coins du pays.</p>

<h3>🌏 Pourquoi une section Vietnam sur Sekaijin ?</h3>
<p>Avec près de 10 000 Français résidant au Vietnam et des milliers d\'autres qui s\'y rendent chaque année pour affaires ou plaisir, il était temps de créer un espace dédié à notre communauté. Voici ce que nous vous proposons :</p>

<ul>
<li><strong>Informations pratiques</strong> : Visa, permis de travail, système de santé, tout ce qu\'il faut savoir</li>
<li><strong>Opportunités professionnelles</strong> : Le Vietnam est en plein boom, profitez-en !</li>
<li><strong>Vie quotidienne</strong> : Où vivre, comment se déplacer, où faire ses courses</li>
<li><strong>Culture et loisirs</strong> : Découvrir la richesse culturelle vietnamienne</li>
<li><strong>Réseau d\'entraide</strong> : Connectez-vous avec d\'autres Français sur place</li>
</ul>

<h3>🏙️ Focus sur les principales villes</h3>
<p><strong>Hô Chi Minh-Ville (Saigon)</strong> : Le poumon économique du pays accueille la plus grande communauté française. Entre tradition et modernité, la ville offre un cadre de vie unique.</p>

<p><strong>Hanoï</strong> : La capitale politique séduit par son charme colonial et sa vie culturelle intense. Parfaite pour ceux qui recherchent l\'authenticité vietnamienne.</p>

<p><strong>Da Nang</strong> : La ville côtière montante attire de plus en plus de digital nomads et d\'entrepreneurs français grâce à sa qualité de vie exceptionnelle.</p>

<h3>🤝 Rejoignez l\'aventure</h3>
<p>Que vous soyez déjà installé au Vietnam ou que vous prépariez votre expatriation, Sekaijin Vietnam est votre nouvelle plateforme de référence. Partagez vos expériences, posez vos questions, créez des connexions !</p>

<h3>📅 Événements à venir</h3>
<ul>
<li>Meetup de lancement à Saigon (15 juillet)</li>
<li>Apéro networking à Hanoï (22 juillet)</li>
<li>Beach party communautaire à Da Nang (29 juillet)</li>
</ul>

<p>Le Vietnam vous tend les bras, et Sekaijin est là pour vous accompagner dans cette magnifique aventure. Ensemble, construisons une communauté française forte et solidaire au pays du Dragon !</p>

<p><em>Hẹn gặp lại ! À très bientôt sur Sekaijin Vietnam ! 🌺</em></p>
',
                'category' => 'administrative',
                'country_id' => $vietnam->id,
                'author_id' => $admin->id,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
                'views' => 0,
            ]);

            // Create article for Vietnam
            Article::create([
                'title' => 'Digital nomad à Da Nang : le guide complet pour s\'installer',
                'slug' => 'digital-nomad-da-nang-guide-complet',
                'excerpt' => 'Da Nang est devenue THE destination pour les digital nomads. Après 6 mois sur place, je partage tous mes tips pour réussir votre installation.',
                'content' => '
<p>Salut la communauté ! Si vous me suivez sur Instagram (@frenchie_in_danang), vous savez que j\'ai posé mes valises (et mon MacBook) à Da Nang il y a 6 mois. Cette ville côtière du centre du Vietnam est devenue un véritable paradis pour les digital nomads, et je comprends pourquoi !</p>

<h3>🏖️ Pourquoi Da Nang ?</h3>
<p>Imaginez : 30km de plages de sable blanc, une ville moderne avec toutes les commodités, un coût de la vie dérisoire, et une communauté internationale dynamique. Da Nang coche toutes les cases du digital nomad en quête du spot parfait.</p>

<p>Les avantages principaux :</p>
<ul>
<li>Internet ultra-rapide (fibre partout)</li>
<li>Coût de la vie très abordable (comptez 800-1200€/mois tout compris)</li>
<li>Plages magnifiques à 10 minutes du centre</li>
<li>Communauté expat très active</li>
<li>Position centrale pour explorer le Vietnam</li>
<li>Sécurité exemplaire</li>
</ul>

<h3>🏠 Se loger : mes quartiers préférés</h3>
<p><strong>My Khe Beach</strong> : Le spot des digital nomads par excellence. Studios modernes avec vue mer à partir de 300€/mois. L\'idéal pour commencer votre séjour.</p>

<p><strong>An Thuong</strong> : Le quartier expat avec ses cafés hipster, restaurants internationaux et co-working spaces. Un peu plus cher mais tellement pratique.</p>

<p><strong>Son Tra</strong> : Pour ceux qui cherchent plus d\'authenticité. Moins touristique, moins cher, mais toujours proche de tout.</p>

<h3>💻 Les meilleurs espaces de co-working</h3>
<p><strong>Enouvo Space</strong> : Mon QG ! Ambiance startup, communauté locale et internationale, événements réguliers. 80€/mois pour un hot desk.</p>

<p><strong>The Hive</strong> : Plus corporate mais très pro. Parfait pour les appels clients importants. 100€/mois.</p>

<p><strong>Cafés laptop-friendly</strong> : 43 Factory Coffee, Cong Caphe, The Espresso Station. WiFi rapide et café vietnamien à volonté !</p>

<h3>🛵 Se déplacer</h3>
<p>Le scooter est ROI à Da Nang. Deux options :</p>
<ul>
<li>Location longue durée : 50-80€/mois pour un Honda automatique</li>
<li>Grab (Uber local) : super pratique et pas cher pour les courts trajets</li>
</ul>

<p>Attention : le permis international est théoriquement obligatoire. Dans la pratique... c\'est vous qui voyez 😉</p>

<h3>🍜 Manger : entre street food et restaurants</h3>
<p>C\'est là que votre budget va adorer Da Nang ! Un repas dans la rue : 1-2€. Un restaurant correct : 3-5€. Un resto fancy : 10-15€. </p>

<p>Mes adresses favorites :</p>
<ul>
<li><strong>Banh Mi Ba Lan</strong> : LE meilleur banh mi de la ville (0,80€)</li>
<li><strong>Mi Quang 1A</strong> : Spécialité locale à tester absolument</li>
<li><strong>Waterfront Restaurant</strong> : Pour impressionner vos visitors</li>
<li><strong>Pizza 4P\'s</strong> : Quand l\'Europe vous manque trop</li>
</ul>

<h3>📱 Administratif et pratique</h3>
<p><strong>Visa</strong> : Le visa touristique 3 mois est parfait pour tester. Ensuite, visa business ou visa runs.</p>

<p><strong>Carte SIM</strong> : Viettel ou Vinaphone. 5€/mois pour 4GB de data.</p>

<p><strong>Banque</strong> : Pas besoin de compte local, les ATM acceptent toutes les cartes. Revolut fonctionne parfaitement.</p>

<p><strong>Santé</strong> : Family Medical Practice pour les expats. Vinmec Hospital pour les urgences.</p>

<h3>🎉 La vie sociale</h3>
<p>Da Nang a une communauté de digital nomads incroyable ! Rejoignez :</p>
<ul>
<li>Facebook : "Da Nang Digital Nomads", "Da Nang Expats"</li>
<li>Meetups hebdomadaires au Waterfront le jeudi</li>
<li>Beach volleyball le dimanche matin</li>
<li>Nomad Summit annuel en janvier</li>
</ul>

<h3>🌟 Mon budget mensuel détaillé</h3>
<ul>
<li>Logement (studio vue mer) : 350€</li>
<li>Nourriture : 200€</li>
<li>Transport (scooter + essence) : 60€</li>
<li>Co-working : 80€</li>
<li>Sorties/loisirs : 150€</li>
<li>Divers : 100€</li>
<li><strong>TOTAL : ~940€/mois</strong></li>
</ul>

<h3>✨ Les petits plus qui font la différence</h3>
<ul>
<li>Les vietnamiens sont adorables et très accueillants</li>
<li>La ville est super safe, même la nuit</li>
<li>La nature est à portée de main (Marble Mountains, Ba Na Hills)</li>
<li>Hoi An, la ville lanterne, est à 40 minutes</li>
<li>Les massages à 5€ après une journée de travail</li>
</ul>

<h3>⚠️ Les points d\'attention</h3>
<ul>
<li>La barrière de la langue (mais on s\'en sort avec Google Translate)</li>
<li>La saison des pluies (octobre-décembre) peut être intense</li>
<li>La circulation anarchique (on s\'habitue vite)</li>
<li>Les coupures d\'électricité occasionnelles (rare mais ça arrive)</li>
</ul>

<h3>🎯 Mon verdict</h3>
<p>Da Nang est vraiment THE place to be pour les digital nomads en 2025. Le combo plage + ville + coût de la vie + communauté est juste parfait. Si vous cherchez une base en Asie pour quelques mois (ou années), foncez !</p>

<p>N\'hésitez pas si vous avez des questions, je suis là pour ça. Et si vous passez par Da Nang, on se fait un café sur la plage ? 🏖️</p>

<p><em>Cảm ơn for reading! See you in Da Nang! 🇻🇳</em></p>
',
                'category' => 'guide-pratique',
                'country_id' => $vietnam->id,
                'author_id' => $admin->id,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'views' => 342,
                'likes' => 67,
                'reading_time' => 10,
            ]);
        }

        $this->command->info('Content for Japan and Vietnam created successfully!');
    }
}
