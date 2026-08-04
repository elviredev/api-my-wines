<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexWineRequest;
use App\Http\Requests\StoreWineRequest;
use App\Http\Requests\UpdateWineRequest;
use App\Http\Resources\WineResource;
use App\Models\Wine;
use Illuminate\Http\Response;

class WineController extends Controller
{
  /**
   * Afficher la liste des vins paginée
   * Permettre la recherche, le filtre et le tri avec le scope scopeFilter()
   */
  public function index(IndexWineRequest $request)
  {
    // choix dans la homepage pour choisir le nb de vins à afficher
    $perPage = $request->integer('per_page', 8);

    return WineResource::collection(
      Wine::query()
        ->filter($request->validated())
        ->paginate($perPage)
        ->withQueryString()
    );
  }

  /**
   * Créer un vin
   */
  public function store(StoreWineRequest $request): WineResource
  {
    $validated = $request->validated();
    // Upload image lors de la création
    $imagePath = null;

    if ($request->hasFile('image')) {
      $imagePath = $request->file('image')->store('wines', 'public');
    }


    $wine = Wine::create([
      ...$validated,
      'image_path' => $imagePath,
    ]);

    return new WineResource($wine);
  }

  /**
   * Afficher un vin
   */
  public function show(Wine $wine): WineResource
  {
    return new WineResource($wine);
  }

  /**
   * Mettre à jour un vin
   * @param UpdateWineRequest $request
   * @param Wine $wine
   * @return WineResource
   */
  public function update(UpdateWineRequest $request, Wine $wine): WineResource
  {
    $validated = $request->validated();

    // remplacer une image
    if ($image = $request->file('image')) {

      $wine->deleteImage();

      $validated['image_path'] = $image->store('wines', 'public');
    }

    $wine->update($validated);

    return new WineResource($wine);
  }

  /**
   * Supprimer un vin
   */
  public function destroy(Wine $wine)
  {
    // supprimer l'image quand on supprime un vin
    $wine->deleteImage();

    $wine->delete();

    return response()->noContent();
  }

  /**
   * Supprimer une image sans supprimer le vin
   * @param Wine $wine
   * @return Response
   */
  public function destroyImage(Wine $wine): Response
  {
    $wine->deleteImage();

    return response()->noContent();
  }
}
