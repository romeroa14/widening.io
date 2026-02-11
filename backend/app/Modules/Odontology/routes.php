<?php

use App\Modules\Odontology\Controllers\CitaController;
use App\Modules\Odontology\Controllers\ClienteController;
use App\Modules\Odontology\Controllers\PagoController;
use App\Modules\Odontology\Controllers\PlanPagoController;
use App\Modules\Odontology\Controllers\ServicioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Odontology API Routes
|--------------------------------------------------------------------------
|
| Endpoints para el Flowbot Odontológico WhatsApp.
| Prefijo: /api/v1/odt/
|
*/

Route::prefix('v1/odt')->group(function () {

    // ─── Clientes ─────────────────────────────────────────
    Route::post('/clientes/lookup', [ClienteController::class, 'lookup']);
    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::get('/clientes/{id}', [ClienteController::class, 'show']);

    // ─── Servicios ────────────────────────────────────────
    Route::get('/servicios', [ServicioController::class, 'index']);
    Route::get('/servicios/{id}', [ServicioController::class, 'show']);

    // ─── Citas ────────────────────────────────────────────
    Route::get('/citas/proxima/{clienteId}', [CitaController::class, 'proxima']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::patch('/citas/{id}/confirmar', [CitaController::class, 'confirmar']);
    Route::patch('/citas/{id}/cancelar', [CitaController::class, 'cancelar']);

    // ─── Pagos ────────────────────────────────────────────
    Route::post('/pagos', [PagoController::class, 'store']);
    Route::patch('/pagos/{id}/confirmar', [PagoController::class, 'confirmar']);
    Route::get('/pagos/cliente/{clienteId}', [PagoController::class, 'historial']);

    // ─── Planes de Pago ───────────────────────────────────
    Route::get('/planes-pago/cliente/{clienteId}', [PlanPagoController::class, 'delCliente']);
    Route::post('/planes-pago/{id}/pagar', [PlanPagoController::class, 'pagar']);
});
