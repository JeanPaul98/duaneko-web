<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'first_name' => 'bail|required|min:2|max:30',
            'last_name' => 'required',
            'email' => 'nullable|email',
            'phone_number' => 'required|string|unique:users',
            'password' => 'bail|required|string|min:6|max:20|confirmed'
        ];
    }
}
