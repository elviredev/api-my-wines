<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexWineRequest;
use App\Http\Requests\StoreWineRequest;
use App\Http\Requests\UpdateWineRequest;
use App\Http\Resources\WineResource;
use App\Models\Wine;

class WineController extends Controller
{
  /**
   * Display a listing of the resource.
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
