<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcement_creation_requires_authentication(): void
    {
        $response = $this->get('/annonces/create');

        $this->assertRedirectToLogin($response);
    }

    public function test_authenticated_user_can_create_announcement(): void
    {
        Storage::fake('public');

        $user = $this->signIn();

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post('/annonces', [
            'title' => 'Test Announcement',
            'description' => 'This is a test announcement description.',
            'type' => 'vente',
            'price' => 100.50,
            'currency' => 'EUR',
            'country' => 'Thaïlande',
            'city' => 'Bangkok',
            'contact_email' => 'test@example.com',
            'images' => [$file],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('announcements', [
            'title' => 'Test Announcement',
            'user_id' => $user->id,
            'type' => 'vente',
            'status' => 'pending',
        ]);
    }

    public function test_announcement_factory_works(): void
    {
        $announcement = $this->createAnnouncement();

        $this->assertNotNull($announcement->title);
        $this->assertNotNull($announcement->description);
        $this->assertNotNull($announcement->type);
        $this->assertNotNull($announcement->country);
        $this->assertNotNull($announcement->city);
        $this->assertNotNull($announcement->user);
        $this->assertContains($announcement->status, ['pending', 'active', 'refused']);
    }

    public function test_announcement_types(): void
    {
        $vente = $this->createAnnouncement(['type' => 'vente']);
        $location = $this->createAnnouncement(['type' => 'location']);
        $colocation = $this->createAnnouncement(['type' => 'colocation']);
        $service = $this->createAnnouncement(['type' => 'service']);

        $this->assertEquals('vente', $vente->type);
        $this->assertEquals('location', $location->type);
        $this->assertEquals('colocation', $colocation->type);
        $this->assertEquals('service', $service->type);
    }

    public function test_announcement_statuses(): void
    {
        $pending = $this->createPendingAnnouncement();
        $active = $this->createActiveAnnouncement();
        $refused = Announcement::factory()->refused()->create();

        $this->assertEquals('pending', $pending->status);
        $this->assertEquals('active', $active->status);
        $this->assertEquals('refused', $refused->status);
    }

    public function test_announcement_pricing(): void
    {
        $paidAnnouncement = $this->createAnnouncement([
            'price' => 150.75,
            'currency' => 'USD',
        ]);

        $freeAnnouncement = $this->createAnnouncement([
            'price' => null,
            'currency' => 'EUR',
        ]);

        $this->assertEquals(150.75, $paidAnnouncement->price);
        $this->assertEquals('USD', $paidAnnouncement->currency);
        $this->assertNull($freeAnnouncement->price);
        $this->assertEquals('EUR', $freeAnnouncement->currency);
    }

    public function test_announcement_location(): void
    {
        $announcement = $this->createAnnouncement([
            'country' => 'Thaïlande',
            'city' => 'Bangkok',
            'address' => '123 Test Street',
        ]);

        $this->assertEquals('Thaïlande', $announcement->country);
        $this->assertEquals('Bangkok', $announcement->city);
        $this->assertEquals('123 Test Street', $announcement->address);
    }

    public function test_announcement_contact_info(): void
    {
        $announcement = $this->createAnnouncement([
            'title' => 'Test Announcement with Contact',
            'description' => 'Test description with contact info',
        ]);

        $this->assertEquals('Test Announcement with Contact', $announcement->title);
        $this->assertEquals('Test description with contact info', $announcement->description);
    }

    public function test_announcement_images(): void
    {
        $announcement = $this->createAnnouncement([
            'images' => ['image1.jpg', 'image2.jpg', 'image3.jpg'],
        ]);

        $this->assertIsArray($announcement->images);
        $this->assertCount(3, $announcement->images);
        $this->assertContains('image1.jpg', $announcement->images);
    }

    public function test_announcement_expiration(): void
    {
        $futureDate = now()->addDays(30);
        $announcement = $this->createAnnouncement([
            'expiration_date' => $futureDate,
        ]);

        $this->assertEquals($futureDate->format('Y-m-d'), $announcement->expiration_date->format('Y-m-d'));
    }

    public function test_announcement_views_tracking(): void
    {
        $announcement = $this->createActiveAnnouncement(['views' => 50]);

        $this->assertEquals(50, $announcement->views);
    }

    public function test_announcement_slug_generation(): void
    {
        $announcement = $this->createAnnouncement([
            'title' => 'Mon Appartement à Bangkok',
        ]);

        $this->assertNotNull($announcement->slug);
        $this->assertIsString($announcement->slug);
    }

    public function test_announcement_user_relationship(): void
    {
        $user = User::factory()->create(['name' => 'Test User']);
        $announcement = $this->createAnnouncement(['user_id' => $user->id]);

        $this->assertEquals($user->id, $announcement->user_id);
        $this->assertEquals('Test User', $announcement->user->name);
    }

    public function test_announcement_moderation_workflow(): void
    {
        $announcement = $this->createPendingAnnouncement();

        // Initially pending
        $this->assertEquals('pending', $announcement->status);
        $this->assertNull($announcement->refusal_reason);

        // Approve announcement
        $announcement->update([
            'status' => 'active',
        ]);

        $this->assertEquals('active', $announcement->status);

        // Refuse announcement
        $announcement->update([
            'status' => 'refused',
            'refusal_reason' => 'Content not appropriate',
        ]);

        $this->assertEquals('refused', $announcement->status);
        $this->assertEquals('Content not appropriate', $announcement->refusal_reason);
    }

    public function test_announcement_currencies(): void
    {
        $currencies = ['EUR', 'USD', 'THB', 'JPY', 'GBP', 'CHF'];

        foreach ($currencies as $currency) {
            $announcement = $this->createAnnouncement([
                'price' => 100,
                'currency' => $currency,
            ]);

            $this->assertEquals($currency, $announcement->currency);
        }
    }

    public function test_announcement_factory_states(): void
    {
        $vente = Announcement::factory()->vente()->create();
        $location = Announcement::factory()->location()->create();
        $colocation = Announcement::factory()->colocation()->create();
        $service = Announcement::factory()->service()->create();

        $this->assertEquals('vente', $vente->type);
        $this->assertEquals('location', $location->type);
        $this->assertEquals('colocation', $colocation->type);
        $this->assertEquals('service', $service->type);
    }

    public function test_announcement_listing_page(): void
    {
        $activeAnnouncement = $this->createActiveAnnouncement();
        $pendingAnnouncement = $this->createPendingAnnouncement();

        $response = $this->get('/annonces');

        $response->assertStatus(200);
        $response->assertSee($activeAnnouncement->title);
        $response->assertDontSee($pendingAnnouncement->title);
    }

    public function test_announcement_country_filtering(): void
    {
        $thailandAnnouncement = $this->createActiveAnnouncement(['country' => 'Thaïlande']);
        $japanAnnouncement = $this->createActiveAnnouncement(['country' => 'Japon']);

        // Global announcements page
        $response = $this->get('/annonces');
        $response->assertStatus(200);
        $response->assertSee($thailandAnnouncement->title);
        $response->assertSee($japanAnnouncement->title);

        // Country-specific announcements
        $response = $this->get('/thailande/annonces');
        $response->assertStatus(200);
        $response->assertSee($thailandAnnouncement->title);
        $response->assertDontSee($japanAnnouncement->title);
    }

    public function test_announcement_type_filtering(): void
    {
        $venteAnnouncement = $this->createActiveAnnouncement(['type' => 'vente']);
        $locationAnnouncement = $this->createActiveAnnouncement(['type' => 'location']);

        $response = $this->get('/annonces?type=vente');

        $response->assertStatus(200);
        $response->assertSee($venteAnnouncement->title);
        $response->assertDontSee($locationAnnouncement->title);
    }

    public function test_user_can_view_their_announcements(): void
    {
        $user = $this->signIn();
        $userAnnouncement = $this->createAnnouncement(['user_id' => $user->id]);
        $otherAnnouncement = $this->createAnnouncement();

        $response = $this->get('/annonces/mes-annonces');

        $response->assertStatus(200);
        $response->assertSee($userAnnouncement->title);
        $response->assertDontSee($otherAnnouncement->title);
    }

    public function test_my_announcements_requires_authentication(): void
    {
        $response = $this->get('/annonces/mes-annonces');

        $this->assertRedirectToLogin($response);
    }

    public function test_announcement_search_functionality(): void
    {
        $searchableAnnouncement = $this->createActiveAnnouncement([
            'title' => 'Beautiful Apartment in Bangkok',
        ]);
        $otherAnnouncement = $this->createActiveAnnouncement([
            'title' => 'Motorbike for Sale',
        ]);

        $response = $this->get('/annonces?search=apartment');

        $response->assertStatus(200);
        $response->assertSee($searchableAnnouncement->title);
        $response->assertDontSee($otherAnnouncement->title);
    }

    public function test_announcement_price_filtering(): void
    {
        $cheapAnnouncement = $this->createActiveAnnouncement(['price' => 50]);
        $expensiveAnnouncement = $this->createActiveAnnouncement(['price' => 500]);

        $response = $this->get('/annonces?price_max=100');

        $response->assertStatus(200);
        $response->assertSee($cheapAnnouncement->title);
        $response->assertDontSee($expensiveAnnouncement->title);
    }
}
