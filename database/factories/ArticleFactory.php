<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $categories = ['témoignage', 'guide-pratique', 'travail', 'lifestyle', 'cuisine'];
        $content = fake()->paragraphs(8, true);
        $excerpt = Str::limit(strip_tags($content), 200);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $excerpt,
            'content' => $content,
            'category' => fake()->randomElement($categories),
            'country_id' => Country::factory(),
            'author_id' => User::factory(),
            'is_featured' => fake()->boolean(20),
            'is_published' => fake()->boolean(80),
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
            'views' => fake()->numberBetween(0, 5000),
            'likes' => fake()->numberBetween(0, 200),
            'reading_time' => fake()->numberBetween(3, 15),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fn ($attributes) => $attributes['created_at'],
        ];
    }

    /**
     * Indicate that the article is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => $attributes['published_at'] ?? now(),
        ]);
    }

    /**
     * Indicate that the article is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_published' => true,
            'published_at' => $attributes['published_at'] ?? now(),
        ]);
    }

    /**
     * Indicate that the article is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
