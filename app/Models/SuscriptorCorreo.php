<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuscriptorCorreo extends Model
{
    use HasFactory;

    protected $table = 'suscriptores_correo';

    protected $fillable = [
        'usuario_id',
        'correo',
        'suscrito',
        'tipo_avisos',
    ];

    protected $casts = [
        'suscrito' => 'boolean',
    ];

    /**
     * Relación con el usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener suscriptores activos para un tipo de aviso
     */
    public static function suscritosActivos($tipoAviso = null)
    {
        $query = self::where('suscrito', true);

        if ($tipoAviso) {
            $tipoSuscriptor = match ($tipoAviso) {
                'promocion' => 'promociones',
                'cierre' => 'cierres',
                default => null,
            };

            if ($tipoSuscriptor !== null) {
                $query->where(function ($q) use ($tipoSuscriptor) {
                    $q->where('tipo_avisos', 'todos')
                        ->orWhere('tipo_avisos', $tipoSuscriptor);
                });
            } else {
                $query->where('tipo_avisos', 'todos');
            }
        }

        return $query;
    }
}
