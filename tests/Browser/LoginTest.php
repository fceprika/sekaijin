<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Traits\DuskCommonSetup;

class LoginTest extends DuskTestCase
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
     * Test simple de connexion utilisateur.
     */
    public function test_user_can_login(): void
    {
        // Créer un utilisateur test
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('Password123!'),
            'country_residence' => 'Thaïlande',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/connexion');
            $this->waitForPageLoad($browser);
            $browser->assertSee('Se connecter')
                ->type('email', 'test@example.com')
                ->type('password', 'Password123!')
                ->press('Se connecter');
            $this->waitForLocationCI($browser, '/');
            $browser->assertPathIs('/')
                ->assertAuthenticated()
                ->assertSee($user->name);
        });
    }

    /**
     * Test de connexion avec mauvais mot de passe.
     */
    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('Password123!'),
            'country_residence' => 'Thaïlande',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->logout() // S'assurer qu'on n'est pas connecté
                ->visit('/connexion');
            $this->waitForPageLoad($browser);
            $browser->assertSee('Bon retour !') // Vérifier qu'on est sur la page de connexion
                ->type('email', 'test@example.com')
                ->type('password', 'WrongPassword')
                ->press('Se connecter');
            $this->waitForTextCI($browser, 'Les identifiants fournis ne correspondent pas');
            $browser->assertPathIs('/connexion') // Doit rester sur la page de connexion
                ->assertPresent('.bg-red-50, .text-red-700, [class*="error"]'); // Check for error styling instead of exact text
        });
    }

    /**
     * Test que le menu utilisateur est présent quand connecté.
     */
    public function test_user_menu_is_present_when_logged_in(): void
    {
        $user = User::factory()->create([
            'country_residence' => 'Thaïlande',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->assertAuthenticated()
                ->assertSee($user->name) // Le nom d'utilisateur est affiché
                ->assertPresent('#user-menu-btn'); // Le bouton du menu utilisateur est présent
        });
    }

    /**
     * Test du lien "mot de passe oublié".
     */
    public function test_forgot_password_link_exists(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/connexion')
                ->assertSeeLink('Mot de passe oublié ?');
            // Note: Le lien pointe vers # pour l'instant
        });
    }
}
