<?php

namespace App\Models;

use App\Enums\WineRegion;
use App\Enums\WineType;
use Database\Factories\WineFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\Attributes\Sluggable;


#[Fillable([
  'name',
  'vintage',
  'grape',
  'country',
  'region',
  'description',
  'appellation',
  'slug',
  'domain',
  'wine_type',
  'price',
  'seller',
  'purchase_date',
  'rating',
  'buy_again',
  'available',
  'favorite',
  'image_path',
  'nose',
  'palate',
  'pairings',
])]
#[Sluggable(from: ['name', 'vintage'], to: 'slug', onUpdate: false)]
class Wine extends Model
{

  /** @use HasFactory<WineFactory> */
  use HasFactory;

  protected function casts(): array
  {
    return [
      'wine_type' => WineType::class,
      'region' => WineRegion::class,
      'purchase_date' => 'date',
      'pairings' => 'array',
      'favorite' => 'boolean',
      'available' => 'boolean',
      'buy_again' => 'boolean',
      'price' => 'decimal:2',
      'rating' => 'decimal:1',
    ];
  }

  // Indiquer au modèle que le binding doit se faire sur le slug
  public function getRouteKeyName(): string
  {
    return 'slug';
  }

  // Logique recherche, filtre, tri
  public function scopeFilter(Builder $query, array $filters): Builder
  {
    // search
    if (!empty($filters['search'])) {
      $search = $filters['search'];

      $query->where(function (Builder $query) use ($search) {
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
    if (!empty($filters['vintage'])) {
      $query->where('vintage', $filters['vintage']);
    }

    // region
    if (!empty($filters['region'])) {
      $query->where('region', $filters['region']);
    }

    // prix
    if (isset($filters['min_price'])) {
      $query->where('price', '>=', $filters['min_price']);
    }

    // note
    if (isset($filters['min_rating'])) {
      $query->where('rating', '>=', $filters['min_rating']);
    }

    // type de vin
    if (!empty($filters['wine_types'])) {
      $query->whereIn('wine_type', $filters['wine_types']);
    }

    // favoris
    if (!empty($filters['favorite'])) {
      $query->where('favorite', true);
    }

    // disponible
    if (!empty($filters['available'])) {
      $query->where('available', true);
    }

    // tri
    if (!empty($filters['sort'])) {
      match ($filters['sort']) {

        'name_asc' => $query->orderBy('name'),
        'name_desc' => $query->orderByDesc('name'),

        'wine_type_asc' => $query->orderBy('wine_type'),
        'wine_type_desc' => $query->orderByDesc('wine_type'),

        'region_asc' => $query->orderBy('region'),
        'region_desc' => $query->orderByDesc('region'),

        'vintage_asc' => $query->orderBy('vintage'),
        'vintage_desc' => $query->orderByDesc('vintage'),

        'rating_asc' => $query->orderBy('rating'),
        'rating_desc' => $query->orderByDesc('rating'),

        'price_asc' => $query->orderBy('price'),
        'price_desc' => $query->orderByDesc('price'),

        'favorite' => $query->orderByDesc('favorite'),

        default => $query->latest(),
      };

    } else {
      $query->latest();
    }

    return $query;
  }

  // Supprimer une image du storage et de la bdd
  public function deleteImage(): void
  {
    if ($this->image_path) {
      Storage::disk('public')->delete($this->image_path);

      $this->image_path = null;
      $this->save();
    }
  }


}

























