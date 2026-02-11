<?php

namespace App\Modules\Odontology\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'max:20', 'unique:odt_clientes,telefono'],
            'email' => ['nullable', 'email', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del paciente es obligatorio.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'telefono.unique' => 'Este número de teléfono ya está registrado.',
            'email.email' => 'El email no tiene un formato válido.',
        ];
    }
}
