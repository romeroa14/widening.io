<?php

namespace App\Modules\Odontology\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'tipo_cliente' => $this->tipo_cliente,
            'fecha_registro' => $this->fecha_registro?->format('Y-m-d'),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
