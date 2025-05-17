<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class CatalogRequest extends FormRequest
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
            'order' => ['required','in:category,title,status'],
            'sort' => ['required','in:asc,desc'],
            'f_category' => ['sometimes','nullable','string','exists:categories,title'],
            'f_author' => ['sometimes','nullable','string'],
            'f_publication' => ['sometimes','nullable','numeric']
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
            'order.in' => 'El campo de ordenación debe ser uno de los siguientes: category, title, status.',
            'sort.required' => 'El criterio de ordenación es obligatorio.',
            'sort.in' => 'El criterio de ordenación debe ser asc o desc.',
            'f_category.string' => 'El filtro de categoría debe ser una cadena de texto.',
            'f_category.exists' => 'La categoría seleccionada no existe.',
            'f_author.string' => 'El filtro de autor debe ser una cadena de texto.',
            'f_publication.numeric' => 'El filtro de año de publicación debe ser un valor numérico.',
        ];
    }
}
