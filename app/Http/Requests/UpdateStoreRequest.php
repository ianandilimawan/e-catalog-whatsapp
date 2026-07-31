<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
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
        $this->merge([
            'user_id' => $this->input('user_id') ?? auth()->id(),
            'name' => $this->input('name') !== null ? strip_tags($this->input('name')) : null,
            'welcome_message' => $this->input('welcome_message') !== null ? strip_tags($this->input('welcome_message')) : null,
            'slug' => $this->input('slug') ?: \Illuminate\Support\Str::slug($this->input('name')),
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
            'user_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'wa_number' => 'required',
            'theme_color' => 'nullable',
            'welcome_message' => 'nullable',
            'logo' => 'nullable',
            'banner' => 'nullable',
            'button_rounded' => 'nullable',
            'dark_mode' => 'nullable',
            'is_active' => 'nullable',
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
