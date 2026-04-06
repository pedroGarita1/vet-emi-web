<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConsultationPricingRule;
use App\Models\Pet;
use App\Models\Species;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function consultationsData(): JsonResponse
    {
        $species = Species::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $pets = Pet::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'owner_name',
                'owner_email',
                'owner_phone',
                'breed',
                'size_category',
                'species_id',
            ]);

        $pricingRules = ConsultationPricingRule::query()
            ->where('is_active', true)
            ->orderBy('diagnosis')
            ->get(['species_id', 'diagnosis', 'default_cost']);

        return response()->json([
            'data' => [
                'species' => $species,
                'pets' => $pets,
                'pricing_rules' => $pricingRules,
            ],
        ]);
    }
}
