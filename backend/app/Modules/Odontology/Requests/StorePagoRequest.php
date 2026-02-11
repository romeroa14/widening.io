<?php

namespace App\Modules\Odontology\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'integer', 'exists:odt_clientes,id'],
            'cita_id' => ['nullable', 'integer', 'exists:odt_citas,id'],
            'monto' => ['required', 'numeric', 'min:1'],
            'metodo_pago' => ['required', 'in:tarjeta,transferencia'],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.exists' => 'El cliente no existe.',
            'cita_id.exists' => 'La cita no existe.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'metodo_pago.in' => 'El método de pago debe ser tarjeta o transferencia.',
        ];
    }
}
