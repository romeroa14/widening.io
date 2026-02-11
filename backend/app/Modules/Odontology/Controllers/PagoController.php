<?php

namespace App\Modules\Odontology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Odontology\Enums\EstadoPago;
use App\Modules\Odontology\Models\OdtPago;
use App\Modules\Odontology\Requests\StorePagoRequest;
use App\Modules\Odontology\Resources\PagoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PagoController extends Controller
{
    public function store(StorePagoRequest $request): JsonResponse
    {
        $pago = OdtPago::create([
            ...$request->validated(),
            'estado' => EstadoPago::PENDIENTE,
            'token_tarjeta' => 'tok_test_' . Str::random(10),
        ]);

        $pago->load(['cliente', 'cita']);

        return response()->json([
            'data' => PagoResource::make($pago),
            'message' => 'Pago creado. Pendiente de confirmación.',
        ], Response::HTTP_CREATED);
    }

    public function confirmar(int $id): JsonResponse
    {
        $pago = OdtPago::findOrFail($id);
        $pago->update([
            'estado' => EstadoPago::APROBADO,
            'fecha_pago' => now(),
        ]);

        // Si el pago tiene cita asociada, confirmarla automáticamente
        if ($pago->cita_id) {
            $pago->cita->update(['estado' => 'confirmada']);
        }

        $pago->load(['cliente', 'cita']);

        return response()->json([
            'data' => PagoResource::make($pago),
            'message' => 'Pago confirmado exitosamente.',
        ], Response::HTTP_OK);
    }

    public function historial(int $clienteId): JsonResponse
    {
        $pagos = OdtPago::with(['cita.servicio'])
            ->delCliente($clienteId)
            ->orderByDesc('fecha_pago')
            ->get();

        return response()->json([
            'data' => PagoResource::collection($pagos),
        ], Response::HTTP_OK);
    }
}
