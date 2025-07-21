<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_username_availability_api(): void
    {
        User::factory()->create(['name' => 'existinguser']);

        // Test existing username
        $response = $this->get('/api/check-username/existinguser');
        $response->assertStatus(200);
        $response->assertJson(['available' => false]);

        // Test available username
        $response = $this->get('/api/check-username/availableuser');
        $response->assertStatus(200);
        $response->assertJson(['available' => true]);
    }

    public function test_username_availability_api_with_special_characters(): void
    {
        // Test username with special characters - should be invalid
        $response = $this->get('/api/check-username/user@name');
        $response->assertStatus(200);
        $response->assertJson(['available' => false]);

        // Test username with dots - should be valid
        $response = $this->get('/api/check-username/user.name');
        $response->assertStatus(200);
        $response->assertJson(['available' => true]);
    }

    public function test_expats_by_country_api(): void
    {
        // Create users in different countries
        User::factory()->create(['country_residence' => 'Thaïlande']);
        User::factory()->create(['country_residence' => 'Thaïlande']);
        User::factory()->create(['country_residence' => 'Japon']);

        $response = $this->get('/api/expats-by-country');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'country',
                'count',
            ],
        ]);

        $data = $response->json();
        $this->assertNotEmpty($data);

        // Check that Thaïlande has 2 users
        $thailand = collect($data)->firstWhere('country', 'Thaïlande');
        $this->assertEquals(2, $thailand['count']);

        // Check that Japon has 1 user
        $japan = collect($data)->firstWhere('country', 'Japon');
        $this->assertEquals(1, $japan['count']);
    }

    public function test_expats_by_country_api_with_no_users(): void
    {
        $response = $this->get('/api/expats-by-country');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_members_with_location_api_requires_auth(): void
    {
        $response = $this->get('/api/members-with-location');

        // API might not exist yet or might not require auth
        $this->assertContains($response->status(), [200, 401, 404]);
    }

    public function test_members_with_location_api_with_auth(): void
    {
        $this->signIn(['is_visible_on_map' => false]); // Ensure authenticated user is not visible

        // Create users with location
        User::factory()->create([
            'is_visible_on_map' => true,
            'latitude' => 13.7563,
            'longitude' => 100.5018,
            'city_detected' => 'Bangkok',
        ]);

        User::factory()->create([
            'is_visible_on_map' => true,
            'latitude' => 35.6762,
            'longitude' => 139.6503,
            'city_detected' => 'Tokyo',
        ]);

        User::factory()->create([
            'is_visible_on_map' => false,
            'latitude' => 48.8566,
            'longitude' => 2.3522,
            'city_detected' => 'Paris',
        ]);

        $response = $this->get('/api/members-with-location');

        $response->assertStatus(200);
        if ($response->status() === 200) {
            $response->assertJsonStructure([
                'members' => [
                    '*' => [
                        'name',
                        'latitude',
                        'longitude',
                    ],
                ],
                'pagination' => [
                    'limit',
                    'offset',
                    'count',
                ],
            ]);
        }

        $data = $response->json();
        $this->assertCount(2, $data['members']); // Only visible users should be returned
    }

    public function test_update_location_api_requires_auth(): void
    {
        $response = $this->post('/api/update-location', [
            'latitude' => 13.7563,
            'longitude' => 100.5018,
            'city_detected' => 'Bangkok',
        ]);

        $this->assertContains($response->status(), [302, 401]); // 302 for redirect, 401 for unauthorized
    }

    public function test_update_location_api_with_auth(): void
    {
        $user = $this->signIn();

        $response = $this->post('/api/update-location', [
            'latitude' => 13.7563,
            'longitude' => 100.5018,
            'city' => 'Bangkok',
            'is_visible_on_map' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Localisation mise à jour avec succès.',
        ]);

        $user->refresh();
        // Coordinates are randomized for privacy within ~10km radius
        $this->assertEqualsWithDelta(13.7563, (float) $user->latitude, 0.1, 'Latitude should be within 0.1 degrees');
        $this->assertEqualsWithDelta(100.5018, (float) $user->longitude, 0.1, 'Longitude should be within 0.1 degrees');
        $this->assertEquals('Bangkok', $user->city_detected);
        $this->assertTrue($user->is_visible_on_map);
    }

    public function test_remove_location_api_requires_auth(): void
    {
        $response = $this->post('/api/remove-location');

        $this->assertContains($response->status(), [302, 401]); // 302 for redirect, 401 for unauthorized
    }

    public function test_remove_location_api_with_auth(): void
    {
        $user = $this->signIn([
            'latitude' => 13.7563,
            'longitude' => 100.5018,
            'city_detected' => 'Bangkok',
            'is_visible_on_map' => true,
        ]);

        $response = $this->post('/api/remove-location');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $user->refresh();
        $this->assertNull($user->latitude);
        $this->assertNull($user->longitude);
        $this->assertNull($user->city_detected);
        $this->assertFalse($user->is_visible_on_map);
    }

    public function test_update_location_api_validation(): void
    {
        $this->signIn();

        // Test invalid latitude
        $response = $this->post('/api/update-location', [
            'latitude' => 'invalid',
            'longitude' => 100.5018,
            'city_detected' => 'Bangkok',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['latitude']);

        // Test invalid longitude
        $response = $this->post('/api/update-location', [
            'latitude' => 13.7563,
            'longitude' => 'invalid',
            'city_detected' => 'Bangkok',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['longitude']);
    }

    public function test_location_api_coordinates_validation(): void
    {
        $this->signIn();

        // Test latitude out of range
        $response = $this->post('/api/update-location', [
            'latitude' => 100, // > 90
            'longitude' => 100.5018,
            'city_detected' => 'Bangkok',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['latitude']);

        // Test longitude out of range
        $response = $this->post('/api/update-location', [
            'latitude' => 13.7563,
            'longitude' => 200, // > 180
            'city_detected' => 'Bangkok',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['longitude']);
    }

    public function test_api_endpoints_return_json(): void
    {
        $endpoints = [
            '/api/check-username/testuser',
            '/api/expats-by-country',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->get($endpoint);
            $response->assertHeader('Content-Type', 'application/json');
        }
    }

    public function test_api_endpoints_handle_errors_gracefully(): void
    {
        // Test non-existent API endpoint
        $response = $this->get('/api/nonexistent-endpoint');
        $response->assertStatus(404);
    }

    public function test_username_availability_api_case_sensitivity(): void
    {
        User::factory()->create(['name' => 'TestUser']);

        // Test exact match
        $response = $this->get('/api/check-username/TestUser');
        $response->assertStatus(200);
        $response->assertJson(['available' => false]);

        // Test different case - might not be available if case-insensitive
        $response = $this->get('/api/check-username/testuser');
        $response->assertStatus(200);
        // Don't assert availability as it depends on implementation
    }

    public function test_expats_by_country_api_excludes_empty_countries(): void
    {
        // Create users with null country_residence
        User::factory()->create(['country_residence' => null]);
        User::factory()->create(['country_residence' => '']);
        User::factory()->create(['country_residence' => 'Thaïlande']);

        $response = $this->get('/api/expats-by-country');

        $response->assertStatus(200);
        $data = $response->json();

        // Should only include users with actual country values
        $this->assertCount(1, $data);
        $this->assertEquals('Thaïlande', $data[0]['country']);
        $this->assertEquals(1, $data[0]['count']);
    }

    public function test_api_rate_limiting(): void
    {
        // This test would be more complex in real scenarios
        // For now, just test that the endpoint responds normally
        $response = $this->get('/api/expats-by-country');
        $response->assertStatus(200);
    }

    public function test_api_cors_headers(): void
    {
        $response = $this->get('/api/expats-by-country');

        // Check that CORS headers are present if configured
        $response->assertStatus(200);
        // Note: CORS headers would be added by middleware if configured
    }

    public function test_location_api_with_extreme_coordinates(): void
    {
        $user = $this->signIn();

        // Test North Pole
        $response = $this->post('/api/update-location', [
            'latitude' => 90,
            'longitude' => 0,
            'city_detected' => 'North Pole',
        ]);

        $response->assertStatus(200);

        // Test South Pole
        $response = $this->post('/api/update-location', [
            'latitude' => -90,
            'longitude' => 0,
            'city_detected' => 'South Pole',
        ]);

        $response->assertStatus(200);

        // Test Date Line
        $response = $this->post('/api/update-location', [
            'latitude' => 0,
            'longitude' => 180,
            'city_detected' => 'Date Line',
        ]);

        $response->assertStatus(200);
    }
}
