<?php

namespace App\Http\Requests;

use App\Enums\WineRegion;
use App\Enums\WineType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class IndexWineRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'per_page' => ['sometimes', 'integer', 'in:8,18,50,100'],
      'page' => ['sometimes', 'integer', 'min:1'],

      'search' => ['sometimes', 'string', 'max:255'],

      'vintage' => ['sometimes', 'integer', 'min:1900', 'max:' .(now()->year + 1)],

      'region' => ['sometimes', new Enum(WineRegion::class)],

      'wine_types' => ['sometimes', 'array'],
      'wine_types.*' => [new Enum(WineType::class)],

      'min_price' => ['sometimes', 'numeric', 'min:0'],

      'min_rating' => ['sometimes', 'numeric', 'between:0,20'],

      'favorite' => ['sometimes', 'boolean'],

      'available' => ['sometimes', 'boolean'],

      // tri
      'sort' => [
        'sometimes',
        'string',
        'in:name_asc,name_desc,vintage_desc,vintage_asc,price_desc,price_asc,rating_desc,rating_asc',
      ],
    ];
  }
}
