<?php

namespace App\Http\Requests;

use App\Enums\WineRegion;
use App\Enums\WineType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreWineRequest extends FormRequest
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
      'name' => ['required', 'string', 'max:255'],
      'appellation' => ['required', 'string', 'max:255'],
      'domain' => ['nullable', 'string', 'max:255'],

      'country' => ['required', 'string'],
      'region' => ['nullable', new Enum(WineRegion::class)],
      'grape' => ['nullable', 'string', 'max:255'],

      'vintage' => ['required', 'integer', 'min:1900', 'max:' . (now()->year + 1)],
      'wine_type' => ['required', new Enum(WineType::class)],

      'price' => ['nullable', 'numeric', 'min:0'],
      'seller' => ['nullable', 'string', 'max:255'],
      'purchase_date' => ['nullable', 'date'],

      'rating' => ['nullable', 'numeric', 'between:0,20'],

      'favorite' => ['required', 'boolean'],
      'buy_again' => ['nullable', 'boolean'],
      'available' => ['required', 'boolean'],

      'description' => ['nullable', 'string'],
      'nose' => ['nullable', 'string'],
      'palate' => ['nullable', 'string'],
      'pairings' => ['nullable', 'array'],
      'pairings.*' => ['string', 'max:255'],

      'image_path' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:2048'],
    ];
  }
}
