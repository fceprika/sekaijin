# 📚 API Articles Documentation - Sekaijin

## 🎯 Vue d'ensemble

L'API Articles permet la création et la gestion automatisée d'articles de blog sur la plateforme Sekaijin. Conçue pour l'intégration avec des outils d'automatisation comme n8n, Zapier ou des scripts personnalisés.

## 🔐 Authentification

Toutes les requêtes nécessitent un token Bearer obtenu via Laravel Sanctum.

```bash
Authorization: Bearer YOUR_API_TOKEN
Content-Type: application/json
Accept: application/json
```

## 📊 Endpoints disponibles

| Méthode | Endpoint | Description | Status |
|---------|----------|-------------|--------|
| `GET` | `/api/articles` | Liste paginée des articles | ✅ |
| `POST` | `/api/articles` | Créer un nouvel article | ✅ |
| `GET` | `/api/articles/{id}` | Afficher un article | ⚠️ |
| `PUT` | `/api/articles/{id}` | Modifier un article | ⚠️ |
| `DELETE` | `/api/articles/{id}` | Supprimer un article | ⚠️ |

⚠️ **Note**: Les routes individuelles peuvent rencontrer des problèmes de model binding. Utilisez GET (liste) et POST pour le moment.

## 🚀 Utilisation

### 📋 GET /api/articles - Liste des articles

#### Paramètres de requête
- `page` (integer) : Numéro de page (défaut: 1)
- `per_page` (integer) : Articles par page (défaut: 20)
- `country_id` (integer) : Filtrer par pays
- `category` (string) : Filtrer par catégorie
- `author_id` (integer) : Filtrer par auteur
- `search` (string) : Rechercher dans titre et résumé

#### Exemple de requête
```bash
curl -X GET "https://sekaijin.com/api/articles?country_id=1&category=lifestyle&per_page=10" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

#### Réponse
```json
{
  "data": [
    {
      "id": 123,
      "title": "Ma nouvelle vie à Tokyo",
      "slug": "ma-nouvelle-vie-a-tokyo",
      "excerpt": "Découvrez mon parcours d'expatriée...",
      "category": "lifestyle",
      "category_label": "Lifestyle",
      "image_url": "https://sekaijin.com/storage/article_images/tokyo-life.jpg",
      "country": {
        "id": 1,
        "name": "Japon",
        "slug": "japon",
        "emoji": "🇯🇵"
      },
      "author": {
        "id": 378,
        "name": "Marie Dupont",
        "avatar": "https://..."
      },
      "is_featured": false,
      "is_published": true,
      "published_at": "2025-08-02T10:30:00.000000Z",
      "views": 1523,
      "likes": 89,
      "reading_time": 7,
      "reading_time_formatted": "7 min de lecture",
      "created_at": "2025-08-01T14:22:00.000000Z",
      "updated_at": "2025-08-02T10:30:00.000000Z",
      "links": {
        "self": "https://sekaijin.com/api/articles/123",
        "web": "https://sekaijin.com/japon/articles/ma-nouvelle-vie-a-tokyo"
      }
    }
  ],
  "links": {
    "first": "https://sekaijin.com/api/articles?page=1",
    "last": "https://sekaijin.com/api/articles?page=5",
    "prev": null,
    "next": "https://sekaijin.com/api/articles?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 20,
    "to": 20,
    "total": 98
  }
}
```

### ✍️ POST /api/articles - Créer un article

#### Corps de la requête
```json
{
  "title": "Mon expérience culinaire au Japon",
  "excerpt": "Un voyage gustatif à travers les saveurs nippones...",
  "content": "<p>Contenu HTML complet de l'article...</p>",
  "category": "cuisine",
  "image_url": "https://www.youtube.com/watch?v=abc123XYZ",
  "country_id": 1,
  "author_id": 378,
  "is_published": false,
  "is_featured": false
}
```

#### Champs disponibles

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| `title` | string | ✅ | Titre de l'article (max 255 caractères) |
| `slug` | string | ❌ | URL slug (généré automatiquement si vide) |
| `excerpt` | string | ✅ | Résumé de l'article (max 500 caractères) |
| `content` | string | ✅ | Contenu HTML (min 100 caractères) |
| `category` | string | ❌ | Catégorie (défaut: "témoignage") |
| `image_url` | string | ❌ | URL de l'image ou vidéo YouTube |
| `country_id` | integer | ❌ | ID du pays associé |
| `author_id` | integer | ✅ | ID de l'auteur |
| `is_published` | boolean | ❌ | Publier l'article (défaut: false) |
| `is_featured` | boolean | ❌ | Mettre en avant (défaut: false) |
| `published_at` | datetime | ❌ | Date de publication |
| `reading_time` | integer | ❌ | Temps de lecture en minutes (calculé si vide) |

#### Catégories disponibles
- `témoignage` : Expériences personnelles
- `guide-pratique` : Conseils pratiques
- `travail` : Carrière et emploi
- `lifestyle` : Mode de vie
- `cuisine` : Gastronomie

#### Support YouTube intelligent 🎥

L'API détecte automatiquement les URLs YouTube et télécharge les miniatures :

```json
{
  "image_url": "https://www.youtube.com/watch?v=dQw4w9WgXcQ"
}
// Sera automatiquement converti en :
// https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg
```

Formats supportés :
- `https://www.youtube.com/watch?v=VIDEO_ID`
- `https://youtu.be/VIDEO_ID`
- `https://www.youtube.com/embed/VIDEO_ID`

