<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class RecordRequest extends FormRequest
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
            'order' => ['required','in:booking_date,delivery_date,giveback_date,status'],
            'sort' => ['required','in:asc,desc'],
            'f_category' => ['sometimes','nullable','string','exists:categories,name'],
            'f_code' => ['sometimes','nullable','string','exists:books,code']
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
            'f_code.string' => 'El filtro de código debe ser una cadena de texto.',
            'f_code.exists' => 'El código no existe.',
        ];
    }
}
