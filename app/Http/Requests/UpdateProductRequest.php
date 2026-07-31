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
        return true;
    }

    protected function prepareForValidation()
    {
        $rawPrice = $this->input('price');
        $cleanPrice = ($rawPrice !== null && $rawPrice !== '') ? (int) preg_replace('/\D/', '', (string) $rawPrice) : null;

        $this->merge([
            'store_id' => $this->input('store_id') ?? (auth()->user()->store->id ?? null),
            'name' => $this->input('name') !== null ? strip_tags($this->input('name')) : null,
            'description' => $this->input('description') !== null ? strip_tags($this->input('description')) : null,
            'slug' => $this->input('slug') ?: \Illuminate\Support\Str::slug($this->input('name')),
            'price' => $cleanPrice,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_id' => 'required',
            'category_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'nullable',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Add custom validation messages here
        ];
    }
}
