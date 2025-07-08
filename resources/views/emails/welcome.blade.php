<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue dans la communauté Sekaijin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .tagline {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            line-height: 1.8;
            color: #4a5568;
            margin-bottom: 25px;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin: 30px 0;
            text-align: center;
        }
        
        .highlight-box h3 {
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            padding: 20px;
            background-color: #f7fafc;
            border-radius: 8px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            font-size: 14px;
            color: #718096;
            margin-top: 5px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }
        
        .tips {
            background-color: #edf2f7;
            padding: 25px;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .tips h4 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .tips ul {
            list-style: none;
            padding-left: 0;
        }
        
        .tips li {
            margin-bottom: 12px;
            padding-left: 25px;
            position: relative;
            line-height: 1.6;
        }
        
        .tips li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #48bb78;
            font-weight: bold;
            font-size: 16px;
            margin-right: 8px;
        }
        
        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 30px;
            text-align: center;
        }
        
        .footer-links {
            margin-bottom: 20px;
        }
        
        .footer-links a {
            color: #a0aec0;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
        }
        
        .footer-links a:hover {
            color: #667eea;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            color: #a0aec0;
            text-decoration: none;
            margin: 0 10px;
            font-size: 20px;
        }
        
        .copyright {
            font-size: 12px;
            color: #718096;
            margin-top: 20px;
        }
        
        @media (max-width: 600px) {
            .container {
                width: 100%;
                margin: 0;
            }
            
            .header, .content, .footer {
                padding: 20px;
            }
            
            .stats {
                flex-direction: column;
                gap: 15px;
            }
            
            .greeting {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🌍 Sekaijin</div>
            <div class="tagline">La communauté des expatriés français</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour {{ $user->name }} ! 👋
            </div>
            
            <div class="message">
                Bienvenue dans la communauté <strong>Sekaijin</strong> ! Nous sommes ravis de vous compter parmi nous.
            </div>
            
            <div class="message">
                Que vous soyez installé{{ $user->country_residence ? ' en ' . $user->country_residence : '' }} depuis peu ou que vous soyez un expatrié chevronné, notre plateforme vous permettra de :
            </div>
            
            <div class="highlight-box">
                <h3>🤝 Connectez-vous avec d'autres Français</h3>
                <p>Partagez vos expériences, posez vos questions et créez des liens durables avec des compatriotes du monde entier.</p>
            </div>
            
            <!-- Statistics -->
            <div class="stats">
                <div class="stat">
                    <div class="stat-number">{{ $totalMembers ?? 12 }}+</div>
                    <div class="stat-label">Membres actifs</div>
                </div>
                <div class="stat">
                    <div class="stat-number">{{ $countriesCovered ?? 3 }}+</div>
                    <div class="stat-label">Pays couverts</div>
                </div>
                <div class="stat">
                    <div class="stat-number">{{ $totalContent ?? 20 }}+</div>
                    <div class="stat-label">Articles & actualités</div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}" class="cta-button">
                    Découvrir la communauté
                </a>
            </div>
            
            <!-- Tips -->
            <div class="tips">
                <h4>💡 Pour bien commencer :</h4>
                <ul>
                    <li>Complétez votre profil avec une photo et une description</li>
                    <li>Rejoignez les discussions de votre pays de résidence</li>
                    <li>Partagez vos bonnes adresses et conseils pratiques</li>
                    <li>Participez aux événements communautaires</li>
                    <li>N'hésitez pas à poser vos questions !</li>
                </ul>
            </div>
            
            <div class="message">
                Si vous avez des questions ou besoin d'aide, n'hésitez pas à nous contacter à <a href="mailto:contact@sekaijin.fr" style="color: #667eea;">contact@sekaijin.fr</a>.
            </div>
            
            <div class="message">
                Bonne découverte et à bientôt sur Sekaijin ! 🇫🇷
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-links">
                <a href="{{ config('app.url') }}/about">À propos</a>
                <a href="{{ config('app.url') }}/contact">Contact</a>
                <a href="{{ config('app.url') }}/politique-confidentialite">Confidentialité</a>
                <a href="{{ config('app.url') }}/conditions-utilisation">Conditions</a>
            </div>
            
            <div class="social-links">
                <a href="#">📘</a>
                <a href="#">📷</a>
                <a href="#">🐦</a>
                <a href="#">💼</a>
            </div>
            
            <div class="copyright">
                © {{ date('Y') }} Sekaijin - Communauté des expatriés français<br>
                Cet email a été envoyé à {{ $user->email }}
            </div>
        </div>
    </div>
</body>
</html>