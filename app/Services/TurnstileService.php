<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TurnstileService
{
    protected ?string $siteKey;
    protected ?string $secretKey;
    protected bool $bypassLocal;

    public function __construct()
    {
        $this->siteKey = config('services.turnstile.key');
        $this->secretKey = config('services.turnstile.secret');
        $this->bypassLocal = config('services.turnstile.bypass_local', false);

        $this->validateConfiguration();
    }

    /**
     * Validate Turnstile configuration.
     */
    private function validateConfiguration(): void
    {
        if (! $this->isConfigured()) {
            return; // Skip validation if not configured
        }

        // Basic length validation for Turnstile keys (Cloudflare will validate authenticity)
        if (strlen($this->secretKey) < 10) {
            throw new \InvalidArgumentException('Turnstile secret key appears to be too short');
        }

        if (strlen($this->siteKey) < 10) {
            throw new \InvalidArgumentException('Turnstile site key appears to be too short');
        }
    }

    /**
     * Verify Turnstile token.
     */
    public function verify(?string $token, ?string $action = null): bool
    {
        // If no token provided, check if we should bypass in development
        if (empty($token)) {
            return $this->shouldBypassInDevelopment();
        }

        // Sanitize and validate token format
        $token = $this->sanitizeToken($token);
        if (! $token) {
            return false;
        }

        if (empty($this->secretKey)) {
            return $this->shouldBypassInDevelopment();
        }

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful()) {
                return false;
            }

            $result = $response->json();

            if (! isset($result['success'])) {
                return false;
            }

            $success = $result['success'] === true;

            return $success;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if Turnstile is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->siteKey) && ! empty($this->secretKey);
    }

    /**
     * Get site key for frontend.
     */
    public function getSiteKey(): ?string
    {
        return $this->siteKey;
    }

    /**
     * Check if should bypass verification in development.
     */
    private function shouldBypassInDevelopment(): bool
    {
        return app()->environment('local') && $this->bypassLocal;
    }

    /**
     * Sanitize and validate Turnstile token.
     */
    private function sanitizeToken(string $token): ?string
    {
        // Remove any whitespace
        $token = trim($token);

        // Basic validation: Turnstile tokens should be reasonable length and contain valid characters
        if (strlen($token) < 10 || strlen($token) > 2048) {
            return null;
        }

        // Allow alphanumeric, dots, dashes, underscores (typical token characters)
        if (! preg_match('/^[a-zA-Z0-9.\-_]+$/', $token)) {
            return null;
        }

        return $token;
    }
}
