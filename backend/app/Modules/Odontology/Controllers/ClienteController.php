<?php

namespace App\Modules\Odontology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Odontology\Models\OdtCliente;
use App\Modules\Odontology\Requests\LookupClienteRequest;
use App\Modules\Odontology\Requests\StoreClienteRequest;
use App\Modules\Odontology\Resources\ClienteResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ClienteController extends Controller
{
    public function lookup(LookupClienteRequest $request): JsonResponse
    {
        $cliente = OdtCliente::porTelefono($request->telefono)->first();

        if (!$cliente) {
            return response()->json([
                'existe' => false,
                'message' => 'Cliente no encontrado.',
            ], Response::HTTP_OK);
        }

        return response()->json([
            'existe' => true,
            'data' => ClienteResource::make($cliente),
        ], Response::HTTP_OK);
    }

    public function store(StoreClienteRequest $request): JsonResponse
    {
        $cliente = OdtCliente::create([
            ...$request->validated(),
            'tipo_cliente' => 'nuevo',
            'fecha_registro' => now()->toDateString(),
        ]);

        return response()->json([
            'data' => ClienteResource::make($cliente),
            'message' => 'Cliente registrado exitosamente.',
        ], Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $cliente = OdtCliente::with(['citas.servicio', 'planesPago.servicio'])->findOrFail($id);

        return response()->json([
            'data' => ClienteResource::make($cliente),
        ], Response::HTTP_OK);
    }
}
