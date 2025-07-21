<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EmailBlacklistService
{
    /**
     * Cache TTL for custom blacklist (1 week).
     */
    private const CACHE_TTL = 604800; // 7 days in seconds

    /**
     * Cache TTL for custom blacklist updates (shorter for dynamic updates).
     */
    private const DYNAMIC_CACHE_TTL = 86400; // 1 day in seconds

    /**
     * Common disposable email domains (loaded once per instance).
     */
    private static ?array $disposableDomains = null;

    /**
     * Get disposable domains with lazy loading.
     */
    private function getDisposableDomains(): array
    {
        if (self::$disposableDomains === null) {
            self::$disposableDomains = [
                // Test domains
                'example.com',
                'example.org',
                'example.net',
                'test.com',
                'localhost',

                // Common disposable email providers
                '10minutemail.com',
                'guerrillamail.com',
                'mailinator.com',
                'maildrop.cc',
                'throwaway.email',
                'tempmail.com',
                'temp-mail.org',
                'getnada.com',
                'trashmail.com',
                'yopmail.com',
                'sharklasers.com',
                'guerrillamailblock.com',
                'pokemail.net',
                'spam4.me',
                'grr.la',
                'mailnesia.com',
                'receivemail.org',
                'koszmail.pl',
                'tmail.ws',
                'anonmails.de',
                'noclickemail.com',
                'incognitomail.net',
                'mailcatch.com',
                'einrot.com',
                'safersignup.com',
                'temporaryemail.net',
                'throwemail.net',
                'tmails.net',
                'fakeinbox.com',
                'emailondeck.com',
                'throwawaymail.com',
                'tempmails.org',
                'crazymailing.com',
                'mailsac.com',
                'inbox.lv',
                'guerrillamail.net',
                'guerrillamail.org',
                'guerrillamail.biz',
                '33mail.com',
                'mytrashmail.com',
                'mt2015.com',
                'thankyou2010.com',
                'trash2009.com',
                'mt2009.com',
                'trashymail.com',
                'temporaryinbox.com',
                'disposableaddress.com',
                'filzmail.com',
                'freemail.ms',
                'getairmail.com',
                'gustr.com',
                'mintemail.com',
                'tempinbox.com',
                'mohmal.com',
            ];
        }

        return self::$disposableDomains;
    }

    /**
     * Specific blacklisted emails.
     */
    protected array $blacklistedEmails = [
        'test@example.com',
        'test@test.com',
        'admin@example.com',
        'noreply@example.com',
    ];

    /**
     * Check if an email is blacklisted.
     */
    public function isBlacklisted(string $email): bool
    {
        $email = strtolower(trim($email));

        // Check if email is in specific blacklist
        if (in_array($email, $this->blacklistedEmails)) {
            Log::warning('Email in blacklist', ['email' => $email]);

            return true;
        }

        // Extract domain from email
        $domain = $this->extractDomain($email);
        if (! $domain) {
            return false;
        }

        // Check if domain is disposable
        if ($this->isDisposableDomain($domain)) {
            Log::warning('Disposable email domain detected', [
                'email' => $email,
                'domain' => $domain,
            ]);

            return true;
        }

        // Check custom blacklist from cache/database
        if ($this->isInCustomBlacklist($email, $domain)) {
            return true;
        }

        return false;
    }

    /**
     * Extract domain from email.
     */
    protected function extractDomain(string $email): ?string
    {
        $parts = explode('@', $email);

        return count($parts) === 2 ? strtolower($parts[1]) : null;
    }

    /**
     * Check if domain is disposable.
     */
    protected function isDisposableDomain(string $domain): bool
    {
        return in_array($domain, $this->getDisposableDomains());
    }

    /**
     * Check custom blacklist (from cache/database).
     */
    protected function isInCustomBlacklist(string $email, string $domain): bool
    {
        // Check cached custom blacklist
        $customBlacklist = Cache::get('email_blacklist', []);

        if (isset($customBlacklist['emails']) && in_array($email, $customBlacklist['emails'])) {
            Log::warning('Email in custom blacklist', ['email' => $email]);

            return true;
        }

        if (isset($customBlacklist['domains']) && in_array($domain, $customBlacklist['domains'])) {
            Log::warning('Domain in custom blacklist', ['domain' => $domain]);

            return true;
        }

        return false;
    }

    /**
     * Add email to blacklist.
     */
    public function addToBlacklist(string $email): void
    {
        $blacklist = Cache::get('email_blacklist', ['emails' => [], 'domains' => []]);

        if (! in_array($email, $blacklist['emails'])) {
            $blacklist['emails'][] = strtolower(trim($email));
            Cache::put('email_blacklist', $blacklist, self::DYNAMIC_CACHE_TTL);

            Log::info('Email added to blacklist', ['email' => $email]);
        }
    }

    /**
     * Add domain to blacklist.
     */
    public function addDomainToBlacklist(string $domain): void
    {
        $blacklist = Cache::get('email_blacklist', ['emails' => [], 'domains' => []]);

        if (! in_array($domain, $blacklist['domains'])) {
            $blacklist['domains'][] = strtolower(trim($domain));
            Cache::put('email_blacklist', $blacklist, self::DYNAMIC_CACHE_TTL);

            Log::info('Domain added to blacklist', ['domain' => $domain]);
        }
    }

    /**
     * Get all disposable domains for frontend validation.
     */
    public function getDisposableDomainsForFrontend(): array
    {
        return $this->getDisposableDomains();
    }
}
