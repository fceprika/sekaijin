@extends('layout')

@section('title', 'Politique de Confidentialité - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Politique de Confidentialité</h1>
                <p class="text-purple-100">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>

            <div class="px-8 py-8 prose prose-lg max-w-none">
                <h2>1. Introduction</h2>
                <p>
                    Chez Sekaijin, nous accordons une grande importance à la protection de vos données personnelles. 
                    Cette politique de confidentialité explique comment nous collectons, utilisons et protégeons 
                    vos informations personnelles.
                </p>

                <h2>2. Responsable du traitement</h2>
                <p>
                    Le responsable du traitement des données personnelles est Sekaijin, 
                    communauté des expatriés français.
                </p>

                <h2>3. Données collectées</h2>
                <h3>3.1 Données fournies directement</h3>
                <p>Lors de votre inscription et utilisation de nos services, nous collectons :</p>
                <ul>
                    <li><strong>Informations d'identification :</strong> pseudo, email, mot de passe (chiffré)</li>
                    <li><strong>Informations de profil :</strong> nom, prénom, date de naissance, téléphone (optionnels)</li>
                    <li><strong>Informations de localisation :</strong> pays de résidence, ville, pays de destination</li>
                    <li><strong>Réseaux sociaux :</strong> liens vers vos profils (YouTube, Instagram, etc.) si fournis</li>
                    <li><strong>Photo de profil :</strong> avatar si téléchargé</li>
                    <li><strong>Biographie :</strong> description personnelle si fournie</li>
                    <li><strong>Contenu :</strong> publications, commentaires, messages</li>
                </ul>

                <h3>3.2 Données de géolocalisation</h3>
                <p>
                    <strong>Avec votre consentement explicite uniquement</strong>, nous pouvons collecter :
                </p>
                <ul>
                    <li>Coordonnées GPS approximatives pour affichage sur la carte communautaire</li>
                    <li>Ces données sont floutées (précision réduite à ~10km)</li>
                    <li>Vous pouvez refuser ou retirer ce consentement à tout moment</li>
                </ul>

                <h3>3.3 Données techniques</h3>
                <ul>
                    <li>Adresse IP (pour la sécurité)</li>
                    <li>Type de navigateur et appareil</li>
                    <li>Pages visitées et temps de navigation</li>
                    <li>Logs de connexion et dernière activité</li>
                </ul>

                <h2>4. Finalités du traitement</h2>
                <p>Nous utilisons vos données pour :</p>
                <ul>
                    <li><strong>Fournir nos services :</strong> création et gestion de compte, accès aux fonctionnalités</li>
                    <li><strong>Communauté :</strong> mise en relation avec d'autres expatriés français</li>
                    <li><strong>Contenu personnalisé :</strong> affichage d'informations pertinentes selon votre pays</li>
                    <li><strong>Communication :</strong> notifications importantes, mises à jour du service</li>
                    <li><strong>Sécurité :</strong> prévention de la fraude, protection du compte</li>
                    <li><strong>Amélioration :</strong> analyse d'usage pour améliorer nos services</li>
                    <li><strong>Conformité légale :</strong> respect des obligations légales</li>
                </ul>

                <h2>5. Base légale du traitement</h2>
                <p>Nos traitements sont fondés sur :</p>
                <ul>
                    <li><strong>Consentement :</strong> géolocalisation, communications marketing</li>
                    <li><strong>Exécution du contrat :</strong> fourniture des services Sekaijin</li>
                    <li><strong>Intérêt légitime :</strong> sécurité, prévention de la fraude, amélioration des services</li>
                    <li><strong>Obligation légale :</strong> conservation de certaines données, coopération avec les autorités</li>
                </ul>

                <h2>6. Partage des données</h2>
                <h3>6.1 Visibilité communautaire</h3>
                <p>Certaines informations sont visibles par les autres membres :</p>
                <ul>
                    <li>Pseudo, photo de profil, biographie</li>
                    <li>Pays et ville de résidence (si renseignés)</li>
                    <li>Localisation approximative (si consentement donné)</li>
                    <li>Liens réseaux sociaux (si fournis)</li>
                    <li>Rôle dans la communauté (membre, premium, ambassadeur, admin)</li>
                </ul>

                <h3>6.2 Pas de vente de données</h3>
                <p>
                    <strong>Nous ne vendons jamais vos données personnelles à des tiers.</strong>
                </p>

                <h3>6.3 Prestataires techniques</h3>
                <p>Nous partageons certaines données avec nos prestataires pour :</p>
                <ul>
                    <li>Hébergement et stockage (sécurisé)</li>
                    <li>Services de géolocalisation (OpenStreetMap, Mapbox)</li>
                    <li>Analytics (Google Analytics en mode anonymisé)</li>
                    <li>Sécurité et monitoring</li>
                </ul>

                <h2>7. Conservation des données</h2>
                <ul>
                    <li><strong>Compte actif :</strong> tant que votre compte existe</li>
                    <li><strong>Après suppression :</strong> 30 jours pour récupération, puis suppression définitive</li>
                    <li><strong>Logs de sécurité :</strong> 12 mois maximum</li>
                    <li><strong>Données légales :</strong> selon obligations légales (facturation, etc.)</li>
                </ul>

                <h2>8. Sécurité des données</h2>
                <p>Nous mettons en œuvre des mesures de sécurité appropriées :</p>
                <ul>
                    <li>Chiffrement des mots de passe (bcrypt)</li>
                    <li>Communications sécurisées (HTTPS)</li>
                    <li>Accès restreint aux données (principe du moindre privilège)</li>
                    <li>Surveillance et monitoring de sécurité</li>
                    <li>Sauvegardes régulières et sécurisées</li>
                    <li>Formation de l'équipe sur la sécurité</li>
                </ul>

                <h2>9. Vos droits (RGPD)</h2>
                <p>Vous disposez des droits suivants :</p>
                <ul>
                    <li><strong>Accès :</strong> obtenir une copie de vos données</li>
                    <li><strong>Rectification :</strong> corriger des données inexactes</li>
                    <li><strong>Effacement :</strong> supprimer vos données ("droit à l'oubli")</li>
                    <li><strong>Limitation :</strong> restreindre certains traitements</li>
                    <li><strong>Portabilité :</strong> récupérer vos données dans un format standard</li>
                    <li><strong>Opposition :</strong> s'opposer à certains traitements</li>
                    <li><strong>Retrait du consentement :</strong> pour les traitements basés sur le consentement</li>
                </ul>
                <p>
                    Pour exercer ces droits, contactez-nous via notre 
                    <a href="/contact" class="text-purple-600 hover:text-purple-800">page de contact</a>.
                </p>

                <h2>10. Cookies et technologies similaires</h2>
                <p>Nous utilisons des cookies pour :</p>
                <ul>
                    <li><strong>Fonctionnement :</strong> session utilisateur, authentification</li>
                    <li><strong>Préférences :</strong> langue, paramètres d'affichage</li>
                    <li><strong>Analytics :</strong> statistiques d'usage (anonymisées)</li>
                    <li><strong>Sécurité :</strong> protection CSRF, détection de fraude</li>
                </ul>
                <p>
                    Vous pouvez gérer les cookies via les paramètres de votre navigateur.
                </p>

                <h2>11. Transferts internationaux</h2>
                <p>
                    Vos données sont principalement traitées en Europe. 
                    En cas de transfert vers des pays tiers, nous veillons à ce que 
                    des garanties appropriées soient en place (clauses contractuelles types, 
                    décisions d'adéquation).
                </p>

                <h2>12. Mineurs</h2>
                <p>
                    Nos services ne sont pas destinés aux personnes de moins de 16 ans. 
                    Si nous apprenons qu'un mineur nous a fourni des données personnelles, 
                    nous les supprimerons rapidement.
                </p>

                <h2>13. Modifications</h2>
                <p>
                    Cette politique peut être mise à jour. Les modifications importantes 
                    seront notifiées par email ou via la plateforme. 
                    La date de dernière modification est indiquée en haut de cette page.
                </p>

                <h2>14. Contact et réclamations</h2>
                <p>
                    Pour toute question sur cette politique ou pour exercer vos droits :
                </p>
                <ul>
                    <li>Contactez-nous via la <a href="/contact" class="text-purple-600 hover:text-purple-800">page de contact</a></li>
                    <li>Délégué à la Protection des Données : dpo@sekaijin.com</li>
                </ul>
                <p>
                    Vous avez également le droit de déposer une réclamation auprès de la 
                    <strong>CNIL</strong> (Commission Nationale de l'Informatique et des Libertés) 
                    si vous estimez que vos droits ne sont pas respectés.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-800 font-medium">
                            🔒 Vos données sont protégées par des mesures de sécurité robustes 
                            et ne sont jamais vendues à des tiers.
                        </p>
                    </div>
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-blue-800 font-medium">
                            🗺️ La géolocalisation est entièrement optionnelle et peut être 
                            désactivée à tout moment depuis votre profil.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection