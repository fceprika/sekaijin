<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifiez votre adresse email - Sekaijin</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #3B82F6, #8B5CF6); color: white; padding: 30px; border-radius: 10px 10px 0 0; text-align: center; }
        .content { background: #fff; padding: 30px; border: 1px solid #e5e7eb; }
        .button { display: inline-block; background: #3B82F6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0; }
        .footer { background: #f9fafb; padding: 20px; border-radius: 0 0 10px 10px; text-align: center; color: #6b7280; font-size: 14px; }
        ul { padding-left: 20px; }
        .link { word-break: break-all; background: #f3f4f6; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Vérifiez votre adresse email</h1>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $user->name }}</strong> 👋</p>
        
        <p>Bienvenue dans la communauté <strong>Sekaijin</strong> ! Nous sommes ravis de vous compter parmi nous.</p>
        
        <p>Pour activer votre compte et accéder à toutes les fonctionnalités, veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email :</p>
        
        <div style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="button">Vérifier mon email</a>
        </div>
        
        <h3>Qu'est-ce qui vous attend après la vérification ?</h3>
        
        <p>Une fois votre email vérifié, vous pourrez :</p>
        
        <ul>
            <li>✅ <strong>Créer des articles</strong> et partager vos expériences d'expatrié</li>
            <li>✅ <strong>Organiser des événements</strong> et rencontrer d'autres expatriés</li>
            <li>✅ <strong>Publier des annonces</strong> (vente, location, services)</li>
            <li>✅ <strong>Rendre votre profil public</strong> et être trouvé par la communauté</li>
            <li>✅ <strong>Uploader votre avatar</strong> et personnaliser votre profil</li>
        </ul>
        
        <h3>Problème avec le lien ?</h3>
        
        <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
        
        <div class="link">{!! e($verificationUrl) !!}</div>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
        
        <p style="color: #dc2626;"><strong>⏰ Important :</strong> Ce lien de vérification expirera dans <strong>{{ config('auth.verification.expire', 60) }} minutes</strong>.</p>
        
        <p style="color: #6b7280;">Si vous n'avez pas créé de compte sur Sekaijin, aucune action n'est requise de votre part.</p>
    </div>
    
    <div class="footer">
        <p>Merci et bienvenue dans l'aventure Sekaijin ! 🌍</p>
        <p><strong>L'équipe Sekaijin</strong></p>
    </div>
</body>
</html>