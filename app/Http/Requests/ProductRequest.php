<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'price' => ['required', 'numeric', 'min:0'],

            'stock' => ['required', 'integer', 'min:0'],

            'quantity' => ['required', 'integer', 'min:0'],

            'categories' => ['required', 'array'],

            'categories.*' => ['exists:categories,id'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'stock.integer' => 'Stock must be an integer.',
            'quantity.integer' => 'Quantity must be an integer.',
        ];
    }
}