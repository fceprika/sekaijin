<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreArticleRequest;
use App\Http\Requests\Api\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ImageDownloadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    protected ImageDownloadService $imageService;

    public function __construct(ImageDownloadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the articles.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Article::with(['country', 'author'])
            ->published()
            ->orderBy('published_at', 'desc');

        // Filter by country
        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by author
        if ($request->has('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Search in title and excerpt
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate($request->input('per_page', 20));

        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            // Handle image URL if provided
            if (isset($validated['image_url'])) {
                $imageUrl = $validated['image_url'];

                // Download and store the image
                $imagePath = $this->imageService->downloadAndStore($imageUrl, 'article_images');

                if ($imagePath) {
                    // Store the relative path and full URL
                    $validated['image_url'] = asset('storage/article_images/' . $imagePath);
                    Log::info('Article image downloaded successfully', ['path' => $imagePath]);
                } else {
                    // Log warning but continue (image is optional)
                    Log::warning('Failed to download article image', ['url' => $imageUrl]);
                    unset($validated['image_url']);
                }
            }

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Article::generateSlug($validated['title']);
            }

            // Check for duplicate title
            if (Article::where('title', $validated['title'])->exists()) {
                return response()->json([
                    'message' => 'Un article avec ce titre existe déjà.',
                    'errors' => [
                        'title' => ['Un article avec ce titre existe déjà.'],
                    ],
                ], 422);
            }

            // Set default values
            $validated['is_published'] = $validated['is_published'] ?? false;
            $validated['is_featured'] = $validated['is_featured'] ?? false;

            // Set published_at if publishing
            if ($validated['is_published'] && ! isset($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            // Calculate reading time if not provided
            if (! isset($validated['reading_time']) && isset($validated['content'])) {
                $validated['reading_time'] = $this->calculateReadingTime($validated['content']);
            }

            // Create the article
            $article = Article::create($validated);

            Log::info('Article created via API', ['id' => $article->id, 'title' => $article->title]);

            return response()->json([
                'message' => 'Article créé avec succès.',
                'data' => new ArticleResource($article),
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating article via API', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erreur lors de la création de l\'article.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): ArticleResource
    {
        $article->load(['country', 'author']);

        return new ArticleResource($article);
    }

    /**
     * Update the specified article in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        try {
            $validated = $request->validated();

            // Handle image URL if provided
            if (isset($validated['image_url']) && $validated['image_url'] !== $article->image_url) {
                $imageUrl = $validated['image_url'];

                // Download and store the new image
                $imagePath = $this->imageService->downloadAndStore($imageUrl, 'article_images');

                if ($imagePath) {
                    $validated['image_url'] = asset('storage/article_images/' . $imagePath);
                    Log::info('Article image updated successfully', ['path' => $imagePath]);
                } else {
                    Log::warning('Failed to download new article image', ['url' => $imageUrl]);
                    unset($validated['image_url']);
                }
            }

            // Regenerate slug if title changed
            if (isset($validated['title']) && $validated['title'] !== $article->title) {
                $validated['slug'] = Article::generateSlug($validated['title'], $article->id);
            }

            // Set published_at if publishing for the first time
            if (isset($validated['is_published']) && $validated['is_published'] && ! $article->is_published) {
                $validated['published_at'] = now();
            }

            // Recalculate reading time if content changed
            if (isset($validated['content']) && ! isset($validated['reading_time'])) {
                $validated['reading_time'] = $this->calculateReadingTime($validated['content']);
            }

            // Update the article
            $article->update($validated);

            Log::info('Article updated via API', ['id' => $article->id]);

            return response()->json([
                'message' => 'Article mis à jour avec succès.',
                'data' => new ArticleResource($article),
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating article via API', ['id' => $article->id, 'error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'article.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            $article->delete();

            Log::info('Article deleted via API', ['id' => $article->id]);

            return response()->json([
                'message' => 'Article supprimé avec succès.',
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting article via API', ['id' => $article->id, 'error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'article.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate reading time based on content.
     */
    private function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / config('content.settings.default_reading_speed', 200));

        return max(1, min($readingTime, config('content.settings.max_reading_time', 60)));
    }
}
