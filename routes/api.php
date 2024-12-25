<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;

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

//Login dan Register
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}', [UserController::class, 'update']);

    //Artikel
    Route::get('/articles', [ArticleController::class, 'index']); // Get all articles
    Route::get('/articles/{id}', [ArticleController::class, 'show']); // Get specific article
    Route::post('/articles', [ArticleController::class, 'store']); // Create article
    Route::put('/articles/{id}', [ArticleController::class, 'update']); // Update article
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']); // Delete article

    
});
