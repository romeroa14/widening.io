<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OdontologySeeder extends Seeder
{
    public function run(): void
    {
        // ─── Servicios ────────────────────────────────────
        DB::table('odt_servicios')->insert([
            [
                'id' => 1,
                'nombre' => 'Consulta odontológica',
                'precio' => 1000.00,
                'tipo' => 'consulta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Ortodoncia – mantenimiento mensual',
                'precio' => 3000.00,
                'tipo' => 'recurrente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'Limpieza dental',
                'precio' => 2000.00,
                'tipo' => 'unico',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'Blanqueamiento',
                'precio' => 8500.00,
                'tipo' => 'unico',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nombre' => 'Endodoncia',
                'precio' => 6000.00,
                'tipo' => 'unico',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ─── Clientes ────────────────────────────────────
        DB::table('odt_clientes')->insert([
            [
                'id' => 1,
                'nombre' => 'María Rodríguez',
                'telefono' => '+18091234567',
                'email' => 'maria@gmail.com',
                'tipo_cliente' => 'nuevo',
                'fecha_registro' => '2026-03-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Juan Pérez',
                'telefono' => '+18099876543',
                'email' => 'juan@gmail.com',
                'tipo_cliente' => 'existente',
                'fecha_registro' => '2025-10-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'Ana Gómez',
                'telefono' => '+18095554444',
                'email' => 'ana@gmail.com',
                'tipo_cliente' => 'existente',
                'fecha_registro' => '2025-08-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ─── Citas ────────────────────────────────────────
        DB::table('odt_citas')->insert([
            [
                'id' => 1,
                'cliente_id' => 1,
                'servicio_id' => 1,
                'fecha' => '2026-03-20',
                'hora' => '10:00:00',
                'estado' => 'confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'cliente_id' => 2,
                'servicio_id' => 2,
                'fecha' => '2026-03-22',
                'hora' => '09:00:00',
                'estado' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'cliente_id' => 3,
                'servicio_id' => 3,
                'fecha' => '2026-03-25',
                'hora' => '15:00:00',
                'estado' => 'confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ─── Plan de Pago — Ortodoncia Juan ───────────────
        DB::table('odt_planes_pago')->insert([
            [
                'id' => 1,
                'cliente_id' => 2,
                'servicio_id' => 2,
                'monto_mensual' => 3000.00,
                'total_meses' => 18,
                'mes_actual' => 5,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ─── Pagos (tokens simulados) ─────────────────────
        DB::table('odt_pagos')->insert([
            [
                'id' => 1,
                'cliente_id' => 1,
                'cita_id' => 1,
                'monto' => 1000.00,
                'metodo_pago' => 'tarjeta',
                'token_tarjeta' => 'tok_test_visa_839201',
                'estado' => 'aprobado',
                'fecha_pago' => '2026-03-01 14:22:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'cliente_id' => 2,
                'cita_id' => 2,
                'monto' => 3000.00,
                'metodo_pago' => 'tarjeta',
                'token_tarjeta' => 'tok_test_master_554433',
                'estado' => 'aprobado',
                'fecha_pago' => '2026-02-22 09:10:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'cliente_id' => 3,
                'cita_id' => 3,
                'monto' => 2000.00,
                'metodo_pago' => 'tarjeta',
                'token_tarjeta' => 'tok_test_amex_778899',
                'estado' => 'aprobado',
                'fecha_pago' => '2026-03-10 16:45:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
