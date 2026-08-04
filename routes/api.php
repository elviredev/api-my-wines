<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\WineController;
use Illuminate\Support\Facades\Route;

// Login
Route::post('/login', [AuthController::class, 'login']);

// Routes "wines" publiques
Route::apiResource('wines', WineController::class)
  ->only(['index', 'show']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {

  // Logout, Me
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'me']);

  // Routes "wines" protégées
  Route::apiResource('wines', WineController::class)
  ->only(['store', 'update', 'destroy']);

  // Routes Profil
  Route::get('/profile', [ProfileController::class, 'show']);
  Route::put('/profile', [ProfileController::class, 'update']);
  Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

});


