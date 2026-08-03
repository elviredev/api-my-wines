<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WineController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Routes publiques
Route::apiResource('wines', WineController::class)
  ->only(['index', 'show']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {

  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'me']);

  Route::apiResource('wines', WineController::class)
  ->only(['store', 'update', 'destroy']);

  // Plus tard...
  // Route::patch('/profile', ...);
  // Route::patch('/profile/password', ...);
  // Route::post('/profile/avatar', ...);

});


