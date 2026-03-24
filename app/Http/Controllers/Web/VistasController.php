<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationPricingRule;
use App\Models\InventoryItem;
use App\Models\Pet;
use App\Models\Sale;
use App\Models\Species;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VistasController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function dashboard(Request $request): View
    {
        $consultations = Consultation::query()->latest('consulted_at')->get();
        $sales = Sale::query()->latest('sold_at')->get();

        return view('dashboard', [
            'user' => $request->user(),
            'selectedSede' => session('selected_sede', 'Matriz'),
            'todayConsultations' => $consultations->filter(fn ($item) => $item->consulted_at && $item->consulted_at->isToday())->count(),
            'monthConsultations' => $consultations->filter(fn ($item) => $item->consulted_at && $item->consulted_at->isCurrentMonth())->count(),
            'monthRevenue' => (float) $consultations->filter(fn ($item) => $item->consulted_at && $item->consulted_at->isCurrentMonth())->sum('cost'),
            'avgConsultationCost' => (float) ($consultations->count() > 0 ? $consultations->avg('cost') : 0),
            'todaySales' => $sales->filter(fn ($item) => $item->sold_at && $item->sold_at->isToday())->count(),
            'todaySalesRevenue' => (float) $sales->filter(fn ($item) => $item->sold_at && $item->sold_at->isToday())->sum('total'),
            'monthSalesRevenue' => (float) $sales->filter(fn ($item) => $item->sold_at && $item->sold_at->isCurrentMonth())->sum('total'),
        ]);
    }

    public function inventory(): View
    {
        return view('modules.inventory.index', [
            'items' => InventoryItem::query()->orderBy('category')->orderBy('name')->get(),
            'speciesCatalog' => Species::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function sales(): View
    {
        $items = InventoryItem::query()->where('is_active', true)->orderBy('category')->orderBy('name')->get();

        $itemsJson = $items->map(function ($item) {
            $speciesIds = collect(explode(',', (string) $item->target_species))
                ->map(fn ($value) => (int) trim($value))
                ->filter(fn ($value) => $value > 0)
                ->values();

            return [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'presentation' => $item->presentation,
                'sale_unit' => $item->sale_unit,
                'unit_price' => (float) $item->unit_price,
                'target_species_ids' => $speciesIds,
            ];
        })->values();

        return view('modules.sales.index', [
            'sales' => Sale::query()->latest('sold_at')->get(),
            'items' => $items,
            'itemsJson' => $itemsJson,
            'speciesCatalog' => Species::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function consultations(Request $request): View
    {
        $species = Species::query()->where('is_active', true)->orderBy('name')->get();
        $pets = Pet::query()->where('is_active', true)->with('species')->orderBy('name')->get();
        $inventoryItems = InventoryItem::query()->where('is_active', true)->orderBy('category')->orderBy('name')->get();
        $pricingRules = ConsultationPricingRule::query()
            ->with('species')
            ->where('is_active', true)
            ->orderBy('diagnosis')
            ->get();

        $selectedPatientPetId = (int) $request->query('patient_pet_id', 0);
        $patientHistory = collect();

        if ($selectedPatientPetId > 0) {
            $patientHistory = Consultation::query()
                ->with(['petCatalog', 'consultationItems.inventoryItem'])
                ->where('pet_id', $selectedPatientPetId)
                ->latest('consulted_at')
                ->get();
        }

        $pricingMap = $pricingRules
            ->groupBy('species_id')
            ->map(function ($rules) {
                return $rules->mapWithKeys(function ($rule) {
                    return [mb_strtolower(trim($rule->diagnosis)) => (float) $rule->default_cost];
                });
            });

        $petsJson = $pets->map(function ($pet) {
            return [
                'id' => $pet->id,
                'name' => $pet->name,
                'owner_name' => $pet->owner_name,
                'breed' => $pet->breed,
                'size_category' => $pet->size_category,
                'species_id' => $pet->species_id,
            ];
        })->values();

        $speciesJson = $species->map(function ($speciesItem) {
            return [
                'id' => $speciesItem->id,
                'name' => $speciesItem->name,
            ];
        })->values();

        $inventoryJson = $inventoryItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'unit_price' => (float) $item->unit_price,
            ];
        })->values();

        return view('modules.consultations.index', [
            'consultations' => Consultation::query()->with(['petCatalog', 'consultationItems.inventoryItem'])->latest('consulted_at')->get(),
            'speciesCatalog' => $species,
            'petsCatalog' => $pets,
            'inventoryCatalog' => $inventoryItems,
            'pricingRules' => $pricingRules,
            'diagnosisCatalog' => $pricingRules->pluck('diagnosis')->unique()->values(),
            'pricingMap' => $pricingMap->toArray(),
            'petsJson' => $petsJson,
            'speciesJson' => $speciesJson,
            'inventoryJson' => $inventoryJson,
            'selectedPatientPetId' => $selectedPatientPetId,
            'patientHistory' => $patientHistory,
        ]);
    }
}
