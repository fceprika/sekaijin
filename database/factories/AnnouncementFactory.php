<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $types = ['vente', 'location', 'colocation', 'service'];
        $type = fake()->randomElement($types);
        $statuses = ['pending', 'active', 'refused'];
        $currencies = ['EUR', 'USD', 'THB', 'JPY', 'GBP', 'CHF'];
        $countries = ['France', 'Thaïlande', 'Japon', 'Vietnam', 'Chine'];
        $country = fake()->randomElement($countries);

        $price = null;
        $currency = fake()->randomElement($currencies); // Currency is always required
        if ($type !== 'service' || fake()->boolean(50)) {
            $price = fake()->randomFloat(2, 10, 5000);
        }

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'type' => $type,
            'price' => $price,
            'currency' => $currency,
            'country' => $country,
            'city' => fake()->city(),
            'address' => fake()->optional(0.7)->address(),
            'images' => fake()->optional(0.7)->passthrough([
                'image1.jpg',
                'image2.jpg',
                'image3.jpg',
            ]),
            'status' => fake()->randomElement($statuses),
            'refusal_reason' => null,
            'expiration_date' => fake()->optional(0.7)->dateTimeBetween('+1 week', '+3 months'),
            'views' => fake()->numberBetween(0, 1000),
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'updated_at' => fn ($attributes) => $attributes['created_at'],
        ];
    }

    /**
     * Indicate that the announcement is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'refusal_reason' => null,
        ]);
    }

    /**
     * Indicate that the announcement is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'refusal_reason' => null,
        ]);
    }

    /**
     * Indicate that the announcement is refused.
     */
    public function refused(?string $reason = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refused',
            'refusal_reason' => $reason ?? fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the announcement is of type 'vente'.
     */
    public function vente(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'vente',
            'price' => fake()->randomFloat(2, 50, 10000),
            'currency' => fake()->randomElement(['EUR', 'USD', 'THB', 'JPY']),
        ]);
    }

    /**
     * Indicate that the announcement is of type 'location'.
     */
    public function location(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'location',
            'price' => fake()->randomFloat(2, 200, 3000),
            'currency' => fake()->randomElement(['EUR', 'USD', 'THB', 'JPY']),
        ]);
    }

    /**
     * Indicate that the announcement is of type 'colocation'.
     */
    public function colocation(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'colocation',
            'price' => fake()->randomFloat(2, 100, 1500),
            'currency' => fake()->randomElement(['EUR', 'USD', 'THB', 'JPY']),
        ]);
    }

    /**
     * Indicate that the announcement is of type 'service'.
     */
    public function service(): static
    {
        return $this->state(function (array $attributes) {
            $price = fake()->optional(0.5)->randomFloat(2, 20, 500);

            return [
                'type' => 'service',
                'price' => $price,
                'currency' => $price ? fake()->randomElement(['EUR', 'USD', 'THB', 'JPY']) : fake()->randomElement(['EUR', 'USD', 'THB', 'JPY']),
            ];
        });
    }
}
