# 🧪 Laravel Dusk E2E Testing Guide

## Overview

Cette documentation couvre l'utilisation et la maintenance des tests End-to-End (E2E) avec Laravel Dusk dans le projet Sekaijin.

## Configuration Locale

### Prérequis
```bash
# Installation des dépendances
composer install
npm install

# Installation de ChromeDriver
php artisan dusk:chrome-driver
```

### Variables d'environnement
Créer `.env.dusk.local` :
```bash
cp .env.example .env.dusk.local
php artisan key:generate

# Configuration spécifique Dusk
echo "APP_ENV=testing" >> .env.dusk.local
echo "DB_DATABASE=sekaijin_dusk" >> .env.dusk.local
echo "SESSION_DRIVER=file" >> .env.dusk.local
echo "CACHE_DRIVER=array" >> .env.dusk.local
echo "MAIL_MAILER=array" >> .env.dusk.local
```

### Base de données de test
```bash
# Créer la base de données Dusk
mysql -u root -p -e "CREATE DATABASE sekaijin_dusk;"

# Lancer les migrations
php artisan migrate --env=dusk.local --force
```

## Exécution des Tests

### Commandes de base
```bash
# Tous les tests E2E
php artisan dusk

# Tests spécifiques
php artisan dusk --filter=test_user_can_login
php artisan dusk tests/Browser/LoginTest.php

# Tests avec groupes
php artisan dusk --group=authentication
```

### Options de debugging
```bash
# Mode verbose avec screenshots
php artisan dusk --env=dusk.local --verbose

# Tests en mode fenêtré (non-headless)
# Modifier DuskTestCase.php temporairement
```

## Architecture des Tests

### Structure des fichiers
```
tests/Browser/
├── LoginTest.php           # Tests d'authentification
├── RegistrationTest.php    # Tests d'inscription  
├── SimpleTest.php         # Tests de navigation
├── Pages/                 # Page Objects (optionnel)
├── screenshots/           # Screenshots automatiques (gitignored)
└── console/              # Logs console (gitignored)

tests/Traits/
└── DuskCommonSetup.php    # Fonctions communes aux tests
```

### Classes de test principales

#### `LoginTest` - Tests d'authentification
- ✅ Connexion utilisateur réussie
- ✅ Échec de connexion avec mauvais mot de passe
- ✅ Présence du menu utilisateur quand connecté
- ✅ Existence du lien "mot de passe oublié"

#### `RegistrationTest` - Tests d'inscription
- ✅ Inscription nouvel utilisateur
- ✅ Validation email déjà existant
- ✅ Validation force du mot de passe
- ✅ Acceptation obligatoire des conditions

#### `SimpleTest` - Tests de navigation
- ✅ Chargement correct de la page d'accueil

## Bonnes Pratiques

### 🎯 Sélecteurs robustes
```php
// ✅ Bon - Utiliser des attributs dusk
->click('[dusk="user-menu"]')

// ✅ Bon - IDs spécifiques
->click('#user-menu-btn')

// ❌ Éviter - Classes CSS fragiles
->click('.btn.btn-primary.hover\\:bg-blue-700')
```

### ⏱️ Attentes intelligentes
```php
// ✅ Bon - Attendre des conditions spécifiques
->waitForText('Connexion réussie', 5)
->waitUntil('document.readyState === "complete"')

// ❌ Éviter - Pauses fixes
->pause(2000)
```

### 🔄 Données de test
```php
// ✅ Bon - Données aléatoires
$username = $this->generateTestUsername();
$email = $this->generateTestEmail();

// ❌ Éviter - Données basées sur le temps
$username = 'TestUser' . time();
```

### 🎨 Validation d'erreurs
```php
// ✅ Bon - Vérifier les états d'erreur
->assertPresent('.bg-red-50, .text-red-700, [class*="error"]')

// ✅ Acceptable - Texte partiel
->waitForText('Les identifiants fournis ne correspondent pas', 5)

// ❌ Éviter - Texte exact complet
->assertSee('Les identifiants fournis ne correspondent pas à nos enregistrements.')
```

