<?php

namespace App\Http\Requests\Book;

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
            'id' => ['required','exists:books,id'],
            'category_id' => ['required',Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'title' => ['required','string','max:255'],
            'cover' => ['sometimes','nullable','file','mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'author' => ['required','string','max:255'],
            'publication' => ['required','integer','between:1500,2025'],
            'synopsis' => ['required','string'],
            'edition' => ['required','string'],
            'stock' => ['required','integer','min:1'],
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
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no debe exceder los 255 caracteres.',
            'title.unique' => 'El título ya está en uso. Debe ser único.',
            'cover.file' => 'El archivo de la portada debe ser un archivo válido.',
            'cover.mimes' => 'La portada debe estar en uno de los siguientes formatos: jpg, jpeg, png, bmp, gif, svg, webp.',
            'author.required' => 'El autor es obligatorio.',
            'author.string' => 'El autor debe ser una cadena de texto.',
            'author.max' => 'El nombre del autor no debe exceder los 255 caracteres.',
            'publication.required' => 'El año de publicación es obligatorio.',
            'publication.integer' => 'El año de publicación debe ser un número entero.',
            'publication.between' => 'El año de publicación debe estar entre 1500 y 2025.',
            'synopsis.required' => 'La sinopsis es obligatoria.',
            'synopsis.string' => 'La sinopsis debe ser una cadena de texto.',
            'edition.required' => 'La edición es obligatoria.',
            'edition.string' => 'La edición debe ser una cadena de texto.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock debe ser al menos 1.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado solo permite: 0 o 1.'
        ];
    }
}
