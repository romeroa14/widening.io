<?php

namespace App\Modules\Odontology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Odontology\Models\OdtPlanPago;
use App\Modules\Odontology\Resources\PlanPagoResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlanPagoController extends Controller
{
    public function delCliente(int $clienteId): JsonResponse
    {
        $plan = OdtPlanPago::with(['cliente', 'servicio'])
            ->delCliente($clienteId)
            ->activos()
            ->first();

        if (!$plan) {
            return response()->json([
                'data' => null,
                'message' => 'No hay plan de pagos activo para este cliente.',
            ], Response::HTTP_OK);
        }

        return response()->json([
            'data' => PlanPagoResource::make($plan),
        ], Response::HTTP_OK);
    }

    public function pagar(int $id): JsonResponse
    {
        $plan = OdtPlanPago::with(['cliente', 'servicio'])->findOrFail($id);

        if ($plan->esta_completo) {
            return response()->json([
                'message' => 'Este plan de pagos ya está completado.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $plan->increment('mes_actual');
        $plan->refresh();

        // Si completó todos los meses, desactivar el plan
        if ($plan->esta_completo) {
            $plan->update(['activo' => false]);
        }

        return response()->json([
            'data' => PlanPagoResource::make($plan),
            'message' => "Cuota {$plan->mes_actual} de {$plan->total_meses} pagada exitosamente.",
        ], Response::HTTP_OK);
    }
}
