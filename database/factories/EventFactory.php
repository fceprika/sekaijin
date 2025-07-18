<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $categories = ['reunion', 'networking', 'culture', 'sport', 'formation'];
        $isOnline = fake()->boolean(30);
        $price = fake()->optional(0.6)->randomFloat(2, 0, 500);
        $startDate = fake()->dateTimeBetween('now', '+6 months');
        $endDate = fake()->optional(0.7)->dateTimeBetween($startDate, '+6 months');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(2, true),
            'full_description' => fake()->paragraphs(5, true),
            'category' => fake()->randomElement($categories),
            'country_id' => Country::factory(),
            'organizer_id' => User::factory()->ambassador(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $isOnline ? null : fake()->company() . ' ' . fake()->city(),
            'address' => $isOnline ? null : fake()->address(),
            'is_online' => $isOnline,
            'online_link' => $isOnline ? fake()->url() : null,
            'price' => $price ?? 0,
            'max_participants' => fake()->optional(0.7)->numberBetween(10, 100),
            'current_participants' => 0,
            'is_published' => fake()->boolean(80),
            'is_featured' => fake()->boolean(20),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fn ($attributes) => $attributes['created_at'],
        ];
    }

    /**
     * Indicate that the event is online.
     */
    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_online' => true,
            'location' => null,
            'address' => null,
            'online_link' => fake()->url(),
        ]);
    }

    /**
     * Indicate that the event is offline.
     */
    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_online' => false,
            'location' => fake()->company() . ' ' . fake()->city(),
            'address' => fake()->address(),
            'online_link' => null,
        ]);
    }

    /**
     * Indicate that the event is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0,
        ]);
    }

    /**
     * Indicate that the event is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    /**
     * Indicate that the event is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_published' => true,
        ]);
    }

    /**
     * Indicate that the event has participants.
     */
    public function withParticipants(?int $count = null): static
    {
        return $this->state(fn (array $attributes) => [
            'current_participants' => $count ?? fake()->numberBetween(1, $attributes['max_participants'] ?? 50),
        ]);
    }
}
