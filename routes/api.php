<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| News API Routes
|--------------------------------------------------------------------------
|
| RESTful API endpoints for news management via automated tools like n8n.
| Authentication via Sanctum token required for all endpoints.
|
*/
Route::middleware(['auth:sanctum', 'throttle:api-news'])->group(function () {
    // Main CRUD endpoints with rate limiting
    Route::apiResource('news', NewsController::class);

    // Alternative explicit routes (same functionality as apiResource)
    // Alternative explicit routes (same functionality as apiResource)
    // Route::get('/news', [NewsController::class, 'index']);         // GET /api/news - List articles
    // Route::post('/news', [NewsController::class, 'store']);        // POST /api/news - Create article
    // Route::get('/news/{news}', [NewsController::class, 'show']);   // GET /api/news/{id} - Show article
    // Route::put('/news/{news}', [NewsController::class, 'update']); // PUT /api/news/{id} - Update article
    // Route::delete('/news/{news}', [NewsController::class, 'destroy']); // DELETE /api/news/{id} - Delete article
});

/*
|--------------------------------------------------------------------------
| Articles API Routes
|--------------------------------------------------------------------------
|
| RESTful API endpoints for articles/blog management via automated tools.
| Authentication via Sanctum token required for all endpoints.
| Same rate limiting as news API (30/min, 100/hour).
|
*/
// Fix model binding for articles API - use ID instead of slug
Route::bind('article', function ($value) {
    return \App\Models\Article::where('id', $value)->firstOrFail();
});

Route::middleware(['auth:sanctum', 'throttle:api-news'])->group(function () {
    // Main CRUD endpoints with rate limiting (reusing same rate limiter as news)
    Route::apiResource('articles', ArticleController::class);
});
