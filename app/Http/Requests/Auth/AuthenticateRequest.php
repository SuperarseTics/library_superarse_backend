<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required','email:rfc'],
            'password' => ['required']
            // 'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'Debe proporcionar un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.'
            // 'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            // 'password.letters' => 'La contraseña debe contener al menos una letra.',
            // 'password.mixedCase' => 'La contraseña debe tener al menos una letra en mayúsculas y una en minúsculas.',
            // 'password.numbers' => 'La contraseña debe incluir al menos un número.',
            // 'password.symbols' => 'La contraseña debe contener al menos un símbolo especial.',
            // 'password.uncompromised' => 'La contraseña proporcionada aparece en una filtración de datos y no es segura. Por favor elija otra.',
        ];
    }
}
