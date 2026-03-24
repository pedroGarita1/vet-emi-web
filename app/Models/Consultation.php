<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultation extends Model
{
    protected $fillable = [
        'species_id',
        'pet_id',
        'pet_name',
        'species',
        'owner_name',
        'diagnosis',
        'treatment',
        'cost',
        'consulted_at',
    ];

    protected function casts(): array
    {
        return [
            'consulted_at' => 'datetime',
        ];
    }

    public function speciesCatalog(): BelongsTo
    {
        return $this->belongsTo(Species::class, 'species_id');
    }

    public function petCatalog(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function consultationItems(): HasMany
    {
        return $this->hasMany(ConsultationItem::class);
    }
}
