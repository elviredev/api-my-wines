<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
  /**
   * Affiche l'utilisateur connecté
   * @return UserResource
   */
  public function show()
  {
    return new UserResource(auth()->user());
  }

  /**
   * Modifier le profil
   * @param UpdateProfileRequest $request
   * @return JsonResponse
   */
  public function update(UpdateProfileRequest $request): JsonResponse
  {
    /** @var User $user */
    $user = $request->user();

    $data = $request->validated();

    // si user change son avatar
    if ($request->hasFile('avatar')) {

      // si avatar déja présent en bdd
      if ($user->avatar_path) {
        // supprime avatar
        Storage::disk('public')->delete($user->avatar_path);
      }

      // stocke le nouvel avatar et enregistre son chemin
      $data['avatar_path'] = $request
        ->file('avatar')
        ->store('avatars', 'public');
    }

    $user->update($data);

    return response()->json([
      'message' => 'Profil mis à jour',
      'user' => new UserResource($user)
    ]);
  }

  /**
   * Modifier le mot de passe
   * @param UpdatePasswordRequest $request
   * @return JsonResponse
   */
  public function updatePassword(UpdatePasswordRequest $request): JsonResponse
  {
    $user = $request->user();

    $user->update([
      'password' => Hash::make($request->password)
    ]);

    return response()->json([
      'message' => 'Mot de passe modifié'
    ]);
  }
}
