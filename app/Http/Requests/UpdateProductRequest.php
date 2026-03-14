<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|min:0|max:5000',
            'category' => 'sometimes|required|string|min:0|max:30',
            'stock' => 'sometimes|required|integer|min:0|max:100000',
            'price' => 'sometimes|required|numeric|min:0|max:999999',
            'image' => 'sometimes|required|file|image|mimes:jpeg,png,jpg,webp|max:5120'
        ];
    }
}
