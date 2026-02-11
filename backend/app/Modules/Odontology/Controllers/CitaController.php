<?php

namespace App\Modules\Odontology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Odontology\Enums\EstadoCita;
use App\Modules\Odontology\Models\OdtCita;
use App\Modules\Odontology\Requests\StoreCitaRequest;
use App\Modules\Odontology\Resources\CitaResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CitaController extends Controller
{
    public function proxima(int $clienteId): JsonResponse
    {
        $cita = OdtCita::with(['cliente', 'servicio'])
            ->delCliente($clienteId)
            ->proximas()
            ->first();

        if (!$cita) {
            return response()->json([
                'data' => null,
                'message' => 'No hay citas próximas para este cliente.',
            ], Response::HTTP_OK);
        }

        return response()->json([
            'data' => CitaResource::make($cita),
        ], Response::HTTP_OK);
    }

    public function store(StoreCitaRequest $request): JsonResponse
    {
        $cita = OdtCita::create([
            ...$request->validated(),
            'estado' => EstadoCita::PENDIENTE,
        ]);

        $cita->load(['cliente', 'servicio']);

        return response()->json([
            'data' => CitaResource::make($cita),
            'message' => 'Cita agendada exitosamente.',
        ], Response::HTTP_CREATED);
    }

    public function confirmar(int $id): JsonResponse
    {
        $cita = OdtCita::findOrFail($id);
        $cita->update(['estado' => EstadoCita::CONFIRMADA]);
        $cita->load(['cliente', 'servicio']);

        return response()->json([
            'data' => CitaResource::make($cita),
            'message' => 'Cita confirmada exitosamente.',
        ], Response::HTTP_OK);
    }

    public function cancelar(int $id): JsonResponse
    {
        $cita = OdtCita::findOrFail($id);
        $cita->update(['estado' => EstadoCita::CANCELADA]);
        $cita->load(['cliente', 'servicio']);

        return response()->json([
            'data' => CitaResource::make($cita),
            'message' => 'Cita cancelada.',
        ], Response::HTTP_OK);
    }
}
