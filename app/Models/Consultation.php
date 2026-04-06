<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultation extends Model
{
    use HasFactory;
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
        'vaccination_applied',
        'vaccination_note',
        'next_vaccination_at',
        'deworming_applied',
        'deworming_note',
        'next_deworming_at',
    ];

    protected function casts(): array
    {
        return [
            'consulted_at' => 'datetime',
            'vaccination_applied' => 'boolean',
            'deworming_applied' => 'boolean',
            'next_vaccination_at' => 'date',
            'next_deworming_at' => 'date',
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
    public function images(): HasMany
    {
        return $this->hasMany(ConsultationImage::class);
    }
}
