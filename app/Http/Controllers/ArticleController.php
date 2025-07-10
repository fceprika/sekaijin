<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Country;
use App\Http\Requests\StoreUserArticleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        $countries = Country::orderBy('name_fr')->get();
        $categories = collect(config('content.article_categories'))->mapWithKeys(function ($category, $key) {
            return [$key => $category['label']];
        })->all();
        
        return view('articles.create', compact('countries', 'categories'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreUserArticleRequest $request)
    {

        $article = Article::create([
            'title' => $request->title,
            'slug' => Article::generateSlug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category' => $request->category,
            'country_id' => $request->country_id,
            'author_id' => Auth::id(),
            'reading_time' => $request->reading_time ?? $this->calculateReadingTime($request->content),
            'is_published' => false, // Toujours en brouillon par défaut
            'is_featured' => false,
        ]);

        return redirect()->route('articles.edit', $article)
            ->with('success', 'Article créé avec succès ! Il est actuellement en mode brouillon.');
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur ou un admin
        if ($article->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet article.');
        }

        $countries = Country::orderBy('name_fr')->get();
        $categories = collect(config('content.article_categories'))->mapWithKeys(function ($category, $key) {
            return [$key => $category['label']];
        })->all();

        return view('articles.edit', compact('article', 'countries', 'categories'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(StoreUserArticleRequest $request, Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur ou un admin
        if ($article->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet article.');
        }

        $article->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category' => $request->category,
            'country_id' => $request->country_id,
            'reading_time' => $request->reading_time ?? $this->calculateReadingTime($request->content),
        ]);

        return redirect()->route('articles.edit', $article)
            ->with('success', 'Article mis à jour avec succès !');
    }

    /**
     * Preview the article.
     */
    public function preview(Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur ou un admin
        if ($article->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à prévisualiser cet article.');
        }

        return view('articles.preview', compact('article'));
    }

    /**
     * Display user's articles.
     */
    public function myArticles()
    {
        $articles = Article::where('author_id', Auth::id())
            ->with(['country', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('articles.my-articles', compact('articles'));
    }

    /**
     * Delete the specified article.
     */
    public function destroy(Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur ou un admin
        if ($article->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cet article.');
        }

        $article->delete();

        return redirect()->route('articles.my-articles')
            ->with('success', 'Article supprimé avec succès !');
    }

    /**
     * Calculate reading time based on content.
     */
    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200); // 200 mots par minute
        return max(1, min($readingTime, 60)); // Entre 1 et 60 minutes
    }
}