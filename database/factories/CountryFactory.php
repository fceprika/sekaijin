<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = [
            ['name' => 'Thaïlande', 'emoji' => '🇹🇭'],
            ['name' => 'Japon', 'emoji' => '🇯🇵'],
            ['name' => 'Vietnam', 'emoji' => '🇻🇳'],
            ['name' => 'Chine', 'emoji' => '🇨🇳'],
        ];

        $country = fake()->randomElement($countries);

        return [
            'name_fr' => $country['name'],
            'slug' => Str::slug($country['name']),
            'emoji' => $country['emoji'],
            'description' => fake()->paragraphs(2, true),
            'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'updated_at' => fn ($attributes) => $attributes['created_at'],
        ];
    }
}
