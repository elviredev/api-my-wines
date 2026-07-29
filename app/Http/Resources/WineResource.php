<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
    return parent::toArray($request);
  }
}
