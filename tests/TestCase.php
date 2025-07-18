<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\AssertionsHelper;
use Tests\Traits\AuthenticatesUsers;
use Tests\Traits\CreatesContent;
use Tests\Traits\CreatesCountries;

abstract class TestCase extends BaseTestCase
{
    use AssertionsHelper;
    use AuthenticatesUsers;
    use CreatesApplication;
    use CreatesContent;
    use CreatesCountries;
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Seed the basic countries for testing
        $this->seedBasicCountries();
    }

    /**
     * Seed basic countries for testing.
     */
    private function seedBasicCountries(): void
    {
        // Create the basic countries that the app needs
        $countries = [
            ['name_fr' => 'Thaïlande', 'slug' => 'thailande', 'emoji' => '🇹🇭'],
            ['name_fr' => 'Japon', 'slug' => 'japon', 'emoji' => '🇯🇵'],
            ['name_fr' => 'Vietnam', 'slug' => 'vietnam', 'emoji' => '🇻🇳'],
            ['name_fr' => 'Chine', 'slug' => 'chine', 'emoji' => '🇨🇳'],
        ];

        foreach ($countries as $country) {
            \App\Models\Country::firstOrCreate(
                ['slug' => $country['slug']],
                [
                    'name_fr' => $country['name_fr'],
                    'emoji' => $country['emoji'],
                    'description' => "Description pour {$country['name_fr']}",
                ]
            );
        }
    }
}
