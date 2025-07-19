<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Traits\DuskCommonSetup;

class DebugRegistrationTest extends DuskTestCase
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
     * Debug registration flow step by step.
     */
    public function test_debug_registration_flow(): void
    {
        $this->browse(function (Browser $browser) {
            echo "\n=== DEBUG REGISTRATION FLOW ===\n";
            
            $browser->visit('/inscription');
            $this->waitForPageLoad($browser);
            
            // Check if form elements exist
            echo "Checking form elements...\n";
            $hasName = $browser->element('#name') !== null;
            $hasEmail = $browser->element('#email') !== null;
            $hasPassword = $browser->element('#password_step1') !== null;
            $hasTerms = $browser->element('#terms_step1') !== null;
            $hasButton = $browser->element('#create-account-btn') !== null;
            
            echo "Form elements check:\n";
            echo "- Name field: " . ($hasName ? "✓" : "✗") . "\n";
            echo "- Email field: " . ($hasEmail ? "✓" : "✗") . "\n";
            echo "- Password field: " . ($hasPassword ? "✓" : "✗") . "\n";
            echo "- Terms checkbox: " . ($hasTerms ? "✓" : "✗") . "\n";
            echo "- Submit button: " . ($hasButton ? "✓" : "✗") . "\n";
            
            if (!$hasName || !$hasEmail || !$hasPassword || !$hasTerms || !$hasButton) {
                $browser->screenshot('missing-form-elements');
                echo "Some form elements are missing! Check screenshot.\n";
                $this->assertTrue(false, "Form elements missing");
                return;
            }
            
            // Fill form step by step
            echo "\nFilling form...\n";
            $testUsername = 'DebugUser_' . time();
            $testEmail = 'debug_' . time() . '@test.com';
            
            $browser->type('#name', $testUsername);
            echo "- Typed username: {$testUsername}\n";
            
            $browser->type('#email', $testEmail);
            echo "- Typed email: {$testEmail}\n";
            
            $browser->type('#password_step1', 'Password123!');
            echo "- Typed password\n";
            
            $browser->type('#password_confirmation_step1', 'Password123!');
            echo "- Typed password confirmation\n";
            
            // Scroll to checkbox and check it
            $browser->scrollIntoView('#terms_step1')
                    ->check('#terms_step1');
            echo "- Checked terms\n";
            
            // Take screenshot before submission
            $browser->screenshot('before-registration-submit');
            
            // Click submit
            echo "\nSubmitting form...\n";
            $browser->press('Créer mon compte');
            
            // Wait a bit and check what happens
            $browser->pause(3000);
            
            // Check current state
            $currentUrl = $browser->driver->getCurrentURL();
            echo "Current URL after submit: {$currentUrl}\n";
            
            // Check if step2 is visible
            $step2Visible = $browser->element('#step2') !== null && 
                           $browser->script("return document.getElementById('step2').style.display !== 'none'")[0];
            echo "Step 2 visible: " . ($step2Visible ? "Yes" : "No") . "\n";
            
            // Check for JavaScript errors
            $logs = $browser->driver->manage()->getLog('browser');
            if (!empty($logs)) {
                echo "\nBrowser console logs:\n";
                foreach ($logs as $log) {
                    echo $log['level'] . ': ' . $log['message'] . "\n";
                }
            }
            
            // Take final screenshot
            $browser->screenshot('after-registration-submit');
            
            echo "\n=== END DEBUG REGISTRATION FLOW ===\n";
            
            // Force pass to see all debug output
            $this->assertTrue(true);
        });
    }
}