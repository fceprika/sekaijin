@extends('layout')

@section('title', 'Conditions d\'utilisation - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Conditions d'utilisation</h1>
                <p class="text-blue-100">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>

            <div class="px-8 py-8 prose prose-lg max-w-none">
                <h2>1. Acceptation des conditions</h2>
                <p>
                    En accédant et en utilisant Sekaijin, vous acceptez d'être lié par ces conditions d'utilisation. 
                    Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser nos services.
                </p>

                <h2>2. Description du service</h2>
                <p>
                    Sekaijin est une plateforme communautaire dédiée aux expatriés français dans le monde entier. 
                    Notre service permet aux utilisateurs de :
                </p>
                <ul>
                    <li>Créer un profil et partager des informations</li>
                    <li>Interagir avec d'autres membres de la communauté</li>
                    <li>Accéder à du contenu spécialisé par pays</li>
                    <li>Participer à des événements et discussions</li>
                    <li>Partager leur localisation de manière approximative</li>
                </ul>

                <h2>3. Inscription et compte utilisateur</h2>
                <p>
                    Pour utiliser certaines fonctionnalités de Sekaijin, vous devez créer un compte. Vous êtes responsable de :
                </p>
                <ul>
                    <li>Fournir des informations exactes et à jour lors de l'inscription</li>
                    <li>Maintenir la sécurité de votre mot de passe</li>
                    <li>Toutes les activités qui se produisent sous votre compte</li>
                    <li>Nous informer immédiatement de toute utilisation non autorisée</li>
                </ul>

                <h2>4. Utilisation acceptable</h2>
                <p>En utilisant Sekaijin, vous vous engagez à ne pas :</p>
                <ul>
                    <li>Publier du contenu illégal, offensant, discriminatoire ou haineux</li>
                    <li>Harceler, intimider ou menacer d'autres utilisateurs</li>
                    <li>Usurper l'identité d'une autre personne</li>
                    <li>Spammer ou envoyer des messages non sollicités</li>
                    <li>Violer les droits de propriété intellectuelle</li>
                    <li>Tenter de compromettre la sécurité de la plateforme</li>
                    <li>Utiliser des robots ou scripts automatisés</li>
                </ul>

                <h2>5. Contenu utilisateur</h2>
                <p>
                    Vous conservez la propriété de tout contenu que vous publiez sur Sekaijin. 
                    Cependant, en publiant du contenu, vous accordez à Sekaijin une licence non exclusive 
                    pour utiliser, modifier et afficher ce contenu dans le cadre de nos services.
                </p>
                <p>
                    Nous nous réservons le droit de supprimer tout contenu qui viole ces conditions 
                    ou qui est jugé inapproprié.
                </p>

                <h2>6. Confidentialité et données personnelles</h2>
                <p>
                    La collecte et l'utilisation de vos données personnelles sont régies par notre 
                    <a href="/politique-confidentialite" class="text-blue-600 hover:text-blue-800">Politique de Confidentialité</a>.
                </p>

                <h2>7. Géolocalisation</h2>
                <p>
                    Le partage de localisation sur Sekaijin est entièrement volontaire. Si vous choisissez 
                    de partager votre localisation :
                </p>
                <ul>
                    <li>Votre position exacte ne sera jamais partagée</li>
                    <li>Seule une zone approximative (rayon de ~10km) sera visible</li>
                    <li>Vous pouvez désactiver cette fonctionnalité à tout moment</li>
                    <li>Ces données ne sont jamais vendues à des tiers</li>
                </ul>

                <h2>8. Suspension et résiliation</h2>
                <p>
                    Nous nous réservons le droit de suspendre ou de résilier votre compte en cas de :
                </p>
                <ul>
                    <li>Violation des présentes conditions d'utilisation</li>
                    <li>Comportement nuisant à la communauté</li>
                    <li>Utilisation frauduleuse ou abusive du service</li>
                </ul>
                <p>
                    Vous pouvez supprimer votre compte à tout moment depuis votre profil.
                </p>

                <h2>9. Limitation de responsabilité</h2>
                <p>
                    Sekaijin est fourni "en l'état". Nous ne garantissons pas que le service sera 
                    toujours disponible ou exempt d'erreurs. Notre responsabilité est limitée 
                    dans la mesure autorisée par la loi.
                </p>

                <h2>10. Modifications</h2>
                <p>
                    Nous nous réservons le droit de modifier ces conditions d'utilisation à tout moment. 
                    Les modifications importantes seront communiquées aux utilisateurs. 
                    L'utilisation continue du service après modification constitue une acceptation des nouvelles conditions.
                </p>

                <h2>11. Droit applicable</h2>
                <p>
                    Ces conditions sont régies par le droit français. Tout litige sera soumis 
                    à la juridiction des tribunaux français.
                </p>

                <h2>12. Contact</h2>
                <p>
                    Pour toute question concernant ces conditions d'utilisation, 
                    vous pouvez nous contacter via notre <a href="/contact" class="text-blue-600 hover:text-blue-800">page de contact</a>.
                </p>

                <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-blue-800 font-medium">
                        🛡️ Votre sécurité et votre vie privée sont nos priorités. 
                        Ces conditions visent à protéger tous les membres de notre communauté.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection