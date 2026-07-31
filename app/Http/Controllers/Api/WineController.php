<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexWineRequest;
use App\Http\Requests\StoreWineRequest;
use App\Http\Requests\UpdateWineRequest;
use App\Http\Resources\WineResource;
use App\Models\Wine;
use Illuminate\Http\Request;

class WineController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(IndexWineRequest $request)
  {
    $request->validated();

    $query = Wine::query();

    // choix dans la homepage pour choisir le nb de vins à afficher
    $perPage = $request->integer('per_page', 8);

    /** recherche et filtres */

    // search
    if ($request->filled('search')) {
      $search = $request->string('search');

      $query->where(function ($query) use ($search) {
        $query
          ->where('name', 'like', "%{$search}%")
          ->orWhere('appellation', 'like', "%{$search}%")
          ->orWhere('domain', 'like', "%{$search}%")
          ->orWhere('country', 'like', "%{$search}%")
          ->orWhere('region', 'like', "%{$search}%")
          ->orWhere('seller', 'like', "%{$search}%");
      });
    }

    // millésime
    if ($request->filled('vintage')) {
      $query->where('vintage', $request->vintage);
    }

    // region
    if ($request->filled('region')) {
      $query->where('region', $request->region);
    }

    // prix
    if ($request->filled('min_price')) {
      $query->where('price', '>=', $request->min_price);
    }

    // note
    if ($request->filled('min_rating')) {
      $query->where('rating', '>=', $request->min_rating);
    }

    // type de vin
    if ($request->filled('wine_types')) {
      $query->whereIn('wine_type', $request->wine_types);
    }

    // favoris
    if ($request->boolean('favorite')) {
      $query->where('favorite', true);
    }

    // disponible
    if ($request->boolean('available')) {
      $query->where('available', true);
    }


    return WineResource::collection(
      $query
        ->latest()
        ->paginate($perPage)
        ->withQueryString()
    );
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreWineRequest $request): WineResource
  {
    $wine = Wine::create($request->validated());

    return new WineResource($wine);
  }

  /**
   * Display the specified resource.
   */
  public function show(Wine $wine): WineResource
  {
    return new WineResource($wine);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateWineRequest $request, Wine $wine): WineResource
  {
    $wine->update($request->validated());

    return new WineResource($wine);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Wine $wine)
  {
    $wine->delete();

    return response()->noContent();
  }
}
