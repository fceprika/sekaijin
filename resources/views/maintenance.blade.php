<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance en cours - Sekaijin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-lg w-full space-y-8 text-center px-4">
            <div>
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Sekaijin" class="h-16 w-auto">
                </div>
                
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    🔧 Maintenance en cours
                </h1>
                
                <p class="text-lg text-gray-600 mb-8">
                    Nous améliorons actuellement notre plateforme pour mieux vous servir. 
                    Le site sera de retour très bientôt.
                </p>
                
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Que se passe-t-il ?
                    </h2>
                    <ul class="text-left text-gray-600 space-y-2">
                        <li>✅ Mise à jour des fonctionnalités</li>
                        <li>✅ Amélioration des performances</li>
                        <li>✅ Renforcement de la sécurité</li>
                        <li>🔄 Vérification des systèmes</li>
                    </ul>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">
                        Restez connecté
                    </h3>
                    <p class="text-blue-700 mb-4">
                        Suivez-nous sur nos réseaux sociaux pour les dernières nouvelles.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="text-blue-600 hover:text-blue-800">
                            📘 Facebook
                        </a>
                        <a href="#" class="text-blue-600 hover:text-blue-800">
                            🐦 Twitter
                        </a>
                        <a href="#" class="text-blue-600 hover:text-blue-800">
                            📧 Newsletter
                        </a>
                    </div>
                </div>
                
                <div class="text-sm text-gray-500">
                    <p>Temps estimé de retour : <strong>quelques minutes</strong></p>
                    <p class="mt-2">Merci pour votre patience !</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>