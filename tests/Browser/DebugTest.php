<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Traits\DuskCommonSetup;

class DebugTest extends DuskTestCase
{
    use DuskCommonSetup;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupCommonTestData();
    }

    /**
     * Debug test to capture what's actually happening on CI.
     */
    public function test_debug_pages_content(): void
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== DEBUG TEST STARTED ===\n";
            
            // Test homepage
            echo "Testing homepage...\n";
            $browser->visit('/');
            $this->waitForPageLoad($browser);
            
            $currentUrl = $browser->driver->getCurrentURL();
            $pageTitle = $browser->driver->getTitle();
            $pageSource = $browser->driver->getPageSource();
            
            echo "Homepage URL: {$currentUrl}\n";
            echo "Homepage Title: {$pageTitle}\n";
            echo "Homepage Source (first 1000 chars):\n";
            echo substr($pageSource, 0, 1000) . "\n";
            echo "--- End Homepage Source ---\n";
            
            $browser->screenshot('debug-homepage');
            
            // Test login page
            echo "\nTesting login page...\n";
            $browser->visit('/connexion');
            $this->waitForPageLoad($browser);
            
            $currentUrl = $browser->driver->getCurrentURL();
            $pageTitle = $browser->driver->getTitle();
            $pageSource = $browser->driver->getPageSource();
            
            echo "Login URL: {$currentUrl}\n";
            echo "Login Title: {$pageTitle}\n";
            echo "Login Source (first 1000 chars):\n";
            echo substr($pageSource, 0, 1000) . "\n";
            echo "--- End Login Source ---\n";
            
            $browser->screenshot('debug-login');
            
            // Test registration page
            echo "\nTesting registration page...\n";
            $browser->visit('/inscription');
            $this->waitForPageLoad($browser);
            
            $currentUrl = $browser->driver->getCurrentURL();
            $pageTitle = $browser->driver->getTitle();
            $pageSource = $browser->driver->getPageSource();
            
            echo "Registration URL: {$currentUrl}\n";
            echo "Registration Title: {$pageTitle}\n";
            echo "Registration Source (first 1000 chars):\n";
            echo substr($pageSource, 0, 1000) . "\n";
            echo "--- End Registration Source ---\n";
            
            $browser->screenshot('debug-registration');
            
            echo "\n=== DEBUG TEST COMPLETED ===\n";
            
            // Force the test to pass so we can see the debug output
            $this->assertTrue(true);
        });
    }
}