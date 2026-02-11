<?php

namespace App\Modules\Odontology\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanPagoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'servicio_id' => $this->servicio_id,
            'monto_mensual' => (float) $this->monto_mensual,
            'total_meses' => $this->total_meses,
            'mes_actual' => $this->mes_actual,
            'meses_restantes' => $this->meses_restantes,
            'esta_completo' => $this->esta_completo,
            'activo' => $this->activo,
            'cliente' => ClienteResource::make($this->whenLoaded('cliente')),
            'servicio' => ServicioResource::make($this->whenLoaded('servicio')),
        ];
    }
}
