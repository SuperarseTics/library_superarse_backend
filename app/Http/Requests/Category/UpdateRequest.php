<?php

namespace App\Http\Requests\Category;

use Illuminate\Validation\Rule;
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
            'id' => ['required','exists:categories,id'],
            'title' => ['required','string','max:255',Rule::unique('categories', 'title')->ignore($this->id)],
            'status' => ['required','in:0,1']
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
            'id.required' => 'La categoria es obligatoria.',
            'id.exists' => 'La categoria seleccionada no existe.',
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no debe exceder los 255 caracteres.',
            'title.unique' => 'El título ya está en uso.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado solo permite: 0 o 1.'
        ];
    }
}
