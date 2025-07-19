@component('mail::message')
# Vérifiez votre adresse email

Bonjour {{ $user->name }} 👋

Bienvenue dans la communauté **Sekaijin** ! Nous sommes ravis de vous compter parmi nous.

Pour activer votre compte et accéder à toutes les fonctionnalités, veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email :

@component('mail::button', ['url' => $verificationUrl, 'color' => 'primary'])
Vérifier mon email
@endcomponent

## Qu'est-ce qui vous attend après la vérification ?

Une fois votre email vérifié, vous pourrez :

- ✅ **Créer des articles** et partager vos expériences d'expatrié
- ✅ **Organiser des événements** et rencontrer d'autres expatriés  
- ✅ **Publier des annonces** (vente, location, services)
- ✅ **Rendre votre profil public** et être trouvé par la communauté
- ✅ **Uploader votre avatar** et personnaliser votre profil

## Problème avec le lien ?

Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :

{{ $verificationUrl }}

---

Ce lien de vérification expirera dans **{{ config('auth.verification.expire', 60) }} minutes**.

Si vous n'avez pas créé de compte sur Sekaijin, aucune action n'est requise de votre part.

Merci et bienvenue dans l'aventure Sekaijin ! 🌍

L'équipe Sekaijin
@endcomponent