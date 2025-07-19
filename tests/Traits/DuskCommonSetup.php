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
     * Get timeout value adjusted for CI environment.
     */
    protected function getTimeout(int $default = 5): int
    {
        $isCI = env('CI', false) || env('GITHUB_ACTIONS', false);

        return $isCI ? $default * 3 : $default; // Triple timeout for CI
    }

    /**
     * Wait for an element to be visible and interactable.
     */
    protected function waitForElement($browser, string $selector, int $timeout = 5): void
    {
        $adjustedTimeout = $this->getTimeout($timeout);
        $browser->waitFor($selector, $adjustedTimeout)
            ->waitUntil("document.querySelector('{$selector}') && 
                           getComputedStyle(document.querySelector('{$selector}')).display !== 'none'", $adjustedTimeout);
    }

    /**
     * Wait for page to be fully loaded.
     */
    protected function waitForPageLoad($browser, int $timeout = 10): void
    {
        $adjustedTimeout = $this->getTimeout($timeout);
        $browser->waitUntil('document.readyState === "complete"', $adjustedTimeout);
    }

    /**
     * Wait for text to appear with CI-adjusted timeout.
     */
    protected function waitForTextCI($browser, string $text, int $timeout = 5): void
    {
        $adjustedTimeout = $this->getTimeout($timeout);
        $browser->waitForText($text, $adjustedTimeout);
    }

    /**
     * Wait for location with CI-adjusted timeout.
     */
    protected function waitForLocationCI($browser, string $location, int $timeout = 5): void
    {
        $adjustedTimeout = $this->getTimeout($timeout);
        $browser->waitForLocation($location, $adjustedTimeout);
    }
}
