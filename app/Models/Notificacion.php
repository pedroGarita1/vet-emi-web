<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'creada_por',
        'cantidad_enviadas',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'activa' => 'boolean',
    ];

    /**
     * Relación con el usuario que creó la notificación (admin)
     */
    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

    /**
     * Obtener notificaciones activas y vigentes
     */
    public static function activas()
    {
        return self::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', now());
            })
            ->latest('created_at');
    }

    /**
     * Obtener notificaciones para mostrar en móvil
     */
    public static function paraMovil()
    {
        return self::activas()->limit(10);
    }

    /**
     * Marcar notificación como enviada
     */
    public function marcarEnviada(int $cantidad = 1): void
    {
        $this->increment('cantidad_enviadas', max(0, $cantidad));
    }
}
