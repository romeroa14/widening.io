<?php

namespace App\Modules\Odontology\Models;

use App\Modules\Odontology\Enums\TipoServicio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OdtServicio extends Model
{
    use HasFactory;

    protected $table = 'odt_servicios';

    protected $fillable = [
        'nombre',
        'precio',
        'tipo',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'tipo' => TipoServicio::class,
        ];
    }

    // ─── Relaciones ───────────────────────────────────────

    public function citas(): HasMany
    {
        return $this->hasMany(OdtCita::class, 'servicio_id');
    }

    public function planesPago(): HasMany
    {
        return $this->hasMany(OdtPlanPago::class, 'servicio_id');
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopePorTipo($query, TipoServicio $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
