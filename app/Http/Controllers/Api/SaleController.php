<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Sale::query()->latest('sold_at')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'inventory_item_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'sold_at' => ['required', 'date'],
        ]);

        $data['total'] = (float) $data['quantity'] * (float) $data['unit_price'];

        $sale = Sale::query()->create($data);

        return response()->json(['data' => $sale], 201);
    }

    public function show(Sale $sale): JsonResponse
    {
        return response()->json(['data' => $sale]);
    }

    public function update(Request $request, Sale $sale): JsonResponse
    {
        $data = $request->validate([
            'inventory_item_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'sold_at' => ['required', 'date'],
        ]);

        $data['total'] = (float) $data['quantity'] * (float) $data['unit_price'];

        $sale->update($data);

        return response()->json(['data' => $sale]);
    }

    public function destroy(Sale $sale): JsonResponse
    {
        $sale->delete();

        return response()->json([], 204);
    }
}
