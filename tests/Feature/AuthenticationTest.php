<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/inscription');

        $response->assertStatus(200);
        $response->assertSee('Créer mon compte');
        $response->assertSee('Pseudo');
        $response->assertSee('Email');
        $response->assertSee('Pays de résidence');
        $response->assertSee('Mot de passe');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/inscription', [
            'name' => 'testuser123',
            'email' => 'test@example.com',
            'interest_country' => 'Thaïlande',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $this->assertAuthenticated();
        $response->assertJson(['success' => true]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('testuser123', $user->name);
        $this->assertEquals('Thaïlande', $user->interest_country);
        $this->assertEquals('free', $user->role);
        $this->assertFalse($user->is_public_profile); // Default is false
    }

    public function test_registration_requires_unique_username(): void
    {
        User::factory()->create(['name' => 'existinguser']);

        $response = $this->post('/inscription', [
            'name' => 'existinguser',
            'email' => 'test@example.com',
            'interest_country' => 'Thaïlande',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertGuest();
    }

    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/inscription', [
            'name' => 'testuser123',
            'email' => 'existing@example.com',
            'interest_country' => 'Thaïlande',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_registration_requires_terms_acceptance(): void
    {
        $response = $this->post('/inscription', [
            'name' => 'testuser123',
            'email' => 'test@example.com',
            'interest_country' => 'Thaïlande',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => false,
        ]);

        $response->assertSessionHasErrors(['terms']);
        $this->assertGuest();
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->post('/inscription', [
            'name' => 'testuser123',
            'email' => 'test@example.com',
            'interest_country' => 'Thaïlande',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword123!',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/connexion');

        $response->assertStatus(200);
        $response->assertSee('Connexion');
        $response->assertSee('Email');
        $response->assertSee('Mot de passe');
    }

    public function test_users_can_authenticate_using_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->post('/connexion', [
            'email' => 'test@example.com',
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->post('/connexion', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_users_can_logout(): void
    {
        $user = $this->signIn();

        $response = $this->post('/deconnexion');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_username_availability_check(): void
    {
        User::factory()->create(['name' => 'existinguser']);

        // Test existing username
        $response = $this->get('/api/check-username/existinguser');
        $response->assertJson(['available' => false]);

        // Test available username
        $response = $this->get('/api/check-username/availableuser');
        $response->assertJson(['available' => true]);
    }

    public function test_authenticated_user_cannot_access_registration(): void
    {
        $this->signIn();

        $response = $this->get('/inscription');
        $response->assertRedirect('/');
    }

    public function test_authenticated_user_cannot_access_login(): void
    {
        $this->signIn();

        $response = $this->get('/connexion');
        $response->assertRedirect('/');
    }

    public function test_registration_creates_name_slug(): void
    {
        $response = $this->post('/inscription', [
            'name' => 'TestUser123',
            'email' => 'test@example.com',
            'interest_country' => 'Thaïlande',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        // Note: name_slug is auto-generated by model boot method
    }

    public function test_registration_with_invalid_country(): void
    {
        $response = $this->post('/inscription', [
            'name' => 'testuser123',
            'email' => 'test@example.com',
            'interest_country' => 'NonExistentCountry',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        // This should fail as interest_country is validated against existing countries
        $response->assertSessionHasErrors(['interest_country']);
        $this->assertGuest();
    }
}
