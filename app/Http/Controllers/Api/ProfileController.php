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
   * Retourne le profil de l'utilisateur connecté
   *
   * @group Profil
   *
   * @responseFile storage/app/scribe/profile.show.json
   *
   * @return UserResource
   */
  public function show()
  {
    return new UserResource(auth()->user());
  }

  /**
   * Met à jour le profil de l'utilisateur connecté.
   *
   * @group Profil
   *
   * @bodyParam name string required Nom. Example: Totoro
   * @bodyParam email string required Adresse e-mail. Example: totoro@example.com
   * @bodyParam avatar file Avatar de l'utilisateur (jpg, jpeg, png, webp, avif). Exemple : avatar.jpg
   *
   * @responseFile storage/app/scribe/profile.update.json
   *
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
   * Modifier le mot de passe.
   *
   * Met à jour le mot de passe de l'utilisateur connecté
   *
   * @group Profil
   *
   * @bodyParam current_password string required Mot de passe actuel. Example: password
   * @bodyParam password string required Nouveau mot de passe actuel (8 caractères minimum). Example: NouveauMotDePasse123!
   * @bodyParam password_confirmation string required Confirmation du mot de passe. Example: NouveauMotDePasse123!
   *
   *
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
