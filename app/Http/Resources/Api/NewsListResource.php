<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsListResource extends JsonResource
{
    /**
     * Transform the resource into an array for list views (without full content).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'thumbnail_url' => $this->thumbnail_url,
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ],
            'status' => $this->status,
            'country_id' => $this->country_id,
            'category' => $this->category,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toISOString(),
            'tags' => $this->tags,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
