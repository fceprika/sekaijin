<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Traits\DuskCommonSetup;

class RegistrationTest extends DuskTestCase
{
    use DatabaseTruncation, DuskCommonSetup;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupCommonTestData();
    }

    /**
     * Test simple d'inscription d'un nouvel utilisateur.
     */
    public function test_user_can_register(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/inscription');
            $this->waitForPageLoad($browser);
            $browser->assertSee('Rejoignez Sekaijin');
            $this->waitForElement($browser, '#name');
            $browser->type('#name', $this->generateTestUsername())
                ->type('#email', $this->generateTestEmail())
                ->type('#password_step1', 'Password123!')
                ->type('#password_confirmation_step1', 'Password123!');
            $this->waitForElement($browser, '#terms_step1');
            $browser->check('#terms_step1');
            $this->waitForElement($browser, '#create-account-btn');
            $browser->press('Créer mon compte');
            $this->waitForElement($browser, '#step2', 10);
            $browser->assertSee('Compte créé avec succès !'); // Test simplifié
        });
    }

    /**
     * Test d'inscription avec email déjà utilisé.
     */
    public function test_cannot_register_with_existing_email(): void
    {
        // Créer un utilisateur existant
        $existingUser = \App\Models\User::factory()->create([
            'email' => 'existing@example.com',
            'country_residence' => 'Thaïlande',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/inscription');
            $this->waitForPageLoad($browser);
            $this->waitForElement($browser, '#name');
            $browser->type('#name', 'NewUser')
                ->type('#email', 'existing@example.com')
                ->type('#password_step1', 'Password123!')
                ->type('#password_confirmation_step1', 'Password123!');
            $this->waitForElement($browser, '#terms_step1');
            $browser->check('#terms_step1')
                ->press('Créer mon compte')
                ->pause(2000) // Wait for form processing
                ->assertPathIs('/inscription'); // Reste sur la page d'inscription avec erreurs
        });
    }

    /**
     * Test de validation du mot de passe.
     */
    public function test_password_validation_works(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/inscription')
                ->waitFor('#name', 5)
                ->type('#name', 'TestUser')
                ->type('#email', 'test@example.com')
                ->type('#password_step1', 'weak')
                ->type('#password_confirmation_step1', 'weak')
                ->waitFor('#terms_step1', 2)
                ->check('#terms_step1')
                ->press('Créer mon compte')
                ->pause(2000) // Wait for form processing
                ->assertPathIs('/inscription'); // Reste sur la page avec erreurs de validation
        });
    }

    /**
     * Test que les conditions d'utilisation doivent être acceptées.
     */
    public function test_must_accept_terms(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/inscription')
                ->waitFor('#name', 5)
                ->type('#name', 'TestUser')
                ->type('#email', 'test@example.com')
                ->type('#password_step1', 'Password123!')
                ->type('#password_confirmation_step1', 'Password123!')
                    // Ne pas cocher les conditions
                ->press('Créer mon compte')
                ->pause(2000) // Wait for form processing
                ->assertPathIs('/inscription'); // Reste sur la page avec erreur terms
        });
    }
}
