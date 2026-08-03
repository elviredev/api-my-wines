<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  /**
   * Connexion de l'utilisateur
   */

  public function login(LoginRequest $request): JsonResponse
  {
    $credentials = $request->validated();

    if (!Auth::attempt($credentials)) {
      return response()->json([
        'message' => 'Identifiants invalides',
      ], 401);
    }

    /** @var User $user */
    $user = $request->user();

    // Supprime les anciens tokens. Comme je suis le seul utilisateur et que je veux une seule session active, c'est pertinent.
    // Chaque nouvelle connexion invalidera automatiquement les anciens token
    $user->tokens()->delete();

    // Le nom du token créé sera celui de l'application
    $token = $user->createToken(
      config('app.name')
    )->plainTextToken;

    return response()->json([
      'message' => 'Connexion réussie',
      'token' => $token,
      'user' => new UserResource($user),
    ]);

  }

  /**
   * Déconnexion
   */
  public function logout(Request $request): JsonResponse
  {
    /** @var User $user */
    $user = $request->user();

    $user->currentAccessToken()->delete();

    return response()->json([
      'message' => 'Déconnexion réussie'
    ]);
  }

  /**
   * Utilisateur connecté
   */
  public function me(): UserResource
  {
    /** @var User $user */
    $user = Auth::user();

    return new UserResource($user);
  }
}
