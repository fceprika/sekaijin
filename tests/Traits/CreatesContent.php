<?php

namespace Tests\Traits;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\Country;
use App\Models\Event;
use App\Models\News;
use App\Models\User;

trait CreatesContent
{
    /**
     * Create an article.
     */
    protected function createArticle(array $attributes = []): Article
    {
        $defaults = [
            'country_id' => Country::factory(),
            'author_id' => User::factory(),
        ];

        return Article::factory()->create(array_merge($defaults, $attributes));
    }

    /**
     * Create a published article.
     */
    protected function createPublishedArticle(array $attributes = []): Article
    {
        return Article::factory()->published()->create($attributes);
    }

    /**
     * Create a featured article.
     */
    protected function createFeaturedArticle(array $attributes = []): Article
    {
        return Article::factory()->featured()->create($attributes);
    }

    /**
     * Create news.
     */
    protected function createNews(array $attributes = []): News
    {
        $defaults = [
            'country_id' => Country::factory(),
            'author_id' => User::factory(),
        ];

        return News::factory()->create(array_merge($defaults, $attributes));
    }

    /**
     * Create published news.
     */
    protected function createPublishedNews(array $attributes = []): News
    {
        return News::factory()->published()->create($attributes);
    }

    /**
     * Create featured news.
     */
    protected function createFeaturedNews(array $attributes = []): News
    {
        return News::factory()->featured()->create($attributes);
    }

    /**
     * Create an event.
     */
    protected function createEvent(array $attributes = []): Event
    {
        $defaults = [
            'country_id' => Country::factory(),
            'organizer_id' => User::factory()->ambassador(),
        ];

        return Event::factory()->create(array_merge($defaults, $attributes));
    }

    /**
     * Create an online event.
     */
    protected function createOnlineEvent(array $attributes = []): Event
    {
        return Event::factory()->online()->create($attributes);
    }

    /**
     * Create an offline event.
     */
    protected function createOfflineEvent(array $attributes = []): Event
    {
        return Event::factory()->offline()->create($attributes);
    }

    /**
     * Create a free event.
     */
    protected function createFreeEvent(array $attributes = []): Event
    {
        return Event::factory()->free()->create($attributes);
    }

    /**
     * Create an announcement.
     */
    protected function createAnnouncement(array $attributes = []): Announcement
    {
        $defaults = [
            'user_id' => User::factory(),
        ];

        return Announcement::factory()->create(array_merge($defaults, $attributes));
    }

    /**
     * Create an active announcement.
     */
    protected function createActiveAnnouncement(array $attributes = []): Announcement
    {
        return Announcement::factory()->active()->create($attributes);
    }

    /**
     * Create a pending announcement.
     */
    protected function createPendingAnnouncement(array $attributes = []): Announcement
    {
        return Announcement::factory()->pending()->create($attributes);
    }
}
