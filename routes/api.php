<?php

use App\Http\Controllers\ArtikelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

//Login dan Register
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/verify-token', [UserController::class, 'verifyToken']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);  // POST untuk create user
    Route::put('/users/{id}', [UserController::class, 'update']);  // PUT untuk update user
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/users/check-email', [UserController::class, 'checkEmail']);

    //Artikel
    Route::get('/artikel', [ArtikelController::class, 'index']); // Get all articles
    Route::get('artikel/{id}', [ArtikelController::class, 'show']); // Get specific article
    Route::post('/artikel', [ArtikelController::class, 'store']); // Create article
    Route::post('/artikel/{id}', [ArtikelController::class, 'update']); // Update article
    Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy']); // Delete article    
});

Route::get('/dashboard', [DashboardController::class, 'getDashboardData']);
Route::get('/artikeluser', [ArtikelController::class, 'index']); // Get all articles
Route::get('/artikeluser/{id}', [ArtikelController::class, 'show']);