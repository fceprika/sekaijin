<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// SEO Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap/clear', [App\Http\Controllers\SitemapController::class, 'clearCache'])->name('sitemap.clear');

// Maintenance status route (for checking if maintenance is enabled)
Route::get('/maintenance-status', function () {
    return response()->json([
        'maintenance_mode' => config('app.maintenance_mode', false),
        'message' => config('app.maintenance_mode', false) ? 'Site en maintenance' : 'Site disponible',
    ]);
})->name('maintenance.status');

// Temporary cache clear route
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Cache::flush();

    return response()->json(['message' => 'Cache cleared']);
});

Route::get('/about', function () {
    $seoService = new \App\Services\SeoService;
    $seoData = $seoService->generateSeoData('about');
    $structuredData = $seoService->generateStructuredData('about');

    return view('about', compact('seoData', 'structuredData'));
});

Route::get('/services', function () {
    return view('services');
});

Route::get('/contact', function () {
    $seoService = new \App\Services\SeoService;
    $seoData = $seoService->generateSeoData('contact');
    $structuredData = $seoService->generateStructuredData('contact');

    return view('contact', compact('seoData', 'structuredData'));
})->name('contact');

// Pages légales
Route::get('/conditions-utilisation', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/politique-confidentialite', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/mentions-legales', function () {
    return view('legal.mentions');
})->name('legal');

// Routes d'authentification
Route::get('/inscription', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/inscription', [App\Http\Controllers\AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/inscription/enrichir-profil', [App\Http\Controllers\AuthController::class, 'enrichProfile'])->name('enrich.profile')->middleware('auth');
Route::get('/connexion', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/connexion', [App\Http\Controllers\AuthController::class, 'login'])->middleware('throttle:20,1');
Route::post('/deconnexion', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [App\Http\Controllers\AuthController::class, 'showVerifyEmail'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\AuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [App\Http\Controllers\AuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')->name('verification.send');
});

// API Routes with rate limiting
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/api/expats-by-country', [App\Http\Controllers\Api\ExpatController::class, 'expatsByCountry']);
    Route::get('/api/members-with-location', [App\Http\Controllers\Api\ExpatController::class, 'membersWithLocation']);
    Route::get('/api/map-config', [App\Http\Controllers\Api\MapController::class, 'getMapConfig']);
    Route::get('/api/geocode', [App\Http\Controllers\Api\MapController::class, 'geocode']);
    Route::get('/api/check-username/{username}', [App\Http\Controllers\AuthController::class, 'checkUsername']);
    Route::post('/api/cities', [App\Http\Controllers\Api\CityController::class, 'getCities']);
    Route::post('/api/city-coordinates', [App\Http\Controllers\Api\CityController::class, 'getCityCoordinates']);
});

Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('/api/update-location', [App\Http\Controllers\Api\ExpatController::class, 'updateLocation']);
    Route::post('/api/remove-location', [App\Http\Controllers\Api\ExpatController::class, 'removeLocation']);
});

