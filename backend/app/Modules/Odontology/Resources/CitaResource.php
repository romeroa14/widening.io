<?php

namespace App\Modules\Odontology\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'servicio_id' => $this->servicio_id,
            'fecha' => $this->fecha?->format('Y-m-d'),
            'hora' => $this->hora,
            'estado' => $this->estado,
            'cliente' => ClienteResource::make($this->whenLoaded('cliente')),
            'servicio' => ServicioResource::make($this->whenLoaded('servicio')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
