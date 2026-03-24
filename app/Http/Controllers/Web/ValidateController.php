<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationItem;
use App\Models\ConsultationPricingRule;
use App\Models\InventoryItem;
use App\Models\Pet;
use App\Models\Sale;
use App\Models\Species;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ValidateController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'sede' => ['nullable', 'string', 'max:100'],
        ]);

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return back()->withInput()->withErrors([
                'email' => 'Credenciales inválidas.',
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();
        $token = $user->createToken('emi-web-'.($credentials['sede'] ?? 'principal'))->plainTextToken;

        session([
            'api_token' => $token,
            'selected_sede' => $credentials['sede'] ?? 'Matriz',
        ]);

        return redirect()->route('vistas-inicio')->with('success', 'Bienvenido a Emi.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();
        $plainTextToken = (string) session('api_token', '');

        if ($user && str_contains($plainTextToken, '|')) {
            $tokenId = (int) explode('|', $plainTextToken)[0];
            $user->tokens()->where('id', $tokenId)->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada.');
    }

    public function storeInventory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'presentation' => ['nullable', 'string', 'max:100'],
            'sale_unit' => ['required', 'string', 'max:40'],
            'target_species' => ['nullable', 'array'],
            'target_species.*' => ['integer', 'exists:species,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['target_species'] = $this->normalizeTargetSpecies($request->input('target_species', []));

        InventoryItem::query()->create($data);

        return redirect()->route('inventario-listar')->with('success', 'Producto agregado al inventario.');
    }

    public function updateInventory(Request $request, InventoryItem $inventoryItem): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'presentation' => ['nullable', 'string', 'max:100'],
            'sale_unit' => ['required', 'string', 'max:40'],
            'target_species' => ['nullable', 'array'],
            'target_species.*' => ['integer', 'exists:species,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['target_species'] = $this->normalizeTargetSpecies($request->input('target_species', []));

        $inventoryItem->update($data);

        return redirect()->route('inventario-listar')->with('success', 'Producto actualizado.');
    }

    public function destroyInventory(InventoryItem $inventoryItem): RedirectResponse
    {
        $inventoryItem->delete();

        return redirect()->route('inventario-listar')->with('success', 'Producto eliminado.');
    }

    public function storeSale(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'inventory_item_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'sold_at' => ['required', 'date'],
        ]);

        $inventoryItem = null;
        if (! empty($data['inventory_item_id'])) {
            $inventoryItem = InventoryItem::query()->find((int) $data['inventory_item_id']);
        }

        if (empty($data['product_name']) && $inventoryItem) {
            $data['product_name'] = trim($inventoryItem->name.' '.((string) $inventoryItem->presentation));
        }

        if (($data['unit_price'] === null || $data['unit_price'] === '') && $inventoryItem) {
            $data['unit_price'] = (float) $inventoryItem->unit_price;
        }

        if (empty($data['product_name']) || $data['unit_price'] === null || $data['unit_price'] === '') {
            return back()->withInput()->withErrors([
                'product_name' => 'Selecciona un producto de inventario o captura nombre y precio.',
            ]);
        }

        $data['total'] = (float) $data['quantity'] * (float) $data['unit_price'];

        Sale::query()->create($data);

        return redirect()->route('sales-listar')->with('success', 'Venta registrada.');
    }

    public function updateSale(Request $request, Sale $sale): RedirectResponse
    {
        $data = $request->validate([
            'inventory_item_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'sold_at' => ['required', 'date'],
        ]);

        $inventoryItem = null;
        if (! empty($data['inventory_item_id'])) {
            $inventoryItem = InventoryItem::query()->find((int) $data['inventory_item_id']);
        }

        if (empty($data['product_name']) && $inventoryItem) {
            $data['product_name'] = trim($inventoryItem->name.' '.((string) $inventoryItem->presentation));
        }

        if (($data['unit_price'] === null || $data['unit_price'] === '') && $inventoryItem) {
            $data['unit_price'] = (float) $inventoryItem->unit_price;
        }

        if (empty($data['product_name']) || $data['unit_price'] === null || $data['unit_price'] === '') {
            return back()->withInput()->withErrors([
                'product_name' => 'Selecciona un producto de inventario o captura nombre y precio.',
            ]);
        }

        $data['total'] = (float) $data['quantity'] * (float) $data['unit_price'];

        $sale->update($data);

        return redirect()->route('sales-listar')->with('success', 'Venta actualizada.');
    }

    public function destroySale(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()->route('sales-listar')->with('success', 'Venta eliminada.');
    }

    public function storeConsultation(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'species_id' => ['required', 'integer', 'exists:species,id'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'consulted_at' => ['required', 'date'],
            'medications' => ['nullable', 'array'],
            'medications.*.inventory_item_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'medications.*.quantity' => ['nullable', 'integer', 'min:1'],
            'medications.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'medications.*.dosage' => ['nullable', 'string', 'max:255'],
            'medications.*.frequency_hours' => ['nullable', 'integer', 'min:1'],
            'medications.*.frequency_days' => ['nullable', 'integer', 'min:1'],
            'medications.*.duration_days' => ['nullable', 'integer', 'min:1'],
            'medications.*.administration_notes' => ['nullable', 'string'],
        ]);

        $species = Species::query()->findOrFail((int) $data['species_id']);
        $pet = Pet::query()->findOrFail((int) $data['pet_id']);

        $data['species'] = $species->name;
        $data['pet_name'] = $pet->name;
        $data['owner_name'] = $data['owner_name'] ?: ($pet->owner_name ?: 'Sin propietario');
        $data['cost'] = $this->resolveConsultationCost((int) $data['species_id'], $data['diagnosis'], $data['cost'] ?? null);

        DB::transaction(function () use ($data, $request): void {
            $consultation = Consultation::query()->create($data);
            $this->syncConsultationItems($request, $consultation, false);
        });

        return redirect()->route('consultations-listar')->with('success', 'Consulta registrada.');
    }

    public function updateConsultation(Request $request, Consultation $consultation): RedirectResponse
    {
        $data = $request->validate([
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'species_id' => ['required', 'integer', 'exists:species,id'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'consulted_at' => ['required', 'date'],
            'medications' => ['nullable', 'array'],
            'medications.*.inventory_item_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'medications.*.quantity' => ['nullable', 'integer', 'min:1'],
            'medications.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'medications.*.dosage' => ['nullable', 'string', 'max:255'],
            'medications.*.frequency_hours' => ['nullable', 'integer', 'min:1'],
            'medications.*.frequency_days' => ['nullable', 'integer', 'min:1'],
            'medications.*.duration_days' => ['nullable', 'integer', 'min:1'],
            'medications.*.administration_notes' => ['nullable', 'string'],
        ]);

        $species = Species::query()->findOrFail((int) $data['species_id']);
        $pet = Pet::query()->findOrFail((int) $data['pet_id']);

        $data['species'] = $species->name;
        $data['pet_name'] = $pet->name;
        $data['owner_name'] = $data['owner_name'] ?: ($pet->owner_name ?: 'Sin propietario');
        $data['cost'] = $this->resolveConsultationCost((int) $data['species_id'], $data['diagnosis'], $data['cost'] ?? null);

        DB::transaction(function () use ($consultation, $data, $request): void {
            $consultation->update($data);
            if ($request->has('medications')) {
                $this->syncConsultationItems($request, $consultation, true);
            }
        });

        return redirect()->route('consultations-listar')->with('success', 'Consulta actualizada.');
    }

    public function destroyConsultation(Consultation $consultation): RedirectResponse
    {
        DB::transaction(function () use ($consultation): void {
            $this->revertConsultationItemsAndSales($consultation);
            $consultation->delete();
        });

        return redirect()->route('consultations-listar')->with('success', 'Consulta eliminada.');
    }

    public function storeSpecies(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:species,name'],
        ]);

        Species::query()->create([
            'name' => $data['name'],
            'is_active' => true,
        ]);

        return redirect()->route('consultations-listar')->with('success', 'Especie agregada al catálogo.');
    }

    public function storePet(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'species_id' => ['required', 'integer', 'exists:species,id'],
            'breed' => ['nullable', 'string', 'max:120'],
            'size_category' => ['nullable', 'in:pequena,mediana,grande'],
        ]);

        $species = Species::query()->findOrFail((int) $data['species_id']);
        $speciesName = mb_strtolower(trim($species->name));
        $requiresBreed = str_contains($speciesName, 'canino') || str_contains($speciesName, 'perro') || str_contains($speciesName, 'ave');
        $requiresSize = str_contains($speciesName, 'canino')
            || str_contains($speciesName, 'perro')
            || str_contains($speciesName, 'felino')
            || str_contains($speciesName, 'gato');

        if ($requiresBreed && empty(trim((string) ($data['breed'] ?? '')))) {
            return back()->withInput()->withErrors([
                'breed' => 'El tipo es obligatorio para especies caninas o aves.',
            ]);
        }

        if ($requiresSize && empty($data['size_category'])) {
            return back()->withInput()->withErrors([
                'size_category' => 'Selecciona talla pequena, mediana o grande para caninos/felinos.',
            ]);
        }

        Pet::query()->create([
            'name' => $data['name'],
            'owner_name' => $data['owner_name'] ?? null,
            'breed' => $data['breed'] ?? null,
            'size_category' => $data['size_category'] ?? null,
            'species_id' => (int) $data['species_id'],
            'is_active' => true,
        ]);

        return redirect()->route('consultations-listar')->with('success', 'Mascota agregada al catálogo.');
    }

    public function storeConsultationPricingRule(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'species_id' => ['required', 'integer', 'exists:species,id'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'default_cost' => ['required', 'numeric', 'min:0'],
        ]);

        ConsultationPricingRule::query()->updateOrCreate(
            [
                'species_id' => (int) $data['species_id'],
                'diagnosis' => trim($data['diagnosis']),
            ],
            [
                'default_cost' => (float) $data['default_cost'],
                'is_active' => true,
            ]
        );

        return redirect()->route('consultations-listar')->with('success', 'Tarifa de consulta configurada.');
    }

    public function downloadConsultationPrescriptionPdf(Consultation $consultation): Response
    {
        $consultation->load(['petCatalog', 'consultationItems.inventoryItem']);

        $pdf = Pdf::loadView('modules.consultations.prescription-pdf', [
            'consultation' => $consultation,
        ]);

        return $pdf->download('receta-consulta-'.$consultation->id.'.pdf');
    }

    private function resolveConsultationCost(int $speciesId, string $diagnosis, mixed $inputCost): float
    {
        if ($inputCost !== null && $inputCost !== '') {
            return (float) $inputCost;
        }

        $rule = ConsultationPricingRule::query()
            ->where('species_id', $speciesId)
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
                return ! empty($row['inventory_item_id']) && ((int) ($row['quantity'] ?? 0)) > 0;
            })
            ->values();

        foreach ($rows as $row) {
            $inventoryItem = InventoryItem::query()->findOrFail((int) $row['inventory_item_id']);

            $quantity = (int) $row['quantity'];
            $unitPrice = isset($row['unit_price']) && $row['unit_price'] !== ''
                ? (float) $row['unit_price']
                : (float) $inventoryItem->unit_price;
            $subtotal = $quantity * $unitPrice;

            $sale = Sale::query()->create([
                'inventory_item_id' => $inventoryItem->id,
                'product_name' => $inventoryItem->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $subtotal,
                'customer_name' => $consultation->owner_name,
                'sold_at' => $consultation->consulted_at,
            ]);

            $inventoryItem->decrement('stock', $quantity);

            ConsultationItem::query()->create([
                'consultation_id' => $consultation->id,
                'inventory_item_id' => $inventoryItem->id,
                'sale_id' => $sale->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'dosage' => $row['dosage'] ?? null,
                'frequency_hours' => ! empty($row['frequency_hours']) ? (int) $row['frequency_hours'] : null,
                'frequency_days' => ! empty($row['frequency_days']) ? (int) $row['frequency_days'] : null,
                'duration_days' => ! empty($row['duration_days']) ? (int) $row['duration_days'] : null,
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
                Sale::query()->where('id', $item->sale_id)->delete();
            }

            $item->delete();
        }
    }

    private function normalizeTargetSpecies(array $speciesIds): ?string
    {
        $normalized = collect($speciesIds)
            ->map(fn ($value) => (int) $value)
            ->filter(fn ($value) => $value > 0)
            ->unique()
            ->values();

        return $normalized->isEmpty() ? null : $normalized->implode(',');
    }
}
