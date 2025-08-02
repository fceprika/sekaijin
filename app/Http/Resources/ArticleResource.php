<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->when($request->routeIs('api.articles.show'), $this->content),
            'category' => $this->category,
            'category_label' => config("content.article_categories.{$this->category}.label"),
            'image_url' => $this->image_url,
            'country' => [
                'id' => $this->country->id ?? null,
                'name' => $this->country->name_fr ?? null,
                'slug' => $this->country->slug ?? null,
                'emoji' => $this->country->emoji ?? null,
            ],
            'author' => [
                'id' => $this->author->id ?? null,
                'name' => $this->author->name ?? null,
                'avatar' => $this->author->avatar ?? null,
            ],
            'is_featured' => $this->is_featured,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at?->toIso8601String(),
            'views' => $this->views,
            'likes' => $this->likes,
            'reading_time' => $this->getAttributes()['reading_time'] ?? null,
            'reading_time_formatted' => $this->reading_time,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'links' => [
                'self' => url("/api/articles/{$this->id}"),
                'web' => $this->country ? route('country.article.show', [$this->country->slug, $this->slug]) : null,
            ],
        ];
    }
}
