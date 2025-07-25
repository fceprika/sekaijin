<?php

namespace App\Http\Controllers;

use App\Services\EmailBlacklistService;
use App\Services\TurnstileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    protected TurnstileService $turnstileService;
    protected EmailBlacklistService $blacklistService;

    public function __construct(TurnstileService $turnstileService, EmailBlacklistService $blacklistService)
    {
        $this->turnstileService = $turnstileService;
        $this->blacklistService = $blacklistService;
    }

    public function show()
    {
        $seoService = new \App\Services\SeoService;
        $seoData = $seoService->generateSeoData('contact');
        $structuredData = $seoService->generateStructuredData('contact');

        return view('contact', compact('seoData', 'structuredData'));
    }

    public function send(Request $request)
    {
        // Verify Turnstile protection
        $turnstileCheck = $this->verifyTurnstileForRequest($request, 'contact');
        if ($turnstileCheck !== null) {
            return $turnstileCheck;
        }

        // Check email blacklist
        if ($this->blacklistService->isBlacklisted($request->input('email'))) {
            \Log::warning('Blacklisted email attempted contact form', [
                'email' => $request->input('email'),
                'ip' => $this->anonymizeIp($request->ip()),
            ]);

            return response()->json([
                'success' => false,
                'errors' => ['email' => ['Cette adresse email n\'est pas autorisée. Veuillez utiliser une adresse email valide.']],
            ], 422);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|in:support,account,content,partnership,suggestion,other',
            'message' => 'required|string|min:10|max:5000',
        ], [
            'name.required' => 'Veuillez indiquer votre nom.',
            'email.required' => 'Veuillez indiquer votre email.',
            'email.email' => 'Veuillez indiquer un email valide.',
            'subject.required' => 'Veuillez sélectionner un sujet.',
            'subject.in' => 'Veuillez sélectionner un sujet valide.',
            'message.required' => 'Veuillez écrire votre message.',
            'message.min' => 'Votre message doit contenir au moins 10 caractères.',
            'message.max' => 'Votre message ne peut pas dépasser 5000 caractères.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Get subject label
            $subjectLabels = [
                'support' => 'Support technique',
                'account' => 'Problème de compte',
                'content' => 'Problème de contenu',
                'partnership' => 'Partenariat',
                'suggestion' => 'Suggestion d\'amélioration',
                'other' => 'Autre',
            ];

            $subjectLabel = $subjectLabels[$request->subject] ?? 'Autre';

            // Send email
            Mail::send('emails.contact', [
                'contactName' => $request->name,
                'contactEmail' => $request->email,
                'contactSubject' => $subjectLabel,
                'contactMessage' => $request->message,
            ], function ($message) use ($request, $subjectLabel) {
                $message->to('contact@sekaijin.fr')
                    ->subject('[Sekaijin Contact] ' . $subjectLabel . ' - ' . $request->name)
                    ->replyTo($request->email, $request->name);
            });

            // Log contact form submission
            \Log::info('Contact form submitted', [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.',
            ]);

        } catch (\Exception $e) {
            \Log::error('Contact form error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.',
            ], 500);
        }
    }

    /**
     * Verify Turnstile protection for a request.
     */
    private function verifyTurnstileForRequest(Request $request, string $action): ?JsonResponse
    {
        if (! $this->shouldVerifyTurnstile($request)) {
            return null;
        }

        if (! $this->turnstileService->verify($request->input('cf-turnstile-response'), $action)) {
            return response()->json([
                'success' => false,
                'errors' => ['turnstile' => ['Vérification de sécurité échouée. Veuillez réessayer.']],
            ], 422);
        }

        return null;
    }

    /**
     * Determine if Turnstile verification should be performed.
     */
    private function shouldVerifyTurnstile(Request $request): bool
    {
        return $this->turnstileService->isConfigured() && ! app()->environment('local');
    }

    /**
     * Anonymize IP address for GDPR compliance.
     */
    private function anonymizeIp(string $ip): string
    {
        // For IPv4: zero out the last octet
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '0';

            return implode('.', $parts);
        }

        // For IPv6: zero out the last 64 bits
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ip);
            for ($i = 4; $i < count($parts); $i++) {
                $parts[$i] = '0';
            }

            return implode(':', $parts);
        }

        return 'unknown';
    }
}
