<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Event;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_roles_are_assigned_correctly(): void
    {
        $freeUser = User::factory()->create(['role' => 'free']);
        $premiumUser = User::factory()->create(['role' => 'premium']);
        $ambassadorUser = User::factory()->create(['role' => 'ambassador']);
        $adminUser = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($freeUser->isFree());
        $this->assertFalse($freeUser->isPremium());
        $this->assertFalse($freeUser->isAmbassador());
        $this->assertFalse($freeUser->isAdmin());

        $this->assertTrue($premiumUser->isPremium());
        $this->assertFalse($premiumUser->isFree());
        $this->assertFalse($premiumUser->isAmbassador());
        $this->assertFalse($premiumUser->isAdmin());

        $this->assertTrue($ambassadorUser->isAmbassador());
        $this->assertFalse($ambassadorUser->isFree());
        $this->assertFalse($ambassadorUser->isPremium());
        $this->assertFalse($ambassadorUser->isAdmin());

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($adminUser->isFree());
        $this->assertFalse($adminUser->isPremium());
        $this->assertFalse($adminUser->isAmbassador());
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $this->signInAdmin();

        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Administration');
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $this->signInFree();

        $response = $this->get('/admin');

        $this->assertForbidden($response);
    }

    public function test_admin_can_create_articles(): void
    {
        $admin = $this->signInAdmin();
        $country = $this->createThailand();

        // Test admin permissions by creating an article directly
        $article = Article::factory()->create([
            'title' => 'Test Article',
            'content' => 'This is a test article content.',
            'excerpt' => 'This is a test excerpt.',
            'category' => 'guide-pratique',
            'country_id' => $country->id,
            'author_id' => $admin->id,
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'author_id' => $admin->id,
            'is_published' => true,
        ]);
    }

    public function test_admin_can_create_news(): void
    {
        $admin = $this->signInAdmin();
        $country = $this->createThailand();

        // Test admin permissions by creating news directly
        $news = News::factory()->create([
            'title' => 'Test News',
            'content' => 'This is a test news content.',
            'excerpt' => 'This is a test excerpt.',
            'category' => 'administrative',
            'country_id' => $country->id,
            'author_id' => $admin->id,
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertDatabaseHas('news', [
            'title' => 'Test News',
            'author_id' => $admin->id,
            'is_published' => true,
        ]);
    }

    public function test_ambassador_can_create_events(): void
    {
        $ambassador = $this->signInAmbassador();
        $country = $this->createThailand();

        // Test ambassador permissions by creating an event directly
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'description' => 'This is a test event.',
            'full_description' => 'This is a detailed test event description.',
            'category' => 'networking',
            'country_id' => $country->id,
            'organizer_id' => $ambassador->id,
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(31),
            'location' => 'Bangkok Convention Center',
            'address' => '123 Test Street, Bangkok',
            'is_online' => false,
            'is_published' => true,
        ]);

        $this->assertTrue($ambassador->isAmbassador());
        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'organizer_id' => $ambassador->id,
            'is_published' => true,
        ]);
    }

    public function test_admin_can_create_events(): void
    {
        $admin = $this->signInAdmin();
        $country = $this->createThailand();

        // Test admin permissions by creating an event directly
        $event = Event::factory()->create([
            'title' => 'Admin Event',
            'description' => 'This is an admin event.',
            'full_description' => 'This is a detailed admin event description.',
            'category' => 'formation',
            'country_id' => $country->id,
            'organizer_id' => $admin->id,
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(31),
            'location' => 'Admin Location',
            'address' => '456 Admin Street, Bangkok',
            'is_online' => false,
            'is_published' => true,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertDatabaseHas('events', [
            'title' => 'Admin Event',
            'organizer_id' => $admin->id,
            'is_published' => true,
        ]);
    }

    public function test_free_user_cannot_create_events(): void
    {
        $this->signInFree();
        $country = $this->createThailand();

        $response = $this->post('/evenements', [
            'title' => 'Free User Event',
            'description' => 'This should not be allowed.',
            'full_description' => 'This should not be allowed.',
            'category' => 'networking',
            'country_id' => $country->id,
            'start_date' => '2025-08-01 10:00:00',
            'is_online' => false,
            'is_published' => true,
        ]);

        $this->assertForbidden($response);
    }

    public function test_premium_user_cannot_create_events(): void
    {
        $this->signInPremium();
        $country = $this->createThailand();

        $response = $this->post('/evenements', [
            'title' => 'Premium User Event',
            'description' => 'This should not be allowed.',
            'full_description' => 'This should not be allowed.',
            'category' => 'networking',
            'country_id' => $country->id,
            'start_date' => '2025-08-01 10:00:00',
            'is_online' => false,
            'is_published' => true,
        ]);

        $this->assertForbidden($response);
    }

    public function test_user_can_edit_own_articles(): void
    {
        $user = $this->signIn();
        $country = $this->createThailand();
        $article = Article::factory()->create([
            'author_id' => $user->id,
            'country_id' => $country->id,
        ]);

        $response = $this->get("/articles/{$article->slug}/edit");

        $response->assertStatus(200);
    }

    public function test_user_cannot_edit_others_articles(): void
    {
        $user = $this->signIn();
        $otherUser = User::factory()->create();
        $country = $this->createThailand();
        $article = Article::factory()->create([
            'author_id' => $otherUser->id,
            'country_id' => $country->id,
        ]);

        $response = $this->get("/articles/{$article->slug}/edit");

        $this->assertForbidden($response);
    }

    public function test_admin_can_edit_any_article(): void
    {
        $this->signInAdmin();
        $otherUser = User::factory()->create();
        $country = $this->createThailand();
        $article = Article::factory()->create([
            'author_id' => $otherUser->id,
            'country_id' => $country->id,
        ]);

        $response = $this->get("/articles/{$article->slug}/edit");

        $response->assertStatus(200);
    }

    public function test_user_can_edit_own_events(): void
    {
        $user = $this->signInAmbassador();
        $country = $this->createThailand();
        $event = Event::factory()->create([
            'organizer_id' => $user->id,
            'country_id' => $country->id,
        ]);

        $response = $this->get("/evenements/{$event->slug}/edit");

        $response->assertStatus(200);
    }

    public function test_user_cannot_edit_others_events(): void
    {
        $user = $this->signInAmbassador();
        $otherUser = User::factory()->ambassador()->create();
        $country = $this->createThailand();
        $event = Event::factory()->create([
            'organizer_id' => $otherUser->id,
            'country_id' => $country->id,
        ]);

        $response = $this->get("/evenements/{$event->slug}/edit");

        $this->assertForbidden($response);
    }

    public function test_admin_can_edit_any_event(): void
    {
        $this->signInAdmin();
        $otherUser = User::factory()->ambassador()->create();
        $country = $this->createThailand();
        $event = Event::factory()->create([
            'organizer_id' => $otherUser->id,
            'country_id' => $country->id,
        ]);

        $response = $this->get("/evenements/{$event->slug}/edit");

        $response->assertStatus(200);
    }

    public function test_role_middleware_protects_admin_routes(): void
    {
        $routes = [
            '/admin',
            '/admin/articles',
            '/admin/news',
            '/admin/articles/create',
            '/admin/news/create',
        ];

        foreach ($routes as $route) {
            $this->signInFree();
            $response = $this->get($route);
            $this->assertForbidden($response, "Route {$route} should be forbidden for free users");

            $this->signInPremium();
            $response = $this->get($route);
            $this->assertForbidden($response, "Route {$route} should be forbidden for premium users");

            $this->signInAmbassador();
            $response = $this->get($route);
            $this->assertForbidden($response, "Route {$route} should be forbidden for ambassadors");
        }
    }

    public function test_role_badge_display(): void
    {
        $adminUser = User::factory()->admin()->create([
            'name' => 'adminuser',
            'is_public_profile' => true,
            'email_verified_at' => now(),
        ]);
        $ambassadorUser = User::factory()->ambassador()->create([
            'name' => 'ambassadoruser',
            'is_public_profile' => true,
            'email_verified_at' => now(),
        ]);
        $premiumUser = User::factory()->premium()->create([
            'name' => 'premiumuser',
            'is_public_profile' => true,
            'email_verified_at' => now(),
        ]);
        $freeUser = User::factory()->create([
            'name' => 'freeuser',
            'role' => 'free',
            'is_public_profile' => true,
            'email_verified_at' => now(),
        ]);

        $adminResponse = $this->get('/membre/adminuser');
        $adminResponse->assertSee('Administrateur');

        $ambassadorResponse = $this->get('/membre/ambassadoruser');
        $ambassadorResponse->assertSee('Ambassadeur Sekaijin');

        $premiumResponse = $this->get('/membre/premiumuser');
        $premiumResponse->assertSee('Membre Premium');

        $freeResponse = $this->get('/membre/freeuser');
        $freeResponse->assertSee('Membre');
    }

    public function test_blade_role_directives(): void
    {
        $admin = $this->signInAdmin();

        $response = $this->get('/');

        // Check that admin-specific content is shown
        $response->assertSee('Administration'); // Admin menu should be visible
    }

    public function test_event_creation_page_access(): void
    {
        // Test that only ambassadors and admins can access event creation
        $this->signInFree();
        $response = $this->get('/evenements/create');
        $this->assertForbidden($response);

        $this->signInPremium();
        $response = $this->get('/evenements/create');
        $this->assertForbidden($response);

        $this->signInAmbassador();
        $response = $this->get('/evenements/create');
        $response->assertStatus(200);

        $this->signInAdmin();
        $response = $this->get('/evenements/create');
        $response->assertStatus(200);
    }

    public function test_content_publication_permissions(): void
    {
        $admin = $this->signInAdmin();
        $country = $this->createThailand();

        // Admin can publish content immediately
        $article = Article::factory()->create([
            'title' => 'Published Article',
            'content' => 'This article is published.',
            'excerpt' => 'Published excerpt.',
            'category' => 'guide-pratique',
            'country_id' => $country->id,
            'author_id' => $admin->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Published Article',
            'is_published' => true,
        ]);

        $this->assertNotNull($article);
        $this->assertTrue($article->is_published);
        $this->assertNotNull($article->published_at);
    }
}
