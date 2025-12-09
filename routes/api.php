<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BeritaController;
use App\Http\Controllers\API\KomentarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public berita routes
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{id}', [BeritaController::class, 'show']);
Route::get('/berita/kategori/{kategori}', [BeritaController::class, 'byCategory']);
Route::get('/search', [BeritaController::class, 'search']);

// Public komentar routes
Route::get('/berita/{beritaId}/komentar', [KomentarController::class, 'index']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Berita routes (CRUD)
    Route::post('/berita', [BeritaController::class, 'store']);
    Route::put('/berita/{id}', [BeritaController::class, 'update']);
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy']);

    // Komentar routes (CUD)
    Route::post('/berita/{beritaId}/komentar', [KomentarController::class, 'store']);
    Route::put('/berita/{beritaId}/komentar/{id}', [KomentarController::class, 'update']);
    Route::delete('/berita/{beritaId}/komentar/{id}', [KomentarController::class, 'destroy']);
});