## Configuration CI/CD

### GitHub Actions
Les tests E2E sont automatiquement exécutés sur :
- Chaque Pull Request
- Chaque push sur `main` et `develop`

### Environnement CI
- **Chrome headless** avec options optimisées
- **Base de données MySQL** séparée
- **Upload automatique** des screenshots en cas d'échec
- **Timeout intelligent** pour l'attente du serveur

### Variables de sécurité
Pour la production ou tests avancés, utiliser GitHub Secrets :
```yaml
# .github/workflows/dusk.yml (exemple)
env:
  DB_PASSWORD: ${{ secrets.TEST_DB_PASSWORD }}
  MAPBOX_TOKEN: ${{ secrets.MAPBOX_TOKEN }}
```

## Debugging

### Screenshots automatiques
En cas d'échec, les screenshots sont sauvegardés dans :
- Local : `tests/Browser/screenshots/`
- CI : Artifacts GitHub Actions

### Logs console
Les logs JavaScript sont dans :
- Local : `tests/Browser/console/`
- CI : Artifacts GitHub Actions

### Debugging interactif
```php
// Ajouter dans un test pour pause interactive
$browser->pause(); // Pause pour inspection manuelle
```

### Mode fenêtré (développement)
```php
// Dans DuskTestCase.php - désactiver temporairement headless
protected function driver(): RemoteWebDriver
{
    $options = (new ChromeOptions)->addArguments([
        '--disable-gpu',
        '--no-sandbox',
        // '--headless', // Commenter cette ligne
    ]);
}
```

## Maintenance

### Mise à jour ChromeDriver
```bash
# Mettre à jour vers la dernière version
php artisan dusk:chrome-driver --detect

# Version spécifique
php artisan dusk:chrome-driver 118
```

### Nettoyage périodique
```bash
# Supprimer les anciens screenshots
rm -rf tests/Browser/screenshots/*
rm -rf tests/Browser/console/*

# Nettoyer la base de données de test
php artisan migrate:fresh --env=dusk.local --force
```

### Performance
- Les tests E2E sont plus lents que les tests unitaires (~25s vs 3s)
- Exécuter en parallèle avec les tests unitaires dans CI
- Limiter le nombre de tests E2E aux parcours critiques

## Troubleshooting

### Erreurs communes

#### "ChromeDriver not found"
```bash
php artisan dusk:chrome-driver
chmod +x vendor/laravel/dusk/bin/chromedriver-*
```

#### "Connection refused"
```bash
# Vérifier que le serveur Laravel démarre
php artisan serve --env=dusk.local &
curl http://127.0.0.1:8000
```

#### "Element not found"
```php
// Ajouter des attentes explicites
->waitFor('#element', 10)
->waitUntil('document.querySelector("#element")')
```

#### "Database connection failed"
```bash
# Vérifier la base de données de test
mysql -u root -p -e "SHOW DATABASES LIKE 'sekaijin_dusk';"
```

## Métriques de Performance

### Temps d'exécution typiques
- **Setup** (ChromeDriver, DB) : ~10s
- **Test individuel** : ~2-5s
- **Suite complète** (9 tests) : ~25s
- **CI total** (avec build) : ~3-5min

### Optimisations possibles
- Réutiliser la session navigateur entre tests similaires
- Parallélisation des tests (Laravel Dusk Pro)
- Cache des assets frontend
- Optimisation des requêtes base de données

## Support et Contribution

### Ajouter de nouveaux tests
1. Créer la classe de test dans `tests/Browser/`
2. Étendre `DuskTestCase` et utiliser `DuskCommonSetup`
3. Suivre les conventions de nommage (`test_*`)
4. Documenter les nouveaux sélecteurs

### Rapporter des bugs
- Inclure les screenshots (`tests/Browser/screenshots/`)
- Copier les logs console (`tests/Browser/console/`)
- Spécifier la version de Chrome/ChromeDriver
- Reproduire localement avant signalement

---

**✨ Les tests E2E garantissent que l'interface utilisateur fonctionne correctement du point de vue de l'utilisateur final !**