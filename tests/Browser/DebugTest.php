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
            
            // Test AJAX registration endpoint
            echo "\nTesting registration AJAX endpoint...\n";
            $browser->visit('/inscription');
            $this->waitForPageLoad($browser);
            
            // Execute JavaScript to test AJAX registration
            $browser->script([
                "fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
                    },
                    body: JSON.stringify({
                        name: 'TestDebug',
                        email: 'debug@test.com',
                        password: 'Password123!',
                        password_confirmation: 'Password123!',
                        terms: true
                    })
                })
                .then(response => response.json())
                .then(data => console.log('Registration response:', JSON.stringify(data)))
                .catch(error => console.log('Registration error:', error));"
            ]);
            
            // Wait a bit for the AJAX call
            $browser->pause(2000);
            
            // Check console logs
            $logs = $browser->driver->manage()->getLog('browser');
            echo "\nBrowser console logs:\n";
            foreach ($logs as $log) {
                echo $log['level'] . ': ' . $log['message'] . "\n";
            }
            
            echo "\n=== DEBUG TEST COMPLETED ===\n";
            
            // Force the test to pass so we can see the debug output
            $this->assertTrue(true);
        });
    }
}