<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odt_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 100);
            $table->string('telefono', 20)->unique();
            $table->string('email', 100)->nullable();
            $table->enum('tipo_cliente', ['nuevo', 'existente'])->default('nuevo');
            $table->date('fecha_registro')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('telefono');
            $table->index('tipo_cliente');  
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odt_clientes');
    }
};
