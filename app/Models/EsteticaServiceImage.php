<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EsteticaServiceImage extends Model
{
    protected $fillable = [
        'estetica_service_id',
        'image_path',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(EsteticaService::class, 'estetica_service_id');
    }
}
