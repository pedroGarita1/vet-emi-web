<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Consultation;
use App\Models\ConsultationImage;
use App\Models\ConsultationItem;
use App\Models\ConsultationPricingRule;
use App\Models\InventoryItem;
use App\Models\Pet;
use App\Models\Sale;
use App\Models\Species;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConsultasController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'species_id' => ['required', 'integer', 'exists:species,id'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'consulted_at' => ['required', 'date'],
            'vaccination_applied' => ['nullable', 'boolean'],
            'vaccination_note' => ['nullable', 'string', 'max:255'],
            'next_vaccination_at' => ['nullable', 'date'],
            'deworming_applied' => ['nullable', 'boolean'],
            'deworming_note' => ['nullable', 'string', 'max:255'],
            'next_deworming_at' => ['nullable', 'date'],
            'medications' => ['nullable', 'array'],
            'medications.*.inventory_item_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'medications.*.quantity' => ['nullable', 'integer', 'min:1'],
            'medications.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'medications.*.dosage' => ['nullable', 'string', 'max:255'],
            'medications.*.frequency_hours' => ['nullable', 'integer', 'min:1'],
            'medications.*.frequency_days' => ['nullable', 'integer', 'min:1'],
            'medications.*.duration_days' => ['nullable', 'integer', 'min:1'],
            'medications.*.administration_notes' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
        ]);

        $species = Species::findOrFail((int) $data['species_id']);
        $pet = Pet::findOrFail((int) $data['pet_id']);
        $data['species'] = $species->name;
        $data['pet_name'] = $pet->name;
        $data['owner_name'] = $data['owner_name'] ?: ($pet->owner_name ?: 'Sin propietario');
        $data['cost'] = $this->resolveConsultationCost((int) $data['species_id'], $data['diagnosis'], $data['cost'] ?? null);
        $data['vaccination_applied'] = $request->boolean('vaccination_applied');
        $data['deworming_applied'] = $request->boolean('deworming_applied');

        DB::transaction(function () use ($data, $request) {
            $consultation = Consultation::create($data);
            $this->syncConsultationItems($request, $consultation, false);
            $this->storeConsultationImages($request, $consultation);
        });

        return redirect()->route('consultations-listar')->with('success', 'Consulta registrada.');
    }

    public function update(Request $request, Consultation $consultation): RedirectResponse
    {
        $data = $request->validate([
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'species_id' => ['required', 'integer', 'exists:species,id'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'consulted_at' => ['required', 'date'],
            'vaccination_applied' => ['nullable', 'boolean'],
            'vaccination_note' => ['nullable', 'string', 'max:255'],
            'next_vaccination_at' => ['nullable', 'date'],
            'deworming_applied' => ['nullable', 'boolean'],
            'deworming_note' => ['nullable', 'string', 'max:255'],
            'next_deworming_at' => ['nullable', 'date'],
            'medications' => ['nullable', 'array'],
            'medications.*.inventory_item_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'medications.*.quantity' => ['nullable', 'integer', 'min:1'],
            'medications.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'medications.*.dosage' => ['nullable', 'string', 'max:255'],
            'medications.*.frequency_hours' => ['nullable', 'integer', 'min:1'],
            'medications.*.frequency_days' => ['nullable', 'integer', 'min:1'],
            'medications.*.duration_days' => ['nullable', 'integer', 'min:1'],
            'medications.*.administration_notes' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
        ]);

        $species = Species::findOrFail((int) $data['species_id']);
        $pet = Pet::findOrFail((int) $data['pet_id']);
        $data['species'] = $species->name;
        $data['pet_name'] = $pet->name;
        $data['owner_name'] = $data['owner_name'] ?: ($pet->owner_name ?: 'Sin propietario');
        $data['cost'] = $this->resolveConsultationCost((int) $data['species_id'], $data['diagnosis'], $data['cost'] ?? null);
        $data['vaccination_applied'] = $request->boolean('vaccination_applied');
        $data['deworming_applied'] = $request->boolean('deworming_applied');

        DB::transaction(function () use ($consultation, $data, $request) {
            $consultation->update($data);
            if ($request->has('medications')) {
                $this->syncConsultationItems($request, $consultation, true);
            }
            $this->storeConsultationImages($request, $consultation);
        });

        return redirect()->route('consultations-listar')->with('success', 'Consulta actualizada.');
    }

    public function destroy(Consultation $consultation): RedirectResponse
    {
        DB::transaction(function () use ($consultation) {
            $this->revertConsultationItemsAndSales($consultation);
            $consultation->delete();
        });
        return redirect()->route('consultations-listar')->with('success', 'Consulta eliminada.');
    }

    public function rescheduleDeworming(Request $request, Consultation $consultation): RedirectResponse
    {
        $data = $request->validate([
            'next_deworming_at' => ['required', 'date'],
            'deworming_note' => ['nullable', 'string', 'max:255'],
        ]);

        $consultation->update([
            'next_deworming_at' => $data['next_deworming_at'],
            'deworming_note' => $data['deworming_note'] ?? $consultation->deworming_note,
        ]);

        return redirect()->route('consultations-listar')->with('success', 'Desparasitacion reagendada correctamente.');
    }

    public function rescheduleVaccination(Request $request, Consultation $consultation): RedirectResponse
    {
        $data = $request->validate([
            'next_vaccination_at' => ['required', 'date'],
            'vaccination_note' => ['nullable', 'string', 'max:255'],
        ]);

        $consultation->update([
            'next_vaccination_at' => $data['next_vaccination_at'],
            'vaccination_note' => $data['vaccination_note'] ?? $consultation->vaccination_note,
        ]);

        return redirect()->route('consultations-listar')->with('success', 'Vacunacion reagendada correctamente.');
    }

    public function addImages(Request $request, Consultation $consultation): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['file', 'image', 'max:5120'], // 5MB por imagen
        ]);

        foreach ($request->file('images', []) as $image) {
            $path = $image->store('public/consultations');
            $relativePath = str_replace('public/', 'storage/', $path);
            ConsultationImage::create([
                'consultation_id' => $consultation->id,
                'image_path' => $relativePath,
            ]);
        }

        return back()->with('success', 'Imágenes agregadas correctamente.');
    }

    public function edit(Consultation $consultation): View
    {
        // Cargar catálogos igual que en VistasController@consultations
        $species = Species::where('is_active', true)->orderBy('name')->get();
        $pets = Pet::where('is_active', true)->with('species')->orderBy('name')->get();
        $inventoryItems = InventoryItem::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        $pricingRules = ConsultationPricingRule::with('species')->where('is_active', true)->orderBy('diagnosis')->get();

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
                'owner_email' => $pet->owner_email,
                'owner_phone' => $pet->owner_phone,
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
            'consultationEdit' => $consultation->load(['consultationItems', 'images']),
            'speciesCatalog' => $species,
            'petsCatalog' => $pets,
            'inventoryCatalog' => $inventoryItems,
            'pricingRules' => $pricingRules,
            'diagnosisCatalog' => $pricingRules->pluck('diagnosis')->unique()->values(),
            'pricingMap' => $pricingMap->toArray(),
            'petsJson' => $petsJson,
            'speciesJson' => $speciesJson,
            'inventoryJson' => $inventoryJson,
            'selectedPatientPetId' => 0,
            'patientHistory' => collect(),
        ]);
    }

    // --- Métodos auxiliares ---
    private function resolveConsultationCost(int $speciesId, string $diagnosis, $inputCost): float
    {
        if ($inputCost !== null && $inputCost !== '') {
            return (float) $inputCost;
        }
        $rule = ConsultationPricingRule::where('species_id', $speciesId)
            ->whereRaw('LOWER(diagnosis) = ?', [mb_strtolower(trim($diagnosis))])
            ->where('is_active', true)
            ->first();
        return $rule ? (float) $rule->default_cost : 0.0;
    }

    private function syncConsultationItems(Request $request, Consultation $consultation, bool $replaceExisting): void
    {
        if ($replaceExisting) {
            $this->revertConsultationItemsAndSales($consultation);
        }
        $rows = collect($request->input('medications', []))
            ->filter(function ($row) {
                return !empty($row['inventory_item_id']) && ((int)($row['quantity'] ?? 0)) > 0;
            })
            ->values();
        foreach ($rows as $row) {
            $inventoryItem = InventoryItem::findOrFail((int) $row['inventory_item_id']);
            $quantity = (int) $row['quantity'];
            $unitPrice = isset($row['unit_price']) && $row['unit_price'] !== '' ? (float) $row['unit_price'] : (float) $inventoryItem->unit_price;
            $subtotal = $quantity * $unitPrice;
            $sale = Sale::create([
                'inventory_item_id' => $inventoryItem->id,
                'product_name' => $inventoryItem->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $subtotal,
                'customer_name' => $consultation->owner_name,
                'sold_at' => $consultation->consulted_at,
            ]);
            $inventoryItem->decrement('stock', $quantity);
            ConsultationItem::create([
                'consultation_id' => $consultation->id,
                'inventory_item_id' => $inventoryItem->id,
                'sale_id' => $sale->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'dosage' => $row['dosage'] ?? null,
                'frequency_hours' => !empty($row['frequency_hours']) ? (int) $row['frequency_hours'] : null,
                'frequency_days' => !empty($row['frequency_days']) ? (int) $row['frequency_days'] : null,
                'duration_days' => !empty($row['duration_days']) ? (int) $row['duration_days'] : null,
                'administration_notes' => $row['administration_notes'] ?? null,
            ]);
        }
    }

    private function revertConsultationItemsAndSales(Consultation $consultation): void
    {
        $consultation->loadMissing('consultationItems.inventoryItem');
        foreach ($consultation->consultationItems as $item) {
            if ($item->inventoryItem) {
                $item->inventoryItem->increment('stock', (int) $item->quantity);
            }
            if ($item->sale_id) {
                Sale::where('id', $item->sale_id)->delete();
            }
            $item->delete();
        }
    }

    private function storeConsultationImages(Request $request, Consultation $consultation): void
    {
        foreach ($request->file('images', []) as $image) {
            $path = $image->store('public/consultations');
            $relativePath = str_replace('public/', 'storage/', $path);

            ConsultationImage::query()->create([
                'consultation_id' => $consultation->id,
                'image_path' => $relativePath,
            ]);
        }
    }
}
