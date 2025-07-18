# Tests Unitaires - Sekaijin

## Vue d'ensemble

Ce document présente l'infrastructure de tests unitaires mise en place pour l'application Sekaijin, une plateforme communautaire pour les expatriés français.

## Configuration

### Environnement de test

- **Base de données de test** : `sekaijin_test` (MySQL)
- **Environnement** : `.env.testing` configuré avec des valeurs optimisées pour les tests
- **PHPUnit** : Version 10.5.47 avec configuration Laravel

### Commandes utiles

```bash
# Lancer tous les tests
php artisan test

# Lancer les tests avec couverture
php artisan test --coverage

# Lancer des tests spécifiques
php artisan test --filter=AuthenticationTest

# Lancer les tests avec arrêt à la première erreur
php artisan test --stop-on-failure

# Recréer la base de données de test
php artisan migrate:fresh --env=testing
```

## Structure des tests

### 1. Factories (Générateurs de données)

Des factories complètes ont été créées pour tous les modèles principaux :

- `UserFactory` - Utilisateurs avec données d'expatriés (rôles, pays, réseaux sociaux, etc.)
- `CountryFactory` - Pays disponibles (Thaïlande, Japon, Vietnam, Chine)
- `ArticleFactory` - Articles de blog avec catégories et statuts
- `NewsFactory` - Actualités avec différentes catégories
- `EventFactory` - Événements avec gestion en ligne/hors ligne
- `AnnouncementFactory` - Petites annonces avec types et modération
- `FavoriteFactory` - Système de favoris polymorphe

### 2. Traits de test personnalisés

**AuthenticatesUsers** (`tests/Traits/AuthenticatesUsers.php`)
- `signIn()` - Connexion utilisateur basique
- `signInAdmin()` - Connexion administrateur
- `signInAmbassador()` - Connexion ambassadeur
- `signInPremium()` - Connexion utilisateur premium

**CreatesCountries** (`tests/Traits/CreatesCountries.php`)
- `createThailand()` - Création de la Thaïlande
- `createJapan()` - Création du Japon
- `createVietnam()` - Création du Vietnam
- `createChina()` - Création de la Chine

**CreatesContent** (`tests/Traits/CreatesContent.php`)
- `createArticle()` - Création d'articles
- `createPublishedArticle()` - Articles publiés
- `createFeaturedArticle()` - Articles mis en avant
- `createNews()` - Actualités
- `createEvent()` - Événements

**AssertionsHelper** (`tests/Traits/AssertionsHelper.php`)
- `assertRedirectToLogin()` - Vérification redirection login
- `assertForbidden()` - Vérification erreur 403
- `assertJsonSuccess()` - Vérification réponse JSON succès
- `assertUserHasRole()` - Vérification rôle utilisateur

### 3. Tests d'authentification

**AuthenticationTest** (`tests/Feature/AuthenticationTest.php`)
- ✅ Affichage des pages d'inscription et connexion
- ✅ Inscription avec validation complète
- ✅ Connexion et déconnexion
- ✅ Validation des champs obligatoires
- ✅ Unicité des pseudos et emails
- ✅ Vérification API de disponibilité des pseudos
- ✅ Redirection des utilisateurs connectés

**Tests couverts (15 tests, 48 assertions)**

### 4. Tests des profils utilisateurs

**ProfileTest** (`tests/Feature/ProfileTest.php`)
- Gestion des profils privés/publics
- Modification des informations personnelles
- Upload d'avatar avec validation
- Gestion des réseaux sociaux
- Changement de mot de passe
- Géolocalisation et carte

### 5. Tests du système de contenu

**ContentTest** (`tests/Feature/ContentTest.php`)
- Affichage des articles, actualités et événements
- Gestion des statuts (publié/non publié)
- Système de vues et engagement
- Filtrage par catégorie
- Fonctionnalité de recherche
- Pagination
- Relations entre modèles

### 6. Tests des permissions et rôles

**RolePermissionTest** (`tests/Feature/RolePermissionTest.php`)
- Système de rôles (free, premium, ambassador, admin)
- Permissions d'accès au panel admin
- Création de contenu selon les rôles
- Édition des contenus propres vs. tiers
- Middleware de protection des routes
- Badges de rôle sur les profils

## Couverture des tests

### Tests fonctionnels

Les tests couvrent les fonctionnalités principales :

1. **Authentification complète** (100% testé)
2. **Gestion des profils** (structure créée)
3. **Système de contenu** (structure créée)
4. **Permissions et rôles** (structure créée)
5. **API endpoints** (structure créée)

### Tests unitaires

- Tests des modèles et leurs relations
- Tests des factories
- Tests des traits personnalisés
- Tests des helpers et utilitaires

## Avantages de cette infrastructure

### 1. Productivité
- Traits réutilisables réduisent la duplication
- Factories facilitent la création de données de test
- Helpers simplifient les assertions courantes

### 2. Fiabilité
- Tests d'authentification complets
- Validation des permissions et rôles
- Vérification des contraintes de sécurité

### 3. Maintenabilité
- Structure modulaire et organisée
- Documentation claire des tests
- Isolation des environnements

### 4. Évolutivité
- Facilité d'ajout de nouveaux tests
- Réutilisation des composants existants
- Intégration continue prête

## Exemple d'utilisation

```php
// Dans un test
public function test_ambassador_can_create_event(): void
{
    $ambassador = $this->signInAmbassador();
    $thailand = $this->createThailand();
    
    $event = $this->createEvent([
        'country_id' => $thailand->id,
        'organizer_id' => $ambassador->id,
    ]);
    
    $response = $this->get("/thailande/evenements/{$event->slug}");
    
    $response->assertStatus(200);
    $response->assertSee($event->title);
    $this->assertUserIsAmbassador();
}
```

## Intégration continue

L'infrastructure est prête pour l'intégration avec GitHub Actions ou d'autres systèmes CI/CD :

```yaml
# .github/workflows/tests.yml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: php artisan test
```

## Commandes de maintenance

```bash
# Nettoyer et recréer la base de test
php artisan migrate:fresh --env=testing

# Lancer les tests avec rapport détaillé
php artisan test --verbose

# Lancer uniquement les tests rapides
php artisan test --filter="Unit"

# Lancer uniquement les tests d'intégration
php artisan test --filter="Feature"
```

Cette infrastructure de tests fournit une base solide pour le développement et la maintenance de l'application Sekaijin, garantissant la qualité et la fiabilité du code.