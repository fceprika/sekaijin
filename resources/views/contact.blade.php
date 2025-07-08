@extends('layout')

@section('title', 'Contact - Sekaijin')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Contactez-nous</h1>
                <p class="text-blue-100">Nous sommes là pour vous aider et répondre à vos questions</p>
            </div>

            <div class="px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Formulaire de contact -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <span class="mr-3">✉️</span>
                            Envoyez-nous un message
                        </h2>
                        <form id="contact-form" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required placeholder="Votre nom et prénom">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required placeholder="votre@email.com">
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                                <select id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Sélectionnez un sujet</option>
                                    <option value="support">Support technique</option>
                                    <option value="account">Problème de compte</option>
                                    <option value="content">Problème de contenu</option>
                                    <option value="partnership">Partenariat</option>
                                    <option value="suggestion">Suggestion d'amélioration</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                <textarea id="message" name="message" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required placeholder="Décrivez votre demande en détail..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition duration-300 flex items-center justify-center">
                                <span class="mr-2">📧</span>
                                Envoyer le message
                            </button>
                        </form>
                    </div>
                    
                    <!-- Informations de contact -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <span class="mr-3">📞</span>
                            Informations de contact
                        </h2>
                        
                        <div class="space-y-6">
                            <!-- Email principal -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <div class="flex items-center mb-3">
                                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-blue-900">Email principal</h3>
                                        <a href="mailto:contact@sekaijin.fr" class="text-blue-600 hover:text-blue-800 font-medium">contact@sekaijin.fr</a>
                                    </div>
                                </div>
                                <p class="text-blue-700 text-sm">
                                    Pour toutes vos questions générales, support technique, ou demandes de partenariat.
                                </p>
                            </div>
                            
                            <!-- Adresse -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                <div class="flex items-center mb-3">
                                    <div class="bg-green-100 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-green-900">Adresse</h3>
                                        <p class="text-green-700">Sekaijin LLC</p>
                                        <p class="text-green-600 text-sm">1021 E Lincolnway Suite 8081</p>
                                        <p class="text-green-600 text-sm">Cheyenne, WY 82001, États-Unis</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Temps de réponse -->
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                                <div class="flex items-center mb-3">
                                    <div class="bg-purple-100 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-purple-900">Temps de réponse</h3>
                                        <p class="text-purple-700">Sous 24-48 heures</p>
                                    </div>
                                </div>
                                <p class="text-purple-700 text-sm">
                                    Nous nous efforçons de répondre à tous les messages dans les plus brefs délais.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Section FAQ -->
                        <div class="mt-8 bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-lg p-6">
                            <h3 class="font-bold text-orange-900 mb-3 flex items-center">
                                <span class="mr-2">❓</span>
                                Questions fréquentes
                            </h3>
                            <p class="text-orange-700 text-sm mb-3">
                                Avant de nous contacter, consultez notre FAQ qui pourrait répondre à vos questions :
                            </p>
                            <ul class="text-orange-700 text-sm space-y-1">
                                <li>• Comment créer un compte ?</li>
                                <li>• Comment modifier mon profil ?</li>
                                <li>• Comment publier du contenu ?</li>
                                <li>• Comment supprimer mon compte ?</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();
        
        const name = $('#name').val();
        const email = $('#email').val();
        const subject = $('#subject').val();
        const message = $('#message').val();
        
        if (name && email && subject && message) {
            alert('Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.');
            $(this)[0].reset();
        } else {
            alert('Veuillez remplir tous les champs obligatoires.');
        }
    });
});
</script>
@endsection