<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    protected ?string $secretKey;
    protected ?string $siteKey;
    protected float $threshold;
    
    public function __construct()
    {
        $this->secretKey = config('services.recaptcha.secret_key');
        $this->siteKey = config('services.recaptcha.site_key');
        $this->threshold = config('services.recaptcha.threshold', 0.5);
        
        $this->validateConfiguration();
    }
    
    /**
     * Validate reCAPTCHA configuration
     */
    private function validateConfiguration(): void
    {
        if (!$this->isConfigured()) {
            return; // Skip validation if not configured
        }
        
        // Validate secret key format
        if (!preg_match('/^[0-9A-Za-z_-]+$/', $this->secretKey)) {
            throw new \InvalidArgumentException('Invalid reCAPTCHA secret key format');
        }
        
        // Validate site key format
        if (!preg_match('/^[0-9A-Za-z_-]+$/', $this->siteKey)) {
            throw new \InvalidArgumentException('Invalid reCAPTCHA site key format');
        }
        
        // Validate threshold
        if ($this->threshold < 0 || $this->threshold > 1) {
            throw new \InvalidArgumentException('reCAPTCHA threshold must be between 0 and 1');
        }
    }
    
    /**
     * Verify reCAPTCHA v3 token
     */
    public function verify(?string $token, string $action = null): bool
    {
        // If no token provided, check if we should bypass in development
        if (empty($token)) {
            Log::warning('reCAPTCHA token is empty');
            return $this->shouldBypassInDevelopment();
        }
        if (empty($this->secretKey)) {
            Log::warning('reCAPTCHA secret key not configured');
            return $this->shouldBypassInDevelopment();
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
    
    /**
     * Determine if verification should be bypassed in development
     */
    private function shouldBypassInDevelopment(): bool
    {
        return app()->environment('local') && config('services.recaptcha.bypass_local', false);
    }
}