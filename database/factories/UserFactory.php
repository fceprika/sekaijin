<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = ['France', 'Thaïlande', 'Japon', 'Vietnam', 'Chine', 'Canada', 'États-Unis', 'Allemagne', 'Espagne', 'Royaume-Uni'];
        $cities = [
            'France' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Bordeaux'],
            'Thaïlande' => ['Bangkok', 'Chiang Mai', 'Phuket', 'Pattaya', 'Krabi'],
            'Japon' => ['Tokyo', 'Osaka', 'Kyoto', 'Yokohama', 'Nagoya'],
            'Vietnam' => ['Hanoï', 'Ho Chi Minh-Ville', 'Da Nang', 'Nha Trang', 'Hoi An'],
            'Chine' => ['Pékin', 'Shanghai', 'Canton', 'Shenzhen', 'Hong Kong'],
        ];
        $interestCountries = ['Thaïlande', 'Japon', 'Vietnam', 'Chine'];

        $country = fake()->randomElement($countries);
        $city = isset($cities[$country]) ? fake()->randomElement($cities[$country]) : fake()->city();

        return [
            'name' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', '-1 day'),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'first_name' => fake()->optional(0.7)->firstName(),
            'last_name' => fake()->optional(0.7)->lastName(),
            'birth_date' => fake()->optional(0.5)->dateTimeBetween('-60 years', '-18 years'),
            'phone' => fake()->optional(0.3)->phoneNumber(),
            'country_residence' => $country,
            'city_residence' => fake()->optional(0.8)->passthrough($city),
            'interest_country' => fake()->optional(0.4)->randomElement($interestCountries),
            'bio' => fake()->optional(0.6)->paragraphs(2, true),
            'youtube_username' => fake()->optional(0.1)->userName(),
            'instagram_username' => fake()->optional(0.2)->userName(),
            'tiktok_username' => fake()->optional(0.1)->userName(),
            'linkedin_username' => fake()->optional(0.15)->userName(),
            'twitter_username' => fake()->optional(0.1)->userName(),
            'facebook_username' => fake()->optional(0.05)->userName(),
            'telegram_username' => fake()->optional(0.05)->userName(),
            'is_verified' => fake()->boolean(10),
            'is_public_profile' => fake()->boolean(70),
            'is_visible_on_map' => fake()->boolean(30),
            'latitude' => fake()->optional(0.3)->latitude(-90, 90),
            'longitude' => fake()->optional(0.3)->longitude(-180, 180),
            'city_detected' => null,
            'last_login' => fake()->optional(0.9)->dateTimeBetween('-30 days', '-1 hour'),
            'role' => fake()->randomElement(['free', 'free', 'free', 'free', 'premium', 'ambassador']),
            'avatar' => null,
            'created_at' => fake()->dateTimeBetween('-2 years', '-1 day'),
            'updated_at' => fn ($attributes) => $attributes['created_at'],
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_verified' => true,
        ]);
    }

    /**
     * Indicate that the user is a premium member.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'premium',
        ]);
    }

    /**
     * Indicate that the user is an ambassador.
     */
    public function ambassador(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'ambassador',
            'is_verified' => true,
        ]);
    }

    /**
     * Indicate that the user has location data.
     */
    public function withLocation(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_visible_on_map' => true,
            'latitude' => fake()->latitude(-90, 90),
            'longitude' => fake()->longitude(-180, 180),
            'city_detected' => fake()->city(),
        ]);
    }

    /**
     * Indicate that the user has a complete profile.
     */
    public function withCompleteProfile(): static
    {
        return $this->state(fn (array $attributes) => [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'phone' => fake()->phoneNumber(),
            'city_residence' => fake()->city(),
            'bio' => fake()->paragraphs(3, true),
            'is_public_profile' => true,
        ]);
    }
}
