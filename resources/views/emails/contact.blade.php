<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact - Sekaijin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .field {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #3b82f6;
        }
        .field-label {
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 5px;
        }
        .field-value {
            color: #333;
            word-break: break-word;
        }
        .message-content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            white-space: pre-wrap;
            font-family: Georgia, serif;
            line-height: 1.8;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌍 Nouveau message de contact</h1>
            <p>Un utilisateur a envoyé un message via le formulaire de contact de Sekaijin</p>
        </div>

        <div class="field">
            <div class="field-label">👤 Nom :</div>
            <div class="field-value">{{ $contactName }}</div>
        </div>

        <div class="field">
            <div class="field-label">📧 Email :</div>
            <div class="field-value">{{ $contactEmail }}</div>
        </div>

        <div class="field">
            <div class="field-label">📋 Sujet :</div>
            <div class="field-value">{{ $contactSubject }}</div>
        </div>

        <div class="field">
            <div class="field-label">💬 Message :</div>
            <div class="message-content">{{ $contactMessage }}</div>
        </div>

        <div class="footer">
            <p>
                📨 Email envoyé automatiquement depuis le site <a href="{{ url('/') }}">Sekaijin</a><br>
                🔒 Cet email provient d'un formulaire sécurisé par Cloudflare Turnstile<br>
                🕒 Reçu le {{ date('d/m/Y à H:i') }}
            </p>
            
            <p style="margin-top: 15px; font-size: 11px;">
                💡 Pour répondre directement à l'utilisateur, utilisez la fonction "Répondre" de votre client email.<br>
                L'adresse de réponse est automatiquement configurée sur : {{ $contactEmail }}
            </p>
        </div>
    </div>
</body>
</html>