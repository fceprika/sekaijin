<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     * Validate Turnstile configuration
     */
    private function validateConfiguration(): void
    {
        if (!$this->isConfigured()) {
            return; // Skip validation if not configured
        }
        
        // Validate secret key format (Turnstile keys are longer than reCAPTCHA)
        if (!preg_match('/^[0-9A-Za-z_-]+$/', $this->secretKey)) {
            throw new \InvalidArgumentException('Invalid Turnstile secret key format');
        }
        
        // Validate site key format
        if (!preg_match('/^[0-9A-Za-z_-]+$/', $this->siteKey)) {
            throw new \InvalidArgumentException('Invalid Turnstile site key format');
        }
    }
    
    /**
     * Verify Turnstile token
     */
    public function verify(?string $token, string $action = null): bool
    {
        // If no token provided, check if we should bypass in development
        if (empty($token)) {
            Log::warning('Turnstile token is empty');
            return $this->shouldBypassInDevelopment();
        }
        
        if (empty($this->secretKey)) {
            Log::warning('Turnstile secret key not configured');
            return $this->shouldBypassInDevelopment();
        }
        
        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);
            
            if (!$response->successful()) {
                Log::error('Turnstile API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }
            
            $result = $response->json();
            
            if (!isset($result['success'])) {
                Log::error('Invalid Turnstile API response format', [
                    'response' => $result,
                ]);
                return false;
            }
            
            $success = $result['success'] === true;
            
            if (!$success) {
                Log::warning('Turnstile verification failed', [
                    'error_codes' => $result['error-codes'] ?? [],
                    'action' => $action,
                    'ip' => request()->ip(),
                ]);
            } else {
                Log::info('Turnstile verification successful', [
                    'action' => $action,
                    'ip' => request()->ip(),
                ]);
            }
            
            return $success;
            
        } catch (\Exception $e) {
            Log::error('Turnstile verification exception', [
                'error' => $e->getMessage(),
                'action' => $action,
                'ip' => request()->ip(),
            ]);
            
            return false;
        }
    }
    
    /**
     * Check if Turnstile is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->siteKey) && !empty($this->secretKey);
    }
    
    /**
     * Get site key for frontend
     */
    public function getSiteKey(): ?string
    {
        return $this->siteKey;
    }
    
    /**
     * Check if should bypass verification in development
     */
    private function shouldBypassInDevelopment(): bool
    {
        return app()->environment('local') && $this->bypassLocal;
    }
}