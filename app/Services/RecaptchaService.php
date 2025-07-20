<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    protected ?string $secretKey;
    protected float $threshold;
    
    public function __construct()
    {
        $this->secretKey = config('services.recaptcha.secret_key');
        $this->threshold = config('services.recaptcha.threshold', 0.5);
    }
    
    /**
     * Verify reCAPTCHA v3 token
     */
    public function verify(?string $token, string $action = null): bool
    {
        // If no token provided, fail verification
        if (empty($token)) {
            Log::warning('reCAPTCHA token is empty');
            return false;
        }
        if (empty($this->secretKey)) {
            Log::warning('reCAPTCHA secret key not configured');
            return app()->environment('local'); // Allow in local development
        }
        
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);
            
            if (!$response->successful()) {
                Log::error('reCAPTCHA API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }
            
            $result = $response->json();
            
            // Log the verification for debugging
            Log::info('reCAPTCHA verification', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? 0,
                'action' => $result['action'] ?? null,
                'expected_action' => $action,
                'ip' => request()->ip(),
            ]);
            
            // Check if verification was successful
            if (!($result['success'] ?? false)) {
                Log::warning('reCAPTCHA verification failed', [
                    'error_codes' => $result['error-codes'] ?? [],
                ]);
                return false;
            }
            
            // Check score threshold
            $score = $result['score'] ?? 0;
            if ($score < $this->threshold) {
                Log::warning('reCAPTCHA score below threshold', [
                    'score' => $score,
                    'threshold' => $this->threshold,
                    'ip' => request()->ip(),
                ]);
                return false;
            }
            
            // Check action if provided
            if ($action && isset($result['action']) && $result['action'] !== $action) {
                Log::warning('reCAPTCHA action mismatch', [
                    'expected' => $action,
                    'actual' => $result['action'],
                ]);
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
    
    /**
     * Get the site key for frontend
     */
    public function getSiteKey(): ?string
    {
        return config('services.recaptcha.site_key');
    }
    
    /**
     * Check if reCAPTCHA is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->secretKey) && !empty($this->getSiteKey());
    }
}