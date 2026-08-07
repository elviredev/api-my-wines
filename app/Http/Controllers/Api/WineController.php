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
   * Liste des vins.
   *
   * Retourne une liste paginée pouvant être filtrée et triée.
   *
   * @group Vins
   *
   * @unauthenticated
   *
   * @queryParam search string Recherche par nom, appellation, domaine, région, vendeur ou pays. Example: Bourgogne
   * @queryParam wine_types[] string Types de vin à filtrer. Valeurs possibles : Rouge, Blanc, Rosé, Champagne, Spiritueux, Autre. Example: Rouge
   * @queryParam region string Région viticole. Example: Bordeaux
   * @queryParam vintage integer Millésime. Example: 2020
   * @queryParam min_price decimal Prix minimum. Example: 55.50
   * @queryParam min_rating decimal Note minimale (0 à 20). Example: 15
   * @queryParam favorite boolean Afficher uniquement les favoris. Example: true
   * @queryParam available boolean Afficher uniquement les bouteilles disponibles. Example: true
   * @queryParam per_page integer Nombre d'éléments par page (8, 18, 50 ou 100). Example: 18
   * @queryParam sort string Tri des résultats. Valeurs possibles :
   *  name_asc,
   *  name_desc,
   *  wine_type_asc,
   *  wine_type_desc,
   *  region_asc,
   *  region_desc,
   *  vintage_asc,
   *  vintage_desc,
   *  rating_asc,
   *  rating_desc,
   *  price_asc,
   *  price_desc,
   *  favorite
   *
   *  Example: name_asc
   *
   * @responseFile storage/app/scribe/wines.index.json
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
   * Ajoute un vin à la collection.
   *
   * @group Vins
   *
   * @bodyParam name string required Nom du vin. Example: Château Margaux
   * @bodyParam appellation string required Appellation. Example: Margaux
   * @bodyParam domain string Domaine. Example: Château Margaux
   * @bodyParam grape string Cépage. Example: Sauvignon blanc
   * @bodyParam country string Pays. Example: France
   * @bodyParam region string Région viticole. Example: Bordeaux
   * @bodyParam vintage integer Millésime. Example: 2020
   * @bodyParam wine_type string Type de vin. Example: Rouge
   * @bodyParam price number Prix d'achat. Example: 39.90
   * @bodyParam seller string Vendeur. Example: Nicolas
   * @bodyParam purchase_date date Date d'achat. Example: 2026-07-15
   * @bodyParam rating number Note sur 20. Example: 18
   * @bodyParam favorite boolean Ajouter aux favoris. Example: true
   * @bodyParam buy_again boolean Souhaite en racheter. Example: true
   * @bodyParam available boolean Bouteille présente en cave. Example: false
   * @bodyParam nose string Description du nez.
   * @bodyParam palate string Description en bouche.
   * @bodyParam description string Commentaires personnels.
   * @bodyParam pairings array Accords mets et vins.
   * @bodyParam image file Photo de la bouteille.
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
   * Détail d'un vin
   *
   * Retourne les informations d'un vin.
   *
   * @group Vins
   *
   * @unauthenticated
   */
  public function show(Wine $wine): WineResource
  {
    return new WineResource($wine);
  }

  /**
   * Modifie un vin existant.
   *
   * Les paramètres sont identiques à ceux utilisés lors de la création d'un vin.
   *
   * @group Vins
   *
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
   * Supprime un vin de la collection.
   *
   * @group Vins
   */
  public function destroy(Wine $wine)
  {
    // supprimer l'image quand on supprime un vin
    $wine->deleteImage();

    $wine->delete();

    return response()->noContent();
  }

  /**
   * Supprime la photo d'un vin
   *
   * Supprime uniquement la photo associée au vin sans supprimer la fiche du vin.
   *
   * @group Vins
   *
   * @param Wine $wine
   * @return Response
   */
  public function destroyImage(Wine $wine): Response
  {
    $wine->deleteImage();

    return response()->noContent();
  }
}
