<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'stats' => $this->resource['stats'],

      'distribution' => $this->resource['distribution'],

      'infos' => $this->resource['infos'],
    ];
  }
}
