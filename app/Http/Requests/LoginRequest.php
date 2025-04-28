<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre de usuario es obligatorio',
            'password.required' => 'La contraseña es obligatoria'
        ];
    }
}
