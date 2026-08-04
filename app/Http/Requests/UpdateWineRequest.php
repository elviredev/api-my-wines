<?php

namespace App\Http\Requests;

use App\Enums\WineRegion;
use App\Enums\WineType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateWineRequest extends FormRequest
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
   * @return array<string, ValidationRule|array|string>
   */
  public function rules(): array
  {
    return [
      'name' => ['sometimes', 'string', 'max:255'],
      'appellation' => ['sometimes', 'string', 'max:255'],
      'domain' => ['sometimes', 'nullable', 'string', 'max:255'],

      'country' => ['sometimes', 'string'],
      'region' => ['sometimes', 'nullable', new Enum(WineRegion::class)],
      'grape' => ['sometimes', 'nullable', 'string', 'max:255'],

      'vintage' => ['sometimes', 'integer', 'min:1900', 'max:' . (now()->year + 1)],
      'wine_type' => ['sometimes', new Enum(WineType::class)],

      'price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
      'seller' => ['sometimes', 'nullable', 'string', 'max:255'],
      'purchase_date' => ['sometimes', 'nullable', 'date'],

      'rating' => ['sometimes', 'nullable', 'numeric', 'between:0,20'],

      'favorite' => ['sometimes', 'boolean'],
      'buy_again' => ['sometimes', 'nullable', 'boolean'],
      'available' => ['sometimes', 'boolean'],

      'description' => ['sometimes', 'nullable', 'string'],
      'nose' => ['sometimes', 'nullable', 'string'],
      'palate' => ['sometimes', 'nullable', 'string'],
      'pairings' => ['sometimes', 'nullable', 'array'],
      'pairings.*' => ['string', 'max:255'],

      'image' => ['sometimes', 'nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:2048'],
    ];
  }
}
