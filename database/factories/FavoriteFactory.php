<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Event;
use App\Models\Favorite;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $favoritableTypes = [
            Article::class,
            News::class,
            Event::class,
        ];

        $favoritableType = fake()->randomElement($favoritableTypes);

        return [
            'user_id' => User::factory(),
            'favoritable_type' => $favoritableType,
            'favoritable_id' => $favoritableType::factory(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fn ($attributes) => $attributes['created_at'],
        ];
    }

    /**
     * Indicate that the favorite is for an article.
     */
    public function forArticle(): static
    {
        return $this->state(fn (array $attributes) => [
            'favoritable_type' => Article::class,
            'favoritable_id' => Article::factory(),
        ]);
    }

    /**
     * Indicate that the favorite is for news.
     */
    public function forNews(): static
    {
        return $this->state(fn (array $attributes) => [
            'favoritable_type' => News::class,
            'favoritable_id' => News::factory(),
        ]);
    }

    /**
     * Indicate that the favorite is for an event.
     */
    public function forEvent(): static
    {
        return $this->state(fn (array $attributes) => [
            'favoritable_type' => Event::class,
            'favoritable_id' => Event::factory(),
        ]);
    }
}
