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

// Routes protégées pour les utilisateurs connectés
Route::middleware('auth')->group(function () {
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profil', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
