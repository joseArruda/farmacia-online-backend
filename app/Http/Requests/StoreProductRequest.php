<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:0|max:5000',
            'category' => 'required|string|min:0|max:30',
            'stock' => 'required|integer|min:0|max:100000',
            'price' => 'required|numeric|min:0|max:999999',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,webp|max:5120'
        ];
    }
}
