<?php

namespace Tests\Traits;

use App\Models\Country;
use Illuminate\Support\Str;

trait CreatesCountries
{
    /**
     * Create a Thailand country.
     */
    protected function createThailand(array $attributes = []): Country
    {
        return Country::firstOrCreate(
            ['slug' => 'thailande'],
            array_merge([
                'name_fr' => 'Thaïlande',
                'emoji' => '🇹🇭',
                'description' => 'Description pour Thaïlande',
            ], $attributes)
        );
    }

    /**
     * Create a Japan country.
     */
    protected function createJapan(array $attributes = []): Country
    {
        return Country::firstOrCreate(
            ['slug' => 'japon'],
            array_merge([
                'name_fr' => 'Japon',
                'emoji' => '🇯🇵',
                'description' => 'Description pour Japon',
            ], $attributes)
        );
    }

    /**
     * Create a Vietnam country.
     */
    protected function createVietnam(array $attributes = []): Country
    {
        return Country::firstOrCreate(
            ['slug' => 'vietnam'],
            array_merge([
                'name_fr' => 'Vietnam',
                'emoji' => '🇻🇳',
                'description' => 'Description pour Vietnam',
            ], $attributes)
        );
    }

    /**
     * Create a China country.
     */
    protected function createChina(array $attributes = []): Country
    {
        return Country::firstOrCreate(
            ['slug' => 'chine'],
            array_merge([
                'name_fr' => 'Chine',
                'emoji' => '🇨🇳',
                'description' => 'Description pour Chine',
            ], $attributes)
        );
    }

    /**
     * Create all available countries.
     */
    protected function createAllCountries(): array
    {
        return [
            'thailande' => $this->createThailand(),
            'japon' => $this->createJapan(),
            'vietnam' => $this->createVietnam(),
            'chine' => $this->createChina(),
        ];
    }

    /**
     * Get or create a country by name.
     */
    protected function getOrCreateCountry(string $name): Country
    {
        $slug = Str::slug($name);

        return Country::firstOrCreate(
            ['slug' => $slug],
            [
                'name_fr' => $name,
                'emoji' => $this->getEmojiForCountry($name),
                'description' => "Description pour {$name}",
            ]
        );
    }

    /**
     * Get emoji for a country.
     */
    private function getEmojiForCountry(string $name): string
    {
        $emojis = [
            'Thaïlande' => '🇹🇭',
            'Japon' => '🇯🇵',
            'Vietnam' => '🇻🇳',
            'Chine' => '🇨🇳',
        ];

        return $emojis[$name] ?? '🌍';
    }
}
