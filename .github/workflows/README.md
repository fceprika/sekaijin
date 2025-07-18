# GitHub Actions CI/CD Pipeline

Ce dossier contient la configuration CI/CD pour le projet Sekaijin.

## 🔄 Workflow Principal : `ci.yml`

### Déclenchement
- **Push** sur les branches `main` et `develop`
- **Pull Request** vers les branches `main` et `develop`

### Jobs

#### 1. 🧪 **Test Job**
- **Matrice de tests** : PHP 8.1/8.2 × Node.js 18/20
- **Services** : MySQL 8.0 avec base de données de test
- **Étapes** :
  - Installation des dépendances PHP et Node.js
  - Cache des dépendances pour accélération
  - Build des assets frontend
  - Configuration environnement de test
  - Exécution des migrations et seeders
  - **Lancement des 222 tests unitaires**
  - Génération rapport de couverture (min 80%)
  - Upload vers Codecov

#### 2. 🔍 **Code Quality Job**
- **Laravel Pint** : Vérification du style de code
- **PHPStan** : Analyse statique (niveau 5)
- **Composer Audit** : Vérification des vulnérabilités

#### 3. 🚀 **Deployment Ready Job**
- **Condition** : Seulement sur la branche `main`
- **Dépendances** : Tous les tests et checks doivent passer
- **Étapes** :
  - Build de production
  - Optimisation Laravel (config, routes, vues)
  - Validation de la préparation au déploiement

## 📊 Couverture de Code

Le pipeline génère automatiquement les rapports de couverture de code :
- **Minimum requis** : 80%
- **Tests couverts** : 222 tests unitaires
- **Upload** : Codecov pour suivi historique

## 🛠️ Outils de Qualité

### Laravel Pint
Configuration dans `pint.json` :
- Preset Laravel
- Règles de style strictes
- Formatage automatique

### PHPStan
Configuration dans `phpstan.neon` :
- Niveau d'analyse : 5
- Exclusions : migrations, seeders
- Règles spécifiques Laravel

## 🔧 Configuration Locale

Pour lancer les mêmes checks en local :

```bash
# Tests complets
php artisan test

# Style de code
./vendor/bin/pint --test

# Analyse statique
./vendor/bin/phpstan analyse

# Vérification sécurité
composer audit
```

## 📈 Statut des Tests

Les badges de statut sont disponibles :
- ✅ **Build Status** : ![CI](https://github.com/username/sekaijin/workflows/CI%2FCD%20Pipeline/badge.svg)
- 📊 **Coverage** : ![codecov](https://codecov.io/gh/username/sekaijin/branch/main/graph/badge.svg)

## 🚦 Règles de Branches

- **main** : Branche de production, protégée
- **develop** : Branche de développement
- **feature/** : Branches de fonctionnalités

### Protection des Branches
- **Required checks** : Tous les jobs doivent passer
- **Require pull request reviews** : 1 approbation minimum
- **Dismiss stale reviews** : Activé
- **Require status checks** : Activé

## 📝 Bonnes Pratiques

1. **Commits** : Messages descriptifs avec conventions
2. **Pull Requests** : Description claire des changements
3. **Tests** : Nouveaux tests pour nouvelles fonctionnalités
4. **Coverage** : Maintenir au minimum 80%
5. **Code Style** : Pint automatiquement via pre-commit hook

## 🔐 Secrets Requis

Pour le déploiement (optionnel) :
- `CODECOV_TOKEN` : Token Codecov pour upload coverage
- `DEPLOY_KEY` : Clé SSH pour déploiement (si applicable)

## 🎯 Optimisations

- **Cache** : Dépendances Composer et npm
- **Matrice** : Tests parallèles sur plusieurs versions
- **Conditions** : Jobs conditionnels selon la branche
- **Fail Fast** : Arrêt rapide en cas d'échec critique