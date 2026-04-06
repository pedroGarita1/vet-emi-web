<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationImage;
use App\Models\Pet;
use App\Models\Species;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Consultation::query()
                ->with(['images', 'petCatalog'])
                ->latest('consulted_at')
                ->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pet_id' => ['nullable', 'integer', 'exists:pets,id'],
            'species_id' => ['nullable', 'integer', 'exists:species,id'],
            'pet_name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:100'],
            'owner_name' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'consulted_at' => ['required', 'date'],
            'vaccination_applied' => ['nullable', 'boolean'],
            'vaccination_note' => ['nullable', 'string', 'max:255'],
            'next_vaccination_at' => ['nullable', 'date'],
            'deworming_applied' => ['nullable', 'boolean'],
            'deworming_note' => ['nullable', 'string', 'max:255'],
            'next_deworming_at' => ['nullable', 'date'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
        ]);

        $data = $this->hydrateCatalogIds($data);
        $data['vaccination_applied'] = $request->boolean('vaccination_applied');
        $data['deworming_applied'] = $request->boolean('deworming_applied');

        $consultation = DB::transaction(function () use ($data, $request) {
            $consultation = Consultation::query()->create($data);

            foreach ($request->file('images', []) as $image) {
                $path = $image->store('public/consultations');
                $relativePath = str_replace('public/', 'storage/', $path);

                ConsultationImage::query()->create([
                    'consultation_id' => $consultation->id,
                    'image_path' => $relativePath,
                ]);
            }

            return $consultation;
        });

        return response()->json(['data' => $consultation->load(['images', 'petCatalog'])], 201);
    }

    public function show(Consultation $consultation): JsonResponse
    {
        return response()->json(['data' => $consultation->load(['images', 'petCatalog'])]);
    }

    public function update(Request $request, Consultation $consultation): JsonResponse
    {
        $data = $request->validate([
            'pet_id' => ['nullable', 'integer', 'exists:pets,id'],
            'species_id' => ['nullable', 'integer', 'exists:species,id'],
            'pet_name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:100'],
            'owner_name' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'consulted_at' => ['required', 'date'],
            'vaccination_applied' => ['nullable', 'boolean'],
            'vaccination_note' => ['nullable', 'string', 'max:255'],
            'next_vaccination_at' => ['nullable', 'date'],
            'deworming_applied' => ['nullable', 'boolean'],
            'deworming_note' => ['nullable', 'string', 'max:255'],
            'next_deworming_at' => ['nullable', 'date'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
        ]);

        $data = $this->hydrateCatalogIds($data);
        $data['vaccination_applied'] = $request->boolean('vaccination_applied');
        $data['deworming_applied'] = $request->boolean('deworming_applied');

        DB::transaction(function () use ($consultation, $data, $request): void {
            $consultation->update($data);

            foreach ($request->file('images', []) as $image) {
                $path = $image->store('public/consultations');
                $relativePath = str_replace('public/', 'storage/', $path);

                ConsultationImage::query()->create([
                    'consultation_id' => $consultation->id,
                    'image_path' => $relativePath,
                ]);
            }
        });

        return response()->json(['data' => $consultation->load(['images', 'petCatalog'])]);
    }

    public function destroy(Consultation $consultation): JsonResponse
    {
        $consultation->delete();

        return response()->json([], 204);
    }

    private function hydrateCatalogIds(array $data): array
    {
        if (empty($data['pet_id'])) {
            $pet = Pet::query()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $data['pet_name']))])
                ->when(!empty($data['owner_name']), function ($query) use ($data) {
                    $query->whereRaw('LOWER(owner_name) = ?', [mb_strtolower(trim((string) $data['owner_name']))]);
                })
                ->first();

            if ($pet) {
                $data['pet_id'] = $pet->id;
                $data['species_id'] = $data['species_id'] ?? $pet->species_id;
            }
        }

        if (empty($data['species_id']) && !empty($data['species'])) {
            $species = Species::query()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $data['species']))])
                ->first();

            if ($species) {
                $data['species_id'] = $species->id;
            }
        }

        return $data;
    }
}
