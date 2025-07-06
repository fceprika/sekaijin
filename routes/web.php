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

Route::get('/about', function () {
    return view('about');
});

Route::get('/services', function () {
    return view('services');
});

Route::get('/contact', function () {
    return view('contact');
});

// Routes d'authentification
Route::get('/inscription', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/inscription', [App\Http\Controllers\AuthController::class, 'register'])->middleware('throttle:10,1');
Route::get('/connexion', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [App\Http\Controllers\AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/deconnexion', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// API Routes with rate limiting
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/api/expats-by-country', [App\Http\Controllers\Api\ExpatController::class, 'expatsByCountry']);
    Route::get('/api/members-with-location', [App\Http\Controllers\Api\ExpatController::class, 'membersWithLocation']);
});

Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('/api/update-location', [App\Http\Controllers\Api\ExpatController::class, 'updateLocation']);
    Route::post('/api/remove-location', [App\Http\Controllers\Api\ExpatController::class, 'removeLocation']);
});

// Routes protégées pour les utilisateurs connectés (AVANT les routes pays)
Route::middleware('auth')->group(function () {
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profil', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
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
});

// Profils publics
Route::get('/membre/{name}', [App\Http\Controllers\PublicProfileController::class, 'show'])->name('public.profile');

// Routes par pays avec middleware de validation (EN DERNIER pour éviter les conflits)
Route::prefix('{country}')->middleware('country')->group(function () {
    Route::get('/', [App\Http\Controllers\CountryController::class, 'index'])->name('country.index');
    Route::get('/actualites', [App\Http\Controllers\CountryController::class, 'actualites'])->name('country.actualites');
    Route::get('/actualites/{news}', [App\Http\Controllers\CountryController::class, 'showNews'])->name('country.news.show');
    Route::get('/blog', [App\Http\Controllers\CountryController::class, 'blog'])->name('country.blog');
    Route::get('/blog/{article}', [App\Http\Controllers\CountryController::class, 'showArticle'])->name('country.article.show');
    Route::get('/communaute', [App\Http\Controllers\CountryController::class, 'communaute'])->name('country.communaute');
    Route::get('/evenements', [App\Http\Controllers\CountryController::class, 'evenements'])->name('country.evenements');
    Route::get('/evenements/{event}', [App\Http\Controllers\CountryController::class, 'showEvent'])->name('country.event.show');
});
