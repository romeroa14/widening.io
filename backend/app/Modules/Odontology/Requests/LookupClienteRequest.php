<?php

namespace App\Modules\Odontology\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LookupClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'telefono' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'telefono.required' => 'El número de teléfono es obligatorio.',
        ];
    }
}
