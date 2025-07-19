<?php

namespace Tests\Traits;

use App\Models\Country;

trait DuskCommonSetup
{
    /**
     * Setup common test data for Dusk tests.
     */
    protected function setupCommonTestData(): void
    {
        // Create required countries for tests
        $this->ensureCountryExists('Thaïlande', 'thailande', '🇹🇭');
        $this->ensureCountryExists('Japon', 'japon', '🇯🇵');
        $this->ensureCountryExists('Vietnam', 'vietnam', '🇻🇳');
        $this->ensureCountryExists('Chine', 'chine', '🇨🇳');
    }

    /**
     * Ensure a country exists in the database.
     */
    private function ensureCountryExists(string $name, string $slug, string $emoji): Country
    {
        return Country::firstOrCreate(
            ['slug' => $slug],
            [
                'name_fr' => $name,
                'emoji' => $emoji,
                'description' => "Description pour {$name}",
            ]
        );
    }

    /**
     * Generate a unique test username.
     */
    protected function generateTestUsername(string $prefix = 'TestUser'): string
    {
        return $prefix . '_' . \Illuminate\Support\Str::random(8);
    }

    /**
     * Generate a unique test email.
     */
    protected function generateTestEmail(string $prefix = 'test'): string
    {
        return $prefix . '_' . \Illuminate\Support\Str::random(8) . '@example.com';
    }

    /**
     * Wait for an element to be visible and interactable.
     */
    protected function waitForElement($browser, string $selector, int $timeout = 5): void
    {
        $browser->waitFor($selector, $timeout)
            ->waitUntil("document.querySelector('{$selector}') && 
                           getComputedStyle(document.querySelector('{$selector}')).display !== 'none'", $timeout);
    }

    /**
     * Wait for page to be fully loaded.
     */
    protected function waitForPageLoad($browser, int $timeout = 10): void
    {
        $browser->waitUntil('document.readyState === "complete"', $timeout);
    }
}