#### Réponse succès (201)
```json
{
  "message": "Article créé avec succès.",
  "data": {
    "id": 124,
    "title": "Mon expérience culinaire au Japon",
    "slug": "mon-experience-culinaire-au-japon",
    "excerpt": "Un voyage gustatif...",
    "category": "cuisine",
    "image_url": "https://sekaijin.com/storage/article_images/youtube-dQw4w9WgXcQ.jpg",
    "is_published": false,
    "created_at": "2025-08-02T15:45:00.000000Z"
  }
}
```

### 🔄 PUT /api/articles/{id} - Modifier un article

Mêmes champs que POST, mais tous optionnels. Seuls les champs fournis seront mis à jour.

```json
{
  "title": "Nouveau titre",
  "is_published": true
}
```

### 🗑️ DELETE /api/articles/{id} - Supprimer un article

```bash
curl -X DELETE "https://sekaijin.com/api/articles/124" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🛡️ Sécurité

### Protection SSRF
- **Whitelist de domaines** pour le téléchargement d'images
- **Validation des fichiers** : type, taille (10MB max), dimensions (4000px max)
- **Blocage des IPs privées** et locales

### Domaines autorisés
- `img.youtube.com`, `i.ytimg.com`
- `images.unsplash.com`, `images.pexels.com`
- `cdn.pixabay.com`
- Autres domaines de confiance

### Rate Limiting
- **30 requêtes par minute** par utilisateur
- **100 requêtes par heure** par utilisateur
- Headers de réponse incluent les limites restantes

## 🔍 Codes d'erreur

| Code | Description | Solution |
|------|-------------|----------|
| 401 | Non authentifié | Vérifier le token Bearer |
| 422 | Validation échouée | Vérifier les données envoyées |
| 429 | Trop de requêtes | Attendre avant de réessayer |
| 500 | Erreur serveur | Contacter le support |

### Exemple d'erreur 422
```json
{
  "message": "Les données fournies sont invalides.",
  "errors": {
    "title": ["Le titre est obligatoire."],
    "excerpt": ["Le résumé ne peut pas dépasser 500 caractères."]
  }
}
```

## 🤖 Intégration n8n

### Workflow exemple
```javascript
// HTTP Request node
{
  "method": "POST",
  "url": "https://sekaijin.com/api/articles",
  "authentication": {
    "type": "genericCredentialType",
    "genericAuthType": "httpHeaderAuth"
  },
  "headers": {
    "Authorization": "Bearer {{$credentials.sekaijinToken}}"
  },
  "body": {
    "title": "{{$node['RSS Feed'].json.title}}",
    "excerpt": "{{$node['RSS Feed'].json.description.substring(0, 500)}}",
    "content": "{{$node['RSS Feed'].json.content}}",
    "category": "guide-pratique",
    "image_url": "{{$node['RSS Feed'].json.thumbnail}}",
    "country_id": 1,
    "author_id": 378,
    "is_published": false
  }
}
```

### Cas d'usage
1. **Import RSS** : Conversion automatique de flux blog
2. **YouTube Webhook** : Article pour chaque nouvelle vidéo
3. **Content curation** : Agrégation multi-sources
4. **Migration** : Import depuis autre CMS

## 📈 Bonnes pratiques

1. **Brouillons par défaut** : `is_published: false`
2. **Validation manuelle** : Réviser avant publication
3. **Images optimisées** : Préférer des URLs d'images légères
4. **Catégorisation** : Choisir la bonne catégorie
5. **SEO** : Titres et résumés accrocheurs

## 🚧 Limitations connues

- **Model binding** : Les routes GET/PUT/DELETE par ID peuvent ne pas fonctionner
- **Contenu minimum** : 100 caractères requis
- **Taille images** : Maximum 10MB, 4000x4000px

## 📞 Support

Pour toute question ou problème :
- Documentation complète : [sekaijin.com/docs](https://sekaijin.com/docs)
- Support technique : api@sekaijin.com

---

*Dernière mise à jour : Août 2025*