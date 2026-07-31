<?php

namespace App\Http\Resources;

use App\Models\Wine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Wine
 */
class WineResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   * Permet de contrôler le JSON renvoyé à React
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,

      'name' => $this->name,
      'slug' => $this->slug,
      'appellation' => $this->appellation,
      'domain' => $this->domain,

      'country' => $this->country,
      'region' => $this->region?->value,
      'grape' => $this->grape,

      'vintage' => $this->vintage,
      'wine_type' => $this->wine_type->value,

      'price' => $this->price,
      'seller' => $this->seller,
      'purchase_date' => $this->purchase_date?->format('Y-m-d'),

      'rating' => $this->rating,

      'favorite' => $this->favorite,
      'buy_again' => $this->buy_again,
      'available' => $this->available,

      'description' => $this->description,
      'nose' => $this->nose,
      'palate' => $this->palate,
      'pairings' => $this->pairings,

      'image_path' => $this->image_path,

      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,

      'links' => [
        'self' => route('wines.show', $this->slug),
      ]
    ];
  }
}
