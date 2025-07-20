<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact - Sekaijin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .subject-badge {
            display: inline-block;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .sender-info {
            background-color: #f1f5f9;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .sender-info h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 16px;
        }
        .sender-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .message-content {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            white-space: pre-line;
            font-size: 15px;
            line-height: 1.7;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 15px 0;
        }
        .meta-info {
            font-size: 12px;
            color: #64748b;
            margin-top: 15px;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🌍 SEKAIJIN</div>
            <p style="margin: 0; color: #64748b;">Nouveau message de contact</p>
        </div>

        <div class="subject-badge">
            {{ $contactSubject }}
        </div>

        <div class="sender-info">
            <h3>Informations de l'expéditeur</h3>
            <p><strong>Nom :</strong> {{ $contactName }}</p>
            <p><strong>Email :</strong> <a href="mailto:{{ $contactEmail }}" style="color: #3b82f6;">{{ $contactEmail }}</a></p>
        </div>

        <h3 style="color: #1e40af; margin-bottom: 10px;">Message :</h3>
        <div class="message-content">{{ $contactMessage }}</div>

        <div style="text-align: center; margin: 25px 0;">
            <a href="mailto:{{ $contactEmail }}?subject=Re: {{ $contactSubject }}" class="cta-button">
                Répondre directement
            </a>
        </div>

        <div class="meta-info">
            <p><strong>Reçu le :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
            <p><strong>Catégorie :</strong> {{ $contactSubject }}</p>
            <p><strong>Via :</strong> Formulaire de contact Sekaijin</p>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement depuis le formulaire de contact de Sekaijin.</p>
            <p>Pour répondre, utilisez directement l'adresse email de l'expéditeur : {{ $contactEmail }}</p>
            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 15px 0;">
            <p style="margin: 0;">
                <strong>Sekaijin</strong> - La communauté des expatriés français<br>
                <a href="https://sekaijin.com" style="color: #3b82f6;">sekaijin.com</a>
            </p>
        </div>
    </div>
</body>
</html>