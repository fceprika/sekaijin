# 🚀 GitHub Actions CI/CD - Guide Complet

## 📋 Vue d'ensemble

Le projet Sekaijin utilise GitHub Actions pour automatiser les tests et assurer la qualité du code sur chaque Pull Request et push.

## 🔧 Configuration

### Workflows Créés

1. **`ci.yml`** - Pipeline principal complet
2. **`pull-request.yml`** - Validation rapide des PR

### Outils de Qualité

- **`phpstan.neon`** - Configuration analyse statique
- **`pint.json`** - Configuration style de code
- **`.env.testing`** - Environnement de test

## 🎯 Workflow `pull-request.yml` (Spécifique aux PR)

### Déclenchement
```yaml
on:
  pull_request:
    branches: [ main, develop ]
    types: [opened, synchronize, reopened]
```

### Jobs

#### 1. **Quick Checks** ⚡
- Vérification syntaxe PHP
- Validation style de code (Laravel Pint)
- Exécution rapide (~2 min)

#### 2. **Unit Tests** 🧪
- **222 tests unitaires complets**
- Base de données MySQL de test
- Build des assets frontend
- Migrations et seeders
- Arrêt à la première erreur

#### 3. **E2E Tests (Dusk)** 🌐
- **Tests End-to-End avec Laravel Dusk**
- Tests d'interface utilisateur avec Chrome headless
- Base de données séparée (`sekaijin_dusk`)
- Tests d'authentification et de navigation
- Upload automatique des screenshots en cas d'échec

#### 4. **Security Check** 🔒
- Audit des dépendances Composer
- Vérification fichiers sensibles
- Validation sécurité

#### 5. **PR Summary** 📊
- Résumé automatique des résultats
- Liens vers les détails
- Statut global de la PR

## 📊 Exemple de Sortie

```
## 📊 Pull Request Summary

### Test Results
- **Quick Checks**: success ✅
- **Unit Tests**: success ✅
- **E2E Tests (Dusk)**: success ✅
- **Security Check**: success ✅

### ✅ All checks passed! Ready for review.

### 🔗 Quick Links
- [View Tests](https://github.com/username/sekaijin/actions/runs/123456)
- [Code Coverage](https://codecov.io/gh/username/sekaijin)
```

## 🛠️ Utilisation

### Pour les Développeurs

1. **Créer une branche** :
   ```bash
   git checkout -b feature/ma-nouvelle-fonctionnalite
   ```

2. **Développer et tester localement** :
   ```bash
   php artisan test
   php artisan dusk
   ./vendor/bin/pint --test
   ```

3. **Push et créer PR** :
   ```bash
   git push origin feature/ma-nouvelle-fonctionnalite
   ```

4. **Les tests se lancent automatiquement** sur la PR

### Pour les Reviews

- ✅ **Verts** : Prêt pour review
- ❌ **Rouges** : Corrections nécessaires
- 🟡 **Oranges** : Warnings non bloquants

## 📈 Avantages

### Automatisation Complète
- **222 tests unitaires** exécutés automatiquement
- **9 tests E2E** avec Laravel Dusk
- **Validations** code style et sécurité
- **Feedback** immédiat sur les PR

### Qualité Garantie
- Aucune PR ne peut être mergée sans tests passants
- Style de code uniforme
- Sécurité vérifiée

### Performance
- **Jobs parallèles** pour rapidité
- **Cache** des dépendances
- **Arrêt rapide** en cas d'erreur

## 🔄 Workflow Complet (`ci.yml`)

### Matrice de Tests
```yaml
strategy:
  matrix:
    php: ['8.1', '8.2']
    node: ['18', '20']
```

### Services
- **MySQL 8.0** pour tests BDD
- **Coverage** avec minimum 80%
- **Upload** vers Codecov

### Jobs Additionnels
- **Code Quality** : PHPStan + Pint
- **Deployment Ready** : Build production

## 🎨 Badges de Statut

Ajoutez ces badges au README :

```markdown
[![CI/CD Pipeline](https://github.com/username/sekaijin/actions/workflows/ci.yml/badge.svg)](https://github.com/username/sekaijin/actions/workflows/ci.yml)
[![Pull Request Checks](https://github.com/username/sekaijin/actions/workflows/pull-request.yml/badge.svg)](https://github.com/username/sekaijin/actions/workflows/pull-request.yml)
[![codecov](https://codecov.io/gh/username/sekaijin/branch/main/graph/badge.svg)](https://codecov.io/gh/username/sekaijin)
```

## 🔧 Configuration Locale

### Prérequis
```bash
# Installer outils qualité
composer require --dev laravel/pint phpstan/phpstan
```

### Commandes
```bash
# Style de code
./vendor/bin/pint --test

# Analyse statique
./vendor/bin/phpstan analyse

# Tests complets
php artisan test

# Tests E2E
php artisan dusk

# Couverture
php artisan test --coverage
```

## 🚦 Règles de Protection

### Branches Protégées
- **main** : Require PR + checks
- **develop** : Require PR + checks

### Checks Requis
- ✅ Quick Checks
- ✅ Unit Tests (222 tests)
- ✅ E2E Tests (9 tests Dusk)
- ✅ Security Check
- ✅ Code Quality

## 📝 Bonnes Pratiques

### Commits
```bash
# Conventions
feat: ajouter authentification OAuth
fix: corriger validation email
docs: mettre à jour README
test: ajouter tests API
```

### Pull Requests
- **Titre clair** : Description courte
- **Description** : Changements détaillés
- **Tests** : Nouveaux tests inclus
- **Screenshots** : Pour UI/UX

### Tests
- **Nouveaux tests** pour nouvelles fonctionnalités
- **Maintenir 80%** de couverture minimum
- **Tests rapides** (< 5 secondes par test)

## 🎯 Optimisations

### Performance
- **Cache** : Dépendances Composer/npm
- **Parallélisation** : Jobs simultanés
- **Fail Fast** : Arrêt rapide si erreur

### Coûts
- **Concurrency** : Annule jobs précédents
- **Conditions** : Jobs conditionnels
- **Matrix** : Tests ciblés

## 📞 Support

### Debugging
- **Logs** : Voir Actions tab sur GitHub
- **Local** : Reproduire avec mêmes commandes
- **Issues** : Créer issue si problème récurrent

### Maintenance
- **Mises à jour** : Actions versions
- **Dependencies** : Audit régulier
- **Performance** : Monitoring temps exécution

---

## 🎉 Résultat

**Avec cette configuration, chaque PR est automatiquement testée avec nos 222 tests unitaires + 9 tests E2E, garantissant une qualité de code maximale !**

Les tests couvrent :
- 🔐 Authentification (15 tests)
- 👤 Profils (35 tests)
- 📝 Contenu (39 tests)
- 🛡️ Permissions (20 tests)
- 📢 Annonces (45 tests)
- ⚙️ Admin (23 tests)
- ⭐ Favoris (24 tests)
- 🔌 APIs (19 tests)
- 🧪 Autres (2 tests)

**Total : 231 tests (222 unitaires + 9 E2E) - 716 assertions - ~25 secondes**

### Tests E2E avec Laravel Dusk :
- 🔐 Authentification (4 tests): Connexion, échec connexion, menu utilisateur, lien mot de passe
- 📝 Inscription (4 tests): Création compte, email existant, validation mot de passe, conditions d'utilisation  
- 🏠 Navigation (1 test): Chargement page d'accueil