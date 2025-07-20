<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message de contact - Sekaijin</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px;">
        <h2 style="color: #2563eb; margin-bottom: 20px;">Nouveau message de contact</h2>
        
        <div style="background-color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <p><strong>Nom :</strong> {{ $contactName }}</p>
            <p><strong>Email :</strong> <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
            <p><strong>Sujet :</strong> {{ $contactSubject }}</p>
        </div>
        
        <div style="background-color: white; padding: 20px; border-radius: 8px;">
            <h3 style="color: #1e40af; margin-bottom: 15px;">Message :</h3>
            <p style="white-space: pre-wrap;">{{ $contactMessage }}</p>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 14px;">
            <p>Ce message a été envoyé depuis le formulaire de contact de Sekaijin.</p>
        </div>
    </div>
</body>
</html>