// Event management routes for ambassadors and admins (require email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/evenements/create', [App\Http\Controllers\EventController::class, 'create'])->name('events.create');
    Route::post('/evenements', [App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/evenements/{event}/edit', [App\Http\Controllers\EventController::class, 'edit'])->name('events.edit');
    Route::put('/evenements/{event}', [App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::delete('/evenements/{event}', [App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');
});

// Routes protégées pour les utilisateurs connectés (AVANT les routes pays)
Route::middleware('auth')->group(function () {
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profil', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/clear-location', [App\Http\Controllers\ProfileController::class, 'clearLocation'])->name('profile.clear-location');

    // Articles management routes (viewing own articles doesn't require verification)
    Route::get('/mes-articles', [App\Http\Controllers\ArticleController::class, 'myArticles'])->name('articles.my-articles');
});

// Content creation routes (require email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    // Articles creation and editing
    Route::get('/articles/create', [App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [App\Http\Controllers\ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [App\Http\Controllers\ArticleController::class, 'update'])->name('articles.update');
    Route::get('/articles/{article}/preview', [App\Http\Controllers\ArticleController::class, 'preview'])->name('articles.preview');
    Route::delete('/articles/{article}', [App\Http\Controllers\ArticleController::class, 'destroy'])->name('articles.destroy');
});

// Routes d'administration (protection par rôle admin)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des articles
    Route::get('/articles', [App\Http\Controllers\AdminController::class, 'articles'])->name('articles');
    Route::get('/articles/create', [App\Http\Controllers\AdminController::class, 'createArticle'])->name('articles.create');
    Route::post('/articles', [App\Http\Controllers\AdminController::class, 'storeArticle'])->name('articles.store');
    Route::get('/articles/{article:id}/edit', [App\Http\Controllers\AdminController::class, 'editArticle'])->name('articles.edit');
    Route::put('/articles/{article:id}', [App\Http\Controllers\AdminController::class, 'updateArticle'])->name('articles.update');
    Route::patch('/articles/{article}/publish', [App\Http\Controllers\AdminController::class, 'publishArticle'])->name('articles.publish');
    Route::get('/articles/drafts', [App\Http\Controllers\AdminController::class, 'drafts'])->name('articles.drafts');
    Route::delete('/articles/{article:id}', [App\Http\Controllers\AdminController::class, 'destroyArticle'])->name('articles.destroy');
    Route::post('/articles/bulk', [App\Http\Controllers\AdminController::class, 'bulkArticleAction'])->name('articles.bulk');

    // Gestion des actualités
    Route::get('/news', [App\Http\Controllers\AdminController::class, 'news'])->name('news');
    Route::get('/news/create', [App\Http\Controllers\AdminController::class, 'createNews'])->name('news.create');
    Route::post('/news', [App\Http\Controllers\AdminController::class, 'storeNews'])->name('news.store');
    Route::get('/news/{news:id}/edit', [App\Http\Controllers\AdminController::class, 'editNews'])->name('news.edit');
    Route::put('/news/{news:id}', [App\Http\Controllers\AdminController::class, 'updateNews'])->name('news.update');
    Route::delete('/news/{news:id}', [App\Http\Controllers\AdminController::class, 'destroyNews'])->name('news.destroy');
    Route::post('/news/bulk', [App\Http\Controllers\AdminController::class, 'bulkNewsAction'])->name('news.bulk');

    // Upload d'images pour TinyMCE
    Route::post('/upload-image', [App\Http\Controllers\AdminController::class, 'uploadImage'])->name('upload.image');

    // Preview routes
    Route::post('/articles/preview', [App\Http\Controllers\AdminController::class, 'previewArticle'])->name('articles.preview');
    Route::post('/news/preview', [App\Http\Controllers\AdminController::class, 'previewNews'])->name('news.preview');

    // Gestion des annonces
    Route::get('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::post('/announcements/{announcement}/approve', [App\Http\Controllers\Admin\AnnouncementController::class, 'approve'])->name('announcements.approve');
    Route::post('/announcements/{announcement}/refuse', [App\Http\Controllers\Admin\AnnouncementController::class, 'refuse'])->name('announcements.refuse');
    Route::post('/announcements/bulk-action', [App\Http\Controllers\Admin\AnnouncementController::class, 'bulkAction'])->name('announcements.bulk-action');
});

// Page d'invitation pour les non-membres
Route::get('/invitation-membre', function () {
    return view('auth.member-invitation');
})->name('member.invitation');

// Profils publics (protégés par l'authentification)
Route::get('/membre/{name}', [App\Http\Controllers\PublicProfileController::class, 'show'])->name('public.profile');

// Routes des favoris (protégées par l'authentification)
Route::middleware('auth')->group(function () {
    Route::post('/favorites/toggle', [App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/mes-favoris', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::get('/favorites/count', [App\Http\Controllers\FavoriteController::class, 'count'])->name('favorites.count');
});

// Routes globales des annonces
Route::prefix('annonces')->group(function () {
    Route::get('/', [App\Http\Controllers\AnnouncementController::class, 'globalIndex'])->name('announcements.index');
    Route::get('/mes-annonces', [App\Http\Controllers\AnnouncementController::class, 'myAnnouncements'])->name('announcements.my')->middleware('auth');
    Route::get('/create', [App\Http\Controllers\AnnouncementController::class, 'create'])->name('announcements.create')->middleware(['auth', 'verified']);
    Route::post('/', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store')->middleware(['auth', 'verified']);
    Route::get('/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/{announcement}/edit', [App\Http\Controllers\AnnouncementController::class, 'edit'])->name('announcements.edit')->middleware(['auth', 'verified']);
    Route::put('/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'update'])->name('announcements.update')->middleware(['auth', 'verified']);
    Route::delete('/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy')->middleware(['auth', 'verified']);
});

// Routes par pays avec middleware de validation (EN DERNIER pour éviter les conflits)
Route::prefix('{country}')->middleware('country')->group(function () {
    Route::get('/', [App\Http\Controllers\CountryController::class, 'index'])->name('country.index');
    Route::get('/actualites', [App\Http\Controllers\CountryController::class, 'actualites'])->name('country.actualites');
    Route::get('/actualites/{news:slug}', [App\Http\Controllers\CountryController::class, 'showNews'])->name('country.news.show');
    Route::get('/blog', [App\Http\Controllers\CountryController::class, 'blog'])->name('country.blog');
    Route::get('/blog/{article:slug}', [App\Http\Controllers\CountryController::class, 'showArticle'])->name('country.article.show');
    Route::get('/communaute', [App\Http\Controllers\CountryController::class, 'communaute'])->name('country.communaute');
    Route::get('/evenements', [App\Http\Controllers\CountryController::class, 'evenements'])->name('country.evenements');
    Route::get('/evenements/{event}', [App\Http\Controllers\CountryController::class, 'showEvent'])->name('country.event.show');

    // Routes des annonces par pays
    Route::get('/annonces', [App\Http\Controllers\CountryController::class, 'annonces'])->name('country.annonces');
    Route::get('/annonces/create', [App\Http\Controllers\CountryController::class, 'createAnnouncement'])->name('country.announcements.create')->middleware(['auth', 'verified']);
    Route::post('/annonces', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('country.announcements.store')->middleware(['auth', 'verified']);
    Route::get('/annonces/{announcement}', [App\Http\Controllers\CountryController::class, 'showAnnouncement'])->name('country.announcement.show');
});
