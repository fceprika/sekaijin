<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileSimpleTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_requires_authentication(): void
    {
        $response = $this->get('/profil');

        $this->assertRedirectToLogin($response);
    }

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = $this->signIn();

        $response = $this->get('/profil');

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_public_profile_can_be_viewed(): void
    {
        $user = User::factory()->create([
            'name' => 'publicuser',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'bio' => 'This is my public bio',
            'is_public_profile' => true,
            'country_residence' => 'France',
            'city_residence' => 'Paris',
            'role' => 'premium',
            'email_verified_at' => now(),
        ]);

        $response = $this->get('/membre/publicuser');

        $response->assertStatus(200);
        $response->assertSee('publicuser'); // Test username instead of first name
        $response->assertSee('This is my public bio');
        $response->assertSee('France');
        $response->assertSee('Paris');
    }

    public function test_private_profile_requires_authentication(): void
    {
        $user = User::factory()->create([
            'name' => 'privateuser',
            'is_public_profile' => false,
        ]);

        $response = $this->get('/membre/privateuser');

        $this->assertRedirectToLogin($response);
    }

    public function test_private_profile_can_be_viewed_by_authenticated_users(): void
    {
        $privateUser = User::factory()->create([
            'name' => 'privateuser',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'bio' => 'This is my private bio',
            'is_public_profile' => false,
            'country_residence' => 'Thaïlande',
        ]);

        $this->signIn();

        $response = $this->get('/membre/privateuser');

        $response->assertStatus(200);
        $response->assertSee('privateuser'); // Test username instead of first name
        $response->assertSee('This is my private bio');
        $response->assertSee('Thaïlande');
    }

    public function test_nonexistent_profile_returns_404(): void
    {
        $response = $this->get('/membre/nonexistentuser');

        $this->assertNotFound($response);
    }

    public function test_profile_link_appears_in_navigation(): void
    {
        $user = $this->signIn([
            'name' => 'testuser',
            'is_public_profile' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('/membre/testuser');
    }

    public function test_user_factory_creates_valid_user(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertContains($user->role, ['free', 'premium', 'ambassador', 'admin']);
    }

    public function test_user_factory_with_specific_role(): void
    {
        $admin = User::factory()->admin()->create();
        $premium = User::factory()->premium()->create();
        $ambassador = User::factory()->ambassador()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($premium->isPremium());
        $this->assertTrue($ambassador->isAmbassador());
    }

    public function test_user_factory_with_location(): void
    {
        $user = User::factory()->withLocation()->create();

        $this->assertTrue($user->is_visible_on_map);
        $this->assertNotNull($user->latitude);
        $this->assertNotNull($user->longitude);
    }

    public function test_user_factory_with_complete_profile(): void
    {
        $user = User::factory()->withCompleteProfile()->create();

        $this->assertNotNull($user->first_name);
        $this->assertNotNull($user->last_name);
        $this->assertNotNull($user->bio);
        $this->assertTrue($user->is_public_profile);
    }

    public function test_user_role_checking_methods(): void
    {
        $freeUser = User::factory()->create(['role' => 'free']);
        $premiumUser = User::factory()->create(['role' => 'premium']);
        $ambassadorUser = User::factory()->create(['role' => 'ambassador']);
        $adminUser = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($freeUser->isFree());
        $this->assertFalse($freeUser->isPremium());

        $this->assertTrue($premiumUser->isPremium());
        $this->assertFalse($premiumUser->isAmbassador());

        $this->assertTrue($ambassadorUser->isAmbassador());
        $this->assertFalse($ambassadorUser->isAdmin());

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($adminUser->isFree());
    }

    public function test_user_has_name_slug(): void
    {
        $user = User::factory()->create(['name' => 'TestUser123']);

        $this->assertNotNull($user->name_slug);
        $this->assertNotEquals($user->name, $user->name_slug);
    }

    public function test_user_countries_relationship(): void
    {
        $thailand = $this->createThailand();

        $user = User::factory()->create([
            'country_residence' => 'Thaïlande',
            'interest_country' => 'Thaïlande',
        ]);

        $this->assertEquals('Thaïlande', $user->country_residence);
        $this->assertEquals('Thaïlande', $user->interest_country);
    }

    public function test_user_social_media_fields(): void
    {
        $user = User::factory()->create([
            'youtube_username' => 'testuser',
            'instagram_username' => 'testuser_insta',
            'linkedin_username' => 'test-user',
        ]);

        $this->assertEquals('testuser', $user->youtube_username);
        $this->assertEquals('testuser_insta', $user->instagram_username);
        $this->assertEquals('test-user', $user->linkedin_username);
    }

    public function test_user_visibility_settings(): void
    {
        $publicUser = User::factory()->create(['is_public_profile' => true]);
        $privateUser = User::factory()->create(['is_public_profile' => false]);

        $this->assertTrue($publicUser->is_public_profile);
        $this->assertFalse($privateUser->is_public_profile);
    }

    public function test_user_verification_status(): void
    {
        $verifiedUser = User::factory()->create(['is_verified' => true]);
        $unverifiedUser = User::factory()->create(['is_verified' => false]);

        $this->assertTrue($verifiedUser->is_verified);
        $this->assertFalse($unverifiedUser->is_verified);
    }
}
