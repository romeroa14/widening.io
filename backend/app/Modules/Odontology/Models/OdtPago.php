<?php

namespace App\Modules\Odontology\Models;

use App\Modules\Odontology\Enums\EstadoPago;
use App\Modules\Odontology\Enums\MetodoPago;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OdtPago extends Model
{
    use HasFactory;

    protected $table = 'odt_pagos';

    protected $fillable = [
        'cliente_id',
        'cita_id',
        'monto',
        'metodo_pago',
        'token_tarjeta',
        'estado',
        'fecha_pago',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'metodo_pago' => MetodoPago::class,
            'estado' => EstadoPago::class,
            'fecha_pago' => 'datetime',
        ];
    }

    // ─── Relaciones ───────────────────────────────────────

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(OdtCliente::class, 'cliente_id');
    }

    public function cita(): BelongsTo
    {
        return $this->belongsTo(OdtCita::class, 'cita_id');
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeAprobados($query)
    {
        return $query->where('estado', EstadoPago::APROBADO);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', EstadoPago::PENDIENTE);
    }

    public function scopeDelCliente($query, int $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }
}
