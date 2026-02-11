<?php

namespace App\Modules\Odontology\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'integer', 'exists:odt_clientes,id'],
            'servicio_id' => ['required', 'integer', 'exists:odt_servicios,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.exists' => 'El cliente no existe.',
            'servicio_id.exists' => 'El servicio no existe.',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o en el futuro.',
            'hora.date_format' => 'La hora debe tener formato HH:MM.',
        ];
    }
}
