<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'page' => ['required','numeric'],
            'size' => ['required','numeric','between:5,50'],
            'order' => ['required','in:title,status'],
            'sort' => ['required','in:asc,desc']
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
            'page.required' => 'El número de página es obligatorio.',
            'page.numeric' => 'El número de página debe ser un valor numérico.',
            'size.required' => 'El tamaño de la página es obligatorio.',
            'size.numeric' => 'El tamaño de la página debe ser un valor numérico.',
            'size.between' => 'El tamaño de la página debe estar entre 5 y 50.',
            'order.required' => 'El campo de ordenación es obligatorio.',
            'order.in' => 'El campo de ordenación debe ser uno de los siguientes: title, status.',
            'sort.required' => 'El criterio de ordenación es obligatorio.',
            'sort.in' => 'El criterio de ordenación debe ser asc o desc.'
        ];
    }
}
