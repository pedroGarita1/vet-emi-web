<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EsteticaService extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'pet_name',
        'owner_name',
        'owner_phone',
        'owner_email',
        'service_type',
        'status',
        'notes',
        'requested_at',
        'ready_at',
        'notified_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'ready_at' => 'datetime',
            'notified_at' => 'datetime',
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(EsteticaServiceImage::class);
    }
}
