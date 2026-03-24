<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationPricingRule extends Model
{
    protected $fillable = [
        'species_id',
        'diagnosis',
        'default_cost',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_cost' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
}
