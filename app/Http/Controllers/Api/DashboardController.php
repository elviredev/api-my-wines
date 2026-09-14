<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Models\Wine;

class DashboardController extends Controller
{
  /**
   * Tableau de bord
   *
   * Retourne les statistiques et les principales informations
   * concernant les vins enregistrés.
   *
   * @group Tableau de bord
   *
   * @responseFile storage/app/scribe/dashboard.index.json
   *
   * @return DashboardResource
   *
   */
  public function index(): DashboardResource
  {
    // Nombre total de vins
    $totalWines = Wine::count();

    // Nombre de favoris
    $favorites = Wine::where('favorite', true)->count();

    //Note moyenne
    $averageRating = Wine::avg('rating');

    // Nombre de type de vin
    $wineTypes = Wine::distinct('wine_type')->count('wine_type');

    // Nombre de régions
    $regions = Wine::whereNotNull('region')
      ->distinct('region')
      ->count('region');

    // Valeur totale des vins
    $totalValue = Wine::sum('price');

    // Répartition des vins par type
    $distribution = Wine::query()
      ->selectRaw('wine_type, COUNT(*) as value')
      ->groupBy('wine_type')
      ->get()
      ->map(fn($item) => [
        'name' => $item->wine_type->value,
        'value' => (int)$item->value,
      ])
      ->values();

    // Meilleur vin
    $bestWine = Wine::whereNotNull('rating')
      ->orderByDesc('rating')
      ->select('name', 'vintage')
      ->first();

    $bestWineName = $bestWine
      ? "{$bestWine->name} ({$bestWine->vintage})"
      : null;

    // Cépage principal
    $mainGrape = Wine::whereNotNull('grape')
      ->where('grape', '!=', '')
      ->pluck('grape')
      ->flatMap(function ($grapes) {
        return array_map(function ($grape) {
          // Supprime les pourcentages
          $grape = preg_replace('/\d+%\s*/', '', $grape);

          return trim($grape);
        },
          explode(',', $grapes)
        );
      })
      ->filter()
      ->countBy()
      ->sortDesc()
      ->keys()
      ->first();

    // Région favorite
    $favoriteRegion = Wine::whereNotNull('region')
      ->selectRaw('region, COUNT(*) as total')
      ->groupBy('region')
      ->orderByDesc('total')
      ->first();

    // Millésime le plus ancien
    $oldestVintage = Wine::whereNotNull('vintage')
      ->min('vintage');

    // Prix moyen
    $averagePrice = Wine::avg('price');

    // Vin le plus cher
    $mostExpensiveWine = Wine::whereNotNull('price')
      ->orderByDesc('price')
      ->select('name', 'vintage')
      ->first();

    $mostExpensiveWineName = $mostExpensiveWine
      ? "{$mostExpensiveWine->name} ({$mostExpensiveWine->vintage})"
      : null;

    // Dernier ajout en bdd
    $lastAdded = Wine::whereNotNull('created_at')
      ->latest()
      ->select('name', 'vintage')
      ->first();

    $lastAddedName = $lastAdded
      ? "{$lastAdded->name} ({$lastAdded->vintage})"
      : null;

    return new DashboardResource([
      'stats' => [
        'wines' => $totalWines,
        'favorites' => $favorites,
        'average_rating' => $averageRating !== null
          ? round((float)$averageRating, 1)
          : null,
        'wine_types' => $wineTypes,
        'regions' => $regions,
        'value' => round((float)$totalValue, 2),
      ],

      'distribution' => $distribution,

      'infos' => [
        'best_wine' => $bestWineName,
        'main_grape' => $mainGrape,

        'favorite_region' => $favoriteRegion
          ? $favoriteRegion->region->value
          : null,

        'oldest_vintage' => $oldestVintage,
        'favorites' => $favorites,

        'average_price' => $averagePrice !== null
          ? floor((float)$averagePrice)
          : null,

        'most_expensive_wine' => $mostExpensiveWineName,
        'last_added' => $lastAddedName,
      ],
    ]);
  }
}
