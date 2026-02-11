<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odt_planes_pago', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('servicio_id');
            $table->decimal('monto_mensual', 10, 2);
            $table->integer('total_meses');
            $table->integer('mes_actual')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('odt_clientes')->cascadeOnDelete();
            $table->foreign('servicio_id')->references('id')->on('odt_servicios')->cascadeOnDelete();

            $table->index(['cliente_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odt_planes_pago');
    }
};
