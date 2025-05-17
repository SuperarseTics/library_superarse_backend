<?php

namespace App\Http\Requests\Book;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'category_id' => ['required',Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'code' => ['required','string','max:255',Rule::unique('books', 'code')->whereNull('deleted_at')],
            'title' => ['required','string','max:255'],
            'cover' => ['required','file','mimes:jpg,jpeg,png,bmp,gif,svg,webp'],
            'author' => ['required','string','max:255'],
            'publication' => ['required','integer','between:1500,2025'],
            'synopsis' => ['required','string'],
            'edition' => ['required','string'],
            'stock' => ['required','integer','min:1'],
            'status' => ['required','in:1']
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
            'code.required' => 'El código es obligatorio.',
            'code.string' => 'El código debe ser una cadena de texto.',
            'code.max' => 'El código no debe exceder los 255 caracteres.',
            'code.unique' => 'El código ya está en uso.',
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no debe exceder los 255 caracteres.',
            'cover.required' => 'La portada es obligatoria.',
            'cover.file' => 'La portada debe ser un archivo válido.',
            'cover.mimes' => 'La portada debe ser un archivo de tipo: jpg, jpeg, png, bmp, gif, svg, webp.',
            'author.required' => 'El autor es obligatorio.',
            'author.string' => 'El autor debe ser una cadena de texto.',
            'author.max' => 'El autor no debe exceder los 255 caracteres.',
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
            'status.in' => 'El estado solo permite: 1.'
        ];
    }
}
