<?php

namespace App\Modules\Odontology\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OdtPlanPago extends Model
{
    use HasFactory;

    protected $table = 'odt_planes_pago';

    protected $fillable = [
        'cliente_id',
        'servicio_id',
        'monto_mensual',
        'total_meses',
        'mes_actual',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'monto_mensual' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    // ─── Relaciones ───────────────────────────────────────

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(OdtCliente::class, 'cliente_id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(OdtServicio::class, 'servicio_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(OdtPago::class, 'plan_pago_id');
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDelCliente($query, int $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    // ─── Helpers ──────────────────────────────────────────

    public function getMesesRestantesAttribute(): int
    {
        return $this->total_meses - $this->mes_actual;
    }

    public function getEstaCompletoAttribute(): bool
    {
        return $this->mes_actual >= $this->total_meses;
    }
}
