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
   * Connexion d'un utilisateur
   *
   * Authentifie un utilisateur et retourne un token Laravel Sanctum.
   *
   * @group Authentification
   *
   * @unauthenticated
   *
   * @bodyParam email string required Adresse e-mail de l'utilisateur. Example: totoro@example.com
   * @bodyParam password string required Mot de passe. Example: password
   *
   * @response 200 scenario="Connexion réussie" {
   *    "message": "Connexion réussie",
   *    "token": "1|xxxxxxxxxxxxxxxx",
   *    "user": {
   *      "id": 1,
   *      "name": "Totoro",
   *      "email": "totoro@example.com",
   *      "avatar": "/storage/avatars/xxxxxxxxx.jpg",
   *      "created_at": "2026-08-02T15:08:28.000000Z"
   *    }
   * }
   *
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
   * Déconnexion de l'utilisateur
   *
   * Révoque le token d'accès actuellement utilisé.
   *
   * @group Authentification
   *
   * @response 200 {
   *   "message": "Déconnexion réussie",
   * }
   *
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
   *
   * Retourne des informations sur l'utilisateur authentifié
   *
   * @group Authentification
   *
   * @response 200 {
   *   "data": {
   *      "id": 1,
   *      "name": "Totoro",
   *      "email": "totoro@example.com",
   *      "avatar": "/storage/avatars/xxxxxxxxx.jpg",
   *      "created_at": "2026-08-02T15:08:28.000000Z"
   *   }
   * }
   */
  public function me(): UserResource
  {
    /** @var User $user */
    $user = Auth::user();

    return new UserResource($user);
  }
}
