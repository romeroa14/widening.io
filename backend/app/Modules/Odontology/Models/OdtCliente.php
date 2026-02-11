<?php

namespace App\Modules\Odontology\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdtCliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'odt_clientes';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'tipo_cliente',
        'fecha_registro',
    ];

    protected function casts(): array
    {
        return [
            'fecha_registro' => 'date',
        ];
    }

    // ─── Relaciones ───────────────────────────────────────

    public function citas(): HasMany
    {
        return $this->hasMany(OdtCita::class, 'cliente_id');
    }

    public function planesPago(): HasMany
    {
        return $this->hasMany(OdtPlanPago::class, 'cliente_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(OdtPago::class, 'cliente_id');
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopePorTelefono($query, string $telefono)
    {
        return $query->where('telefono', $telefono);
    }

    public function scopeNuevos($query)
    {
        return $query->where('tipo_cliente', 'nuevo');
    }

    public function scopeExistentes($query)
    {
        return $query->where('tipo_cliente', 'existente');
    }
}
