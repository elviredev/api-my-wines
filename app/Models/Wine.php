<?php

namespace App\Models;

use App\Enums\WineRegion;
use App\Enums\WineType;
use Database\Factories\WineFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\Attributes\Sluggable;


#[Fillable(['name', 'vintage', 'grape', 'country', 'region', 'description', 'appellation', 'slug', 'domain', 'wine_type', 'price', 'seller', 'purchase_date',
  'rating', 'buy_again', 'available', 'favorite', 'image_path', 'nose', 'palate', 'pairings'])]
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

}
