<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer les pays nécessaires
        \App\Models\Country::firstOrCreate(
            ['slug' => 'thailande'],
            [
                'name_fr' => 'Thaïlande',
                'emoji' => '🇹🇭',
                'description' => 'Description pour Thaïlande',
            ]
        );
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
            $browser->visit('/connexion')
                    ->assertSee('Se connecter')
                    ->type('email', 'test@example.com')
                    ->type('password', 'Password123!')
                    ->press('Se connecter')
                    ->waitForLocation('/')
                    ->assertPathIs('/')
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
                    ->visit('/connexion')
                    ->assertSee('Bon retour !') // Vérifier qu'on est sur la page de connexion
                    ->type('email', 'test@example.com')
                    ->type('password', 'WrongPassword')
                    ->press('Se connecter')
                    ->pause(2000) // Attendre le traitement
                    ->assertPathIs('/connexion') // Doit rester sur la page de connexion
                    ->assertSee('Les identifiants fournis ne correspondent pas à nos enregistrements.');
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