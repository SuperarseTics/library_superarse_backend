<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class ReserveRequest extends FormRequest
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
            'book_code' => ['required','exists:books,code'],
            'booking_date' => ['required','date']
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
            'book_code.required' => 'El código del libro es obligatorio.',
            'book_code.exists' => 'El código del libro no existe en nuestra base de datos.',
            'booking_date.required' => 'La fecha de reserva es obligatoria.',
            'booking_date.date' => 'La fecha de reserva debe ser una fecha válida.'
        ];
    }
}
