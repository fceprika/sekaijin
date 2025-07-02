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

Route::get('/', function () {
    return view('home');
});

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
Route::post('/inscription', [App\Http\Controllers\AuthController::class, 'register']);
Route::get('/connexion', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/deconnexion', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// API Routes
Route::get('/api/expats-by-country', [App\Http\Controllers\Api\ExpatController::class, 'expatsByCountry']);

// Routes protégées pour les utilisateurs connectés (AVANT les routes pays)
Route::middleware('auth')->group(function () {
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profil', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
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
