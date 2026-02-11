<?php

namespace App\Modules\Odontology\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'cita_id' => $this->cita_id,
            'monto' => (float) $this->monto,
            'metodo_pago' => $this->metodo_pago,
            'estado' => $this->estado,
            'fecha_pago' => $this->fecha_pago?->toISOString(),
            'cliente' => ClienteResource::make($this->whenLoaded('cliente')),
            'cita' => CitaResource::make($this->whenLoaded('cita')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
