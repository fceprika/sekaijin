<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\News;
use App\Models\Country;
use App\Models\User;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\StoreNewsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'articles_total' => Article::count(),
            'articles_published' => Article::where('is_published', true)->count(),
            'articles_draft' => Article::where('is_published', false)->count(),
            'news_total' => News::count(),
            'news_published' => News::where('is_published', true)->count(),
            'news_draft' => News::where('is_published', false)->count(),
            'users_total' => User::count(),
            'countries_active' => Country::count(),
        ];

        $recent_articles = Article::with(['author', 'country'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_news = News::with(['author', 'country'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_articles', 'recent_news'));
    }

    /**
     * Article management
     */
    public function articles(Request $request)
    {
        $query = Article::with(['author', 'country']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        if ($request->filled('country')) {
            $query->where('country_id', $request->country);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->latest()->paginate(15);
        $countries = Country::all();

        return view('admin.articles.index', compact('articles', 'countries'));
    }

    public function createArticle()
    {
        $countries = Country::all();
        $categories = ['témoignage', 'guide-pratique', 'travail', 'lifestyle', 'cuisine'];
        
        return view('admin.articles.create', compact('countries', 'categories'));
    }

    public function storeArticle(StoreArticleRequest $request)
    {
        $data = $request->validated();
        
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        // Set author
        $data['author_id'] = auth()->id();
        
        // Auto-calculate reading time if not provided
        if (empty($data['reading_time'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            $data['reading_time'] = max(1, ceil($wordCount / 200)); // 200 words per minute
        }
        
        // Set default values for checkboxes
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_published'] = $data['is_published'] ?? false;
        
        // Set published_at if publishing
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $article = Article::create($data);

        return redirect()
            ->route('admin.articles')
            ->with('success', 'Article créé avec succès !');
    }

    public function editArticle(Article $article)
    {
        $this->authorize('update', $article);
        
        $countries = Country::all();
        $categories = ['témoignage', 'guide-pratique', 'travail', 'lifestyle', 'cuisine'];
        
        return view('admin.articles.edit', compact('article', 'countries', 'categories'));
    }

    public function updateArticle(UpdateArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);
        
        $data = $request->validated();
        
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        // Auto-calculate reading time if not provided
        if (empty($data['reading_time'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            $data['reading_time'] = max(1, ceil($wordCount / 200));
        }
        
        // Set default values for checkboxes
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_published'] = $data['is_published'] ?? false;
        
        // Set published_at if publishing for the first time
        if ($data['is_published'] && !$article->published_at) {
            $data['published_at'] = now();
        }

        $article->update($data);

        return redirect()
            ->route('admin.articles')
            ->with('success', 'Article mis à jour avec succès !');
    }

    public function destroyArticle(Article $article)
    {
        $this->authorize('delete', $article);
        
        $article->delete();

        return redirect()
            ->route('admin.articles')
            ->with('success', 'Article supprimé avec succès !');
    }

    /**
     * News management
     */
    public function news(Request $request)
    {
        $query = News::with(['author', 'country']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        if ($request->filled('country')) {
            $query->where('country_id', $request->country);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $news = $query->latest()->paginate(15);
        $countries = Country::all();

        return view('admin.news.index', compact('news', 'countries'));
    }

    public function createNews()
    {
        $countries = Country::all();
        $categories = ['administrative', 'vie-pratique', 'culture', 'economie'];
        
        return view('admin.news.create', compact('countries', 'categories'));
    }

    public function storeNews(StoreNewsRequest $request)
    {
        $data = $request->validated();
        
        // Set author
        $data['author_id'] = auth()->id();
        
        // Set default values for checkboxes
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_published'] = $data['is_published'] ?? false;
        
        // Set published_at if publishing
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $news = News::create($data);

        return redirect()
            ->route('admin.news')
            ->with('success', 'Actualité créée avec succès !');
    }

    public function editNews(News $news)
    {
        $this->authorize('update', $news);
        
        $countries = Country::all();
        $categories = ['administrative', 'vie-pratique', 'culture', 'economie'];
        
        return view('admin.news.edit', compact('news', 'countries', 'categories'));
    }

    public function updateNews(StoreNewsRequest $request, News $news)
    {
        $this->authorize('update', $news);
        
        $data = $request->validated();
        
        // Set default values for checkboxes
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_published'] = $data['is_published'] ?? false;
        
        // Set published_at if publishing for the first time
        if ($data['is_published'] && !$news->published_at) {
            $data['published_at'] = now();
        }

        $news->update($data);

        return redirect()
            ->route('admin.news')
            ->with('success', 'Actualité mise à jour avec succès !');
    }

    public function destroyNews(News $news)
    {
        $this->authorize('delete', $news);
        
        $news->delete();

        return redirect()
            ->route('admin.news')
            ->with('success', 'Actualité supprimée avec succès !');
    }

    /**
     * Bulk actions
     */
    public function bulkArticleAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,delete',
            'articles' => 'required|array',
            'articles.*' => 'exists:articles,id'
        ]);

        $articles = Article::whereIn('id', $request->articles)->get();

        foreach ($articles as $article) {
            switch ($request->action) {
                case 'publish':
                    if ($this->authorize('publish', $article)) {
                        $article->update([
                            'is_published' => true,
                            'published_at' => $article->published_at ?? now()
                        ]);
                    }
                    break;
                case 'unpublish':
                    if ($this->authorize('publish', $article)) {
                        $article->update(['is_published' => false]);
                    }
                    break;
                case 'delete':
                    if ($this->authorize('delete', $article)) {
                        $article->delete();
                    }
                    break;
            }
        }

        $count = count($request->articles);
        $message = match($request->action) {
            'publish' => "{$count} article(s) publié(s) avec succès !",
            'unpublish' => "{$count} article(s) dépublié(s) avec succès !",
            'delete' => "{$count} article(s) supprimé(s) avec succès !",
        };

        return redirect()->route('admin.articles')->with('success', $message);
    }

    public function bulkNewsAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,delete',
            'news' => 'required|array',
            'news.*' => 'exists:news,id'
        ]);

        $newsItems = News::whereIn('id', $request->news)->get();

        foreach ($newsItems as $news) {
            switch ($request->action) {
                case 'publish':
                    if ($this->authorize('publish', $news)) {
                        $news->update([
                            'is_published' => true,
                            'published_at' => $news->published_at ?? now()
                        ]);
                    }
                    break;
                case 'unpublish':
                    if ($this->authorize('publish', $news)) {
                        $news->update(['is_published' => false]);
                    }
                    break;
                case 'delete':
                    if ($this->authorize('delete', $news)) {
                        $news->delete();
                    }
                    break;
            }
        }

        $count = count($request->news);
        $message = match($request->action) {
            'publish' => "{$count} actualité(s) publiée(s) avec succès !",
            'unpublish' => "{$count} actualité(s) dépubliée(s) avec succès !",
            'delete' => "{$count} actualité(s) supprimée(s) avec succès !",
        };

        return redirect()->route('admin.news')->with('success', $message);
    }
}