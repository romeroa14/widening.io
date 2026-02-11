<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odt_pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('cita_id')->nullable();
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['tarjeta', 'transferencia']);
            $table->string('token_tarjeta', 100)->nullable();
            $table->enum('estado', ['aprobado', 'rechazado', 'pendiente'])->default('pendiente');
            $table->dateTime('fecha_pago')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('odt_clientes')->cascadeOnDelete();
            $table->foreign('cita_id')->references('id')->on('odt_citas')->nullOnDelete();

            $table->index(['cliente_id', 'estado']);
            $table->index('fecha_pago');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odt_pagos');
    }
};
