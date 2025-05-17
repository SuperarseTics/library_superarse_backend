<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'system' => ['required'],
            'notifications' => ['required'],
            'rules' => ['required']
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
            'system.required' => 'La configuración de sistema es obligatoria.',
            'notifications.required' => 'La configuración de notificaciones es obligatoria.',
            'rules.required' => 'La configuración de reglas es obligatoria.'
        ];
    }
}
