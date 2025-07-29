# API News Documentation

## Authentification

Toutes les requêtes API nécessitent un token Sanctum dans les en-têtes :
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

## Endpoints disponibles

### 1. Créer un article (POST /api/news)

**URL:** `POST http://127.0.0.1:8000/api/news`

**Body JSON:**
```json
{
    "title": "Mon titre d'article",
    "summary": "Résumé de l'article",
    "content": "Contenu complet de l'article en HTML ou texte",
    "thumbnail_url": "https://example.com/image.jpg",
    "author_id": 1,
    "status": "published",
    "tags": ["tag1", "tag2", "tag3"]
}
```

**Réponse succès (201):**
```json
{
    "success": true,
    "message": "Article créé avec succès.",
    "data": {
        "id": 123,
        "title": "Mon titre d'article",
        "slug": "mon-titre-darticle",
        "summary": "Résumé de l'article",
        "content": "Contenu complet...",
        "thumbnail_url": "http://127.0.0.1:8000/storage/news_thumbnails/2025-07-29_17-30-00_ABC123.jpg",
        "author": {
            "id": 1,
            "name": "Jean Expat"
        },
        "status": "published",
        "published_at": "2025-07-29T17:30:00.000000Z",
        "tags": ["tag1", "tag2", "tag3"],
        "created_at": "2025-07-29T17:30:00.000000Z",
        "updated_at": "2025-07-29T17:30:00.000000Z"
    }
}
```

### 2. Lister les articles (GET /api/news)

**URL:** `GET http://127.0.0.1:8000/api/news`

**Réponse succès (200):**
```json
{
    "success": true,
    "message": "Articles récupérés avec succès.",
    "data": [
        {
            "id": 123,
            "title": "Mon titre d'article",
            "slug": "mon-titre-darticle",
            "summary": "Résumé de l'article",
            "thumbnail_url": "http://127.0.0.1:8000/storage/news_thumbnails/image.jpg",
            "author": {
                "id": 1,
                "name": "Jean Expat"
            },
            "status": "published",
            "published_at": "2025-07-29T17:30:00.000000Z",
            "tags": ["tag1", "tag2"],
            "created_at": "2025-07-29T17:30:00.000000Z",
            "updated_at": "2025-07-29T17:30:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 25,
        "last_page": 2,
        "has_more_pages": true
    }
}
```

### 3. Récupérer un article (GET /api/news/{id})

**URL:** `GET http://127.0.0.1:8000/api/news/123`

**Réponse succès (200):** (même format que POST avec `content` inclus)

### 4. Mettre à jour un article (PUT /api/news/{id})

**URL:** `PUT http://127.0.0.1:8000/api/news/123`

**Body JSON:** (tous les champs optionnels avec `sometimes`)
```json
{
    "title": "Nouveau titre",
    "status": "draft"
}
```

**Réponse succès (200):** (même format que POST)

### 5. Supprimer un article (DELETE /api/news/{id})

**URL:** `DELETE http://127.0.0.1:8000/api/news/123`

**Réponse succès (200):**
```json
{
    "success": true,
    "message": "Article supprimé avec succès.",
    "data": null
}
```

## Fonctionnalités spéciales

### Téléchargement automatique d'images
- L'API télécharge automatiquement les images depuis `thumbnail_url`
- Support du fallback YouTube (maxresdefault → hqdefault)
- Images stockées dans `storage/news_thumbnails/`
- Génération automatique de noms de fichiers uniques

### Détection de doublons
- Vérification automatique des titres en double
- Erreur 409 si titre existant lors de la création
- Exclusion de l'article actuel lors de la mise à jour

### Génération automatique de slug
- Slug généré automatiquement depuis le titre
- Gestion des doublons avec suffixes numériques
- Mise à jour automatique si titre modifié

### Gestion du statut de publication
- `status: "draft"` → article non publié, `published_at: null`
- `status: "published"` → article publié, `published_at: timestamp automatique`

## Codes d'erreur

- **400**: URL d'image invalide ou impossible à télécharger
- **409**: Titre en double (conflit)
- **422**: Erreurs de validation (champs manquants/invalides)
- **500**: Erreur serveur interne

## Exemple d'utilisation avec n8n

```javascript
// Configuration du nœud HTTP Request dans n8n
{
    "method": "POST",
    "url": "http://127.0.0.1:8000/api/news",
    "headers": {
        "Authorization": "Bearer YOUR_SANCTUM_TOKEN",
        "Accept": "application/json",
        "Content-Type": "application/json"
    },
    "body": {
        "title": "{{ $json.title }}",
        "summary": "{{ $json.summary }}",
        "content": "{{ $json.content }}",
        "thumbnail_url": "{{ $json.thumbnail_url }}",
        "author_id": 1,
        "status": "published",
        "tags": "{{ $json.tags }}"
    }
}
```