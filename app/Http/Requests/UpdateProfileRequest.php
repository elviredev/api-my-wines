<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
      'name' => ['required', 'string', 'min:3', 'max:50'],
      'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user()->id)],
      'avatar_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:2048'],
    ];
  }
}
