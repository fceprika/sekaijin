<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Favorite;
use App\Models\News;
use App\Http\Requests\ToggleFavoriteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Toggle favorite status for an item
     */
    public function toggle(ToggleFavoriteRequest $request)
    {
        $user = Auth::user();
        
        // Check authorization
        $this->authorize('toggle', Favorite::class);
        $type = $request->input('type');
        $itemId = $request->input('id');
        $modelClass = $request->getModelClass();

        // Verify the content exists and is published (already done in FormRequest)
        $contentItem = $request->getContentItem();

        // Find existing favorite
        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', $modelClass)
            ->where('favoritable_id', $itemId)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $isFavorited = false;
            $message = $type === 'article' ? 'Article retiré des favoris' : 'Actualité retirée des favoris';
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_type' => $modelClass,
                'favoritable_id' => $itemId,
            ]);
            $isFavorited = true;
            $message = $type === 'article' ? 'Article ajouté aux favoris' : 'Actualité ajoutée aux favoris';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'favorited' => $isFavorited,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Display user's favorites
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check authorization
        $this->authorize('viewAny', Favorite::class);

        // Get favorite articles with their relationships
        $favoriteArticles = $user->favoriteArticles()
            ->with(['author', 'country'])
            ->published()
            ->orderBy('favorites.created_at', 'desc')
            ->paginate(10, ['*'], 'articles_page');

        // Get favorite news with their relationships
        $favoriteNews = $user->favoriteNews()
            ->with(['author', 'country'])
            ->published()
            ->orderBy('favorites.created_at', 'desc')
            ->paginate(10, ['*'], 'news_page');

        return view('favorites.index', compact('favoriteArticles', 'favoriteNews'));
    }

    /**
     * Get favorites count for user
     */
    public function count()
    {
        $user = Auth::user();
        
        $articlesCount = $user->favoriteArticles()->count();
        $newsCount = $user->favoriteNews()->count();
        $totalCount = $articlesCount + $newsCount;

        return response()->json([
            'articles' => $articlesCount,
            'news' => $newsCount,
            'total' => $totalCount
        ]);
    }
}
