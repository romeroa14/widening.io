<?php

namespace App\Modules\Odontology\Models;

use App\Modules\Odontology\Enums\EstadoCita;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OdtCita extends Model
{
    use HasFactory;

    protected $table = 'odt_citas';

    protected $fillable = [
        'cliente_id',
        'servicio_id',
        'fecha',
        'hora',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'hora' => 'datetime:H:i',
            'estado' => EstadoCita::class,
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
        return $this->hasMany(OdtPago::class, 'cita_id');
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeProximas($query)
    {
        return $query->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->orderBy('hora');
    }

    public function scopeDelCliente($query, int $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', EstadoCita::PENDIENTE);
    }

    public function scopeConfirmadas($query)
    {
        return $query->where('estado', EstadoCita::CONFIRMADA);
    }
}
