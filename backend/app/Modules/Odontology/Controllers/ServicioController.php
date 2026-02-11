<?php

namespace App\Modules\Odontology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Odontology\Models\OdtServicio;
use App\Modules\Odontology\Resources\ServicioResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ServicioController extends Controller
{
    public function index(): JsonResponse
    {
        $servicios = OdtServicio::all();

        return response()->json([
            'data' => ServicioResource::collection($servicios),
        ], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $servicio = OdtServicio::findOrFail($id);

        return response()->json([
            'data' => ServicioResource::make($servicio),
        ], Response::HTTP_OK);
    }
}
