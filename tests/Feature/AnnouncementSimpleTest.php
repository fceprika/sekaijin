<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementSimpleTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcement_factory_works(): void
    {
        $announcement = Announcement::factory()->create();

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
        $vente = Announcement::factory()->vente()->create();
        $location = Announcement::factory()->location()->create();
        $colocation = Announcement::factory()->colocation()->create();
        $service = Announcement::factory()->service()->create();

        $this->assertEquals('vente', $vente->type);
        $this->assertEquals('location', $location->type);
        $this->assertEquals('colocation', $colocation->type);
        $this->assertEquals('service', $service->type);
    }

    public function test_announcement_statuses(): void
    {
        $pending = Announcement::factory()->pending()->create();
        $active = Announcement::factory()->active()->create();
        $refused = Announcement::factory()->refused()->create();

        $this->assertEquals('pending', $pending->status);
        $this->assertEquals('active', $active->status);
        $this->assertEquals('refused', $refused->status);
    }

    public function test_announcement_pricing(): void
    {
        $paidAnnouncement = Announcement::factory()->create([
            'price' => 150.75,
            'currency' => 'USD',
        ]);

        $freeAnnouncement = Announcement::factory()->create([
            'price' => null,
            'currency' => 'EUR',
        ]);

        $this->assertEquals(150.75, $paidAnnouncement->price);
        $this->assertEquals('USD', $paidAnnouncement->currency);
        $this->assertNull($freeAnnouncement->price);
    }

    public function test_announcement_location(): void
    {
        $announcement = Announcement::factory()->create([
            'country' => 'Thaïlande',
            'city' => 'Bangkok',
            'address' => '123 Test Street',
        ]);

        $this->assertEquals('Thaïlande', $announcement->country);
        $this->assertEquals('Bangkok', $announcement->city);
        $this->assertEquals('123 Test Street', $announcement->address);
    }

    public function test_announcement_images(): void
    {
        $announcement = Announcement::factory()->create([
            'images' => ['image1.jpg', 'image2.jpg', 'image3.jpg'],
        ]);

        $this->assertIsArray($announcement->images);
        $this->assertCount(3, $announcement->images);
        $this->assertContains('image1.jpg', $announcement->images);
    }

    public function test_announcement_expiration(): void
    {
        $futureDate = now()->addDays(30)->format('Y-m-d');
        $announcement = Announcement::factory()->create([
            'expiration_date' => $futureDate,
        ]);

        $this->assertEquals($futureDate, $announcement->expiration_date->format('Y-m-d'));
    }

    public function test_announcement_views_tracking(): void
    {
        $announcement = Announcement::factory()->create(['views' => 50]);

        $this->assertEquals(50, $announcement->views);
    }

    public function test_announcement_slug_generation(): void
    {
        $announcement = Announcement::factory()->create([
            'title' => 'Mon Appartement à Bangkok',
        ]);

        $this->assertNotNull($announcement->slug);
        $this->assertIsString($announcement->slug);
    }

    public function test_announcement_user_relationship(): void
    {
        $user = User::factory()->create(['name' => 'Test User']);
        $announcement = Announcement::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $announcement->user_id);
        $this->assertEquals('Test User', $announcement->user->name);
    }

    public function test_announcement_moderation_workflow(): void
    {
        $announcement = Announcement::factory()->pending()->create();

        // Initially pending
        $this->assertEquals('pending', $announcement->status);
        $this->assertNull($announcement->refusal_reason);

        // Approve announcement
        $announcement->update(['status' => 'active']);
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
            $announcement = Announcement::factory()->create([
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

        // Check that pricing is set appropriately (Laravel returns decimals as strings)
        $this->assertIsNumeric($vente->price);
        $this->assertIsNumeric($location->price);
        $this->assertIsNumeric($colocation->price);
        // Service can be free or paid
    }

    public function test_announcement_creation_requires_authentication(): void
    {
        $response = $this->get('/annonces/create');

        $this->assertRedirectToLogin($response);
    }

    public function test_announcement_listing_page(): void
    {
        $activeAnnouncement = Announcement::factory()->active()->create();
        $pendingAnnouncement = Announcement::factory()->pending()->create();

        $response = $this->get('/annonces');

        $response->assertStatus(200);
        $response->assertSee($activeAnnouncement->title);
        $response->assertDontSee($pendingAnnouncement->title);
    }

    public function test_announcement_country_filtering(): void
    {
        $thailandAnnouncement = Announcement::factory()->active()->create(['country' => 'Thaïlande']);
        $japanAnnouncement = Announcement::factory()->active()->create(['country' => 'Japon']);

        // Country-specific announcements
        $response = $this->get('/thailande/annonces');
        $response->assertStatus(200);
        $response->assertSee($thailandAnnouncement->title);
        $response->assertDontSee($japanAnnouncement->title);
    }

    public function test_announcement_search_functionality(): void
    {
        $searchableAnnouncement = Announcement::factory()->active()->create([
            'title' => 'Beautiful Apartment in Bangkok',
        ]);
        $otherAnnouncement = Announcement::factory()->active()->create([
            'title' => 'Motorbike for Sale',
        ]);

        $response = $this->get('/annonces?search=apartment');

        $response->assertStatus(200);
        $response->assertSee($searchableAnnouncement->title);
        $response->assertDontSee($otherAnnouncement->title);
    }

    public function test_announcement_type_filtering(): void
    {
        $venteAnnouncement = Announcement::factory()->active()->create(['type' => 'vente']);
        $locationAnnouncement = Announcement::factory()->active()->create(['type' => 'location']);

        $response = $this->get('/annonces?type=vente');

        $response->assertStatus(200);
        $response->assertSee($venteAnnouncement->title);
        $response->assertDontSee($locationAnnouncement->title);
    }

    public function test_announcement_price_filtering(): void
    {
        $cheapAnnouncement = Announcement::factory()->active()->create([
            'price' => 50,
            'title' => 'Cheap Item for Sale',
        ]);
        $expensiveAnnouncement = Announcement::factory()->active()->create([
            'price' => 500,
            'title' => 'Expensive Item for Sale',
        ]);

        $response = $this->get('/annonces?price_max=100');

        $response->assertStatus(200);
        $response->assertSee($cheapAnnouncement->title);
        $response->assertDontSee($expensiveAnnouncement->title);
    }

    public function test_announcement_with_images(): void
    {
        $announcement = Announcement::factory()->create([
            'images' => ['test1.jpg', 'test2.jpg'],
        ]);

        $this->assertCount(2, $announcement->images);
        $this->assertEquals('test1.jpg', $announcement->images[0]);
        $this->assertEquals('test2.jpg', $announcement->images[1]);
    }

    public function test_announcement_without_images(): void
    {
        $announcement = Announcement::factory()->create([
            'images' => null,
        ]);

        $this->assertNull($announcement->images);
    }

    public function test_announcement_required_fields(): void
    {
        $announcement = Announcement::factory()->create();

        $this->assertNotNull($announcement->title);
        $this->assertNotNull($announcement->description);
        $this->assertNotNull($announcement->type);
        $this->assertNotNull($announcement->country);
        $this->assertNotNull($announcement->city);
        $this->assertNotNull($announcement->status);
        $this->assertNotNull($announcement->user_id);
    }
}
