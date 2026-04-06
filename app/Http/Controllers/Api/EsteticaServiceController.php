<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EsteticaService;
use App\Models\EsteticaServiceImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EsteticaServiceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => EsteticaService::query()
                ->with('images')
                ->latest('requested_at')
                ->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pet_id' => ['nullable', 'integer', 'exists:pets,id'],
            'pet_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_phone' => ['nullable', 'string', 'max:30'],
            'owner_email' => ['nullable', 'email', 'max:255'],
            'service_type' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
            'requested_at' => ['required', 'date'],
            'status' => ['nullable', 'in:pendiente,en_proceso,lista,entregada'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
        ]);

        $service = DB::transaction(function () use ($data, $request) {
            $service = EsteticaService::query()->create([
                'pet_id' => $data['pet_id'] ?? null,
                'pet_name' => $data['pet_name'],
                'owner_name' => $data['owner_name'] ?? null,
                'owner_phone' => $data['owner_phone'] ?? null,
                'owner_email' => $data['owner_email'] ?? null,
                'service_type' => $data['service_type'],
                'status' => $data['status'] ?? 'pendiente',
                'notes' => $data['notes'] ?? null,
                'requested_at' => $data['requested_at'],
            ]);

            foreach ($request->file('images', []) as $image) {
                $path = $image->store('public/estetica');
                $relativePath = str_replace('public/', 'storage/', $path);

                EsteticaServiceImage::query()->create([
                    'estetica_service_id' => $service->id,
                    'image_path' => $relativePath,
                ]);
            }

            return $service;
        });

        return response()->json([
            'data' => $service->load('images'),
        ], 201);
    }
}
