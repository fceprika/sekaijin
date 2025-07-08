# Configuration Email - Sekaijin

## Système d'emails implémenté

✅ **Email de bienvenue automatique** envoyé après inscription  
✅ **Template responsive en français** avec branding Sekaijin  
✅ **Gestion d'erreurs** (ne bloque pas l'inscription si l'email échoue)  
✅ **Support Zoho Mail** configuré pour contact@sekaijin.fr  

## Configuration pour la production

### 1. Paramètres SMTP Zoho Mail

Dans votre fichier `.env` de production, ajoutez :

```env
# Configuration email Zoho Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=contact@sekaijin.fr
MAIL_PASSWORD=VOTRE_MOT_DE_PASSE_EMAIL
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=contact@sekaijin.fr
MAIL_FROM_NAME="Sekaijin"
```

### 2. Configuration alternative (port SSL)

Si le port 587 ne fonctionne pas, essayez :

```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### 3. Test de configuration

Une fois configuré, testez avec cette commande :

```bash
php artisan tinker
# Puis dans Tinker :
Mail::raw('Test email', function($message) {
    $message->to('votre-email@test.fr')
            ->subject('Test Sekaijin');
});
```

## Structure des emails

### Template de bienvenue

- **Fichier** : `resources/views/emails/welcome.blade.php`
- **Classe Mailable** : `app/Mail/WelcomeEmail.php`
- **Sujet** : "Bienvenue dans la communauté Sekaijin ! 🌍"
- **Contenu** : 
  - Message personnalisé avec nom et pays de résidence
  - Statistiques de la communauté
  - Conseils pour bien commencer
  - Liens vers les pages importantes

### Variables disponibles dans le template

- `$user->name` : Pseudo de l'utilisateur
- `$user->email` : Email de l'utilisateur  
- `$user->country_residence` : Pays de résidence
- `config('app.url')` : URL du site

## Intégration dans l'inscription

L'email est envoyé automatiquement dans `AuthController::register()` :

```php
// Envoyer l'email de bienvenue (ne bloque pas si échec)
try {
    Mail::to($user->email)->send(new WelcomeEmail($user));
} catch (\Exception $e) {
    // Logged mais n'empêche pas l'inscription
}
```

## Développement local

Pour le développement local, utilisez :

```env
MAIL_MAILER=log
```

Les emails seront écrits dans `storage/logs/laravel.log`

## Sécurité

- ✅ Les mots de passe email ne sont jamais commités dans le code
- ✅ Gestion d'erreurs robuste (try/catch)
- ✅ Logs détaillés pour debugging
- ✅ Template sécurisé (pas d'injection de variables utilisateur)

## Extensions futures possibles

1. **Email de vérification** : Activer `MustVerifyEmail` sur le modèle User
2. **Reset password** : Templates français personnalisés
3. **Notifications communautaires** : Nouveaux articles, événements
4. **Newsletter** : Système d'abonnement aux actualités par pays
5. **Emails transactionnels** : Confirmations d'événements, messages privés

## Commandes utiles

```bash
# Vider le cache des emails
php artisan config:clear

# Voir la configuration email actuelle
php artisan tinker --execute="dd(config('mail'))"

# Tester une adresse email
php artisan tinker --execute="
\$user = App\Models\User::first();
Mail::to('test@example.com')->send(new App\Mail\WelcomeEmail(\$user));
echo 'Email envoyé!';
"
```

## Troubleshooting

### Erreur "Connection refused"
- Vérifiez que le serveur SMTP est accessible
- Testez avec `telnet smtp.zoho.com 587`

### Erreur d'authentification
- Vérifiez username et password
- Assurez-vous que l'authentification 2FA n'est pas activée
- Utilisez un mot de passe d'application si nécessaire

### Emails non reçus
- Vérifiez les dossiers spam/indésirables
- Vérifiez les logs Laravel : `tail -f storage/logs/laravel.log`
- Vérifiez la configuration DNS du domaine expéditeur