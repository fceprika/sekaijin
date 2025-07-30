<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreNewsRequest;
use App\Http\Requests\Api\UpdateNewsRequest;
use App\Http\Resources\Api\NewsListResource;
use App\Http\Resources\Api\NewsResource;
use App\Models\News;
use App\Services\ImageDownloadService;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    private ImageDownloadService $imageDownloadService;

    public function __construct(ImageDownloadService $imageDownloadService)
    {
        $this->imageDownloadService = $imageDownloadService;
    }

    /**
     * Store a newly created news article.
     */
    public function store(StoreNewsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            // Check for duplicate title
            if (News::hasDuplicateTitle($validated['title'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un article avec ce titre existe déjà.',
                    'data' => null,
                ], 409);
            }

            // Handle thumbnail download if URL provided
            $thumbnailPath = null;
            if (! empty($validated['thumbnail_url'])) {
                $thumbnailPath = $this->imageDownloadService->downloadAndStore($validated['thumbnail_url']);

                if (! $thumbnailPath) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de télécharger l\'image depuis l\'URL fournie.',
                        'data' => null,
                    ], 400);
                }
            }

            // Prepare data for creation
            $newsData = $validated;
            
            // Remove thumbnail_url as it's not a database field
            unset($newsData['thumbnail_url']);
            
            // Add processed fields
            $newsData['thumbnail_path'] = $thumbnailPath;
            $newsData['published_at'] = $validated['status'] === 'published' ? now() : null;
            
            // Create the news article
            $news = News::create($newsData);

            // Load the author relationship
            $news->load('author');

            return response()->json([
                'success' => true,
                'message' => 'Article créé avec succès.',
                'data' => new NewsResource($news),
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create news article', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'article.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified news article.
     */
    public function show(News $news): JsonResponse
    {
        try {
            // Load the author relationship
            $news->load('author');

            return response()->json([
                'success' => true,
                'message' => 'Article récupéré avec succès.',
                'data' => new NewsResource($news),
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to retrieve news article', [
                'news_id' => $news->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'article.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Update the specified news article.
     */
    public function update(UpdateNewsRequest $request, News $news): JsonResponse
    {
        try {
            $validated = $request->validated();

            // Check for duplicate title if title is being updated
            if (isset($validated['title']) && News::hasDuplicateTitle($validated['title'], $news->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un autre article avec ce titre existe déjà.',
                    'data' => null,
                ], 409);
            }

            // Handle thumbnail download if new URL provided
            if (isset($validated['thumbnail_url']) && ! empty($validated['thumbnail_url'])) {
                // Delete old thumbnail if it exists
                if ($news->thumbnail_path) {
                    $this->imageDownloadService->deleteImage($news->thumbnail_path);
                }

                // Download new thumbnail
                $thumbnailPath = $this->imageDownloadService->downloadAndStore($validated['thumbnail_url']);

                if (! $thumbnailPath) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de télécharger l\'image depuis l\'URL fournie.',
                        'data' => null,
                    ], 400);
                }

                $validated['thumbnail_path'] = $thumbnailPath;
            }

            // Handle status change to published
            if (isset($validated['status']) && $validated['status'] === 'published' && $news->status !== 'published') {
                $validated['published_at'] = now();
            } elseif (isset($validated['status']) && $validated['status'] === 'draft') {
                $validated['published_at'] = null;
            }

            // Remove thumbnail_url from validated data as it's not a database field
            unset($validated['thumbnail_url']);

            // Update the news article
            $news->update($validated);

            // Load the author relationship
            $news->load('author');

            return response()->json([
                'success' => true,
                'message' => 'Article mis à jour avec succès.',
                'data' => new NewsResource($news),
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to update news article', [
                'news_id' => $news->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'article.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified news article from storage.
     */
    public function destroy(News $news): JsonResponse
    {
        try {
            // Delete thumbnail file if it exists
            if ($news->thumbnail_path) {
                $this->imageDownloadService->deleteImage($news->thumbnail_path);
            }

            // Delete the news article
            $news->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article supprimé avec succès.',
                'data' => null,
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to delete news article', [
                'news_id' => $news->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'article.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Get a list of news articles with pagination.
     */
    public function index(): JsonResponse
    {
        try {
            $news = News::with('author')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $newsData = NewsListResource::collection($news);

            return response()->json([
                'success' => true,
                'message' => 'Articles récupérés avec succès.',
                'data' => $newsData,
                'pagination' => [
                    'current_page' => $news->currentPage(),
                    'per_page' => $news->perPage(),
                    'total' => $news->total(),
                    'last_page' => $news->lastPage(),
                    'has_more_pages' => $news->hasMorePages(),
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to retrieve news articles', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des articles.',
                'data' => null,
            ], 500);
        }
    }
}
