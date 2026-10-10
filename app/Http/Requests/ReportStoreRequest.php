<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:8192',
            'type' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'La position GPS est indisponible. Activez la localisation et réessayez.',
            'longitude.required' => 'La position GPS est indisponible. Activez la localisation et réessayez.',
            'image.max' => 'La photo est trop lourde (8 Mo maximum).',
        ];
    }
}
