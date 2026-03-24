<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Consultation::query()->latest('consulted_at')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pet_name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:100'],
            'owner_name' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'consulted_at' => ['required', 'date'],
        ]);

        $consultation = Consultation::query()->create($data);

        return response()->json(['data' => $consultation], 201);
    }

    public function show(Consultation $consultation): JsonResponse
    {
        return response()->json(['data' => $consultation]);
    }

    public function update(Request $request, Consultation $consultation): JsonResponse
    {
        $data = $request->validate([
            'pet_name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:100'],
            'owner_name' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'consulted_at' => ['required', 'date'],
        ]);

        $consultation->update($data);

        return response()->json(['data' => $consultation]);
    }

    public function destroy(Consultation $consultation): JsonResponse
    {
        $consultation->delete();

        return response()->json([], 204);
    }
}
