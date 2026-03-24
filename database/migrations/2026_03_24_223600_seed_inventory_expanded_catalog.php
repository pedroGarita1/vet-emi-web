<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        $items = [
            ['name' => 'Amoxicilina 500mg', 'category' => 'Medicamentos', 'stock' => 80, 'unit_price' => 12.50, 'min_stock' => 20],
            ['name' => 'Antiparasitario oral', 'category' => 'Medicamentos', 'stock' => 60, 'unit_price' => 18.00, 'min_stock' => 15],
            ['name' => 'Pomada dermatologica', 'category' => 'Medicamentos', 'stock' => 35, 'unit_price' => 27.00, 'min_stock' => 10],
            ['name' => 'Croquetas premium 10kg', 'category' => 'Alimentos', 'stock' => 25, 'unit_price' => 45.90, 'min_stock' => 8],
            ['name' => 'Sobre humedo felino', 'category' => 'Alimentos', 'stock' => 120, 'unit_price' => 2.80, 'min_stock' => 30],
            ['name' => 'Collar nylon mediano', 'category' => 'Accesorios', 'stock' => 40, 'unit_price' => 9.50, 'min_stock' => 12],
            ['name' => 'Correa reforzada', 'category' => 'Accesorios', 'stock' => 30, 'unit_price' => 14.20, 'min_stock' => 10],
            ['name' => 'Sueter canino talla M', 'category' => 'Ropa', 'stock' => 18, 'unit_price' => 21.00, 'min_stock' => 6],
            ['name' => 'Impermeable canino talla L', 'category' => 'Ropa', 'stock' => 12, 'unit_price' => 28.50, 'min_stock' => 4],
        ];

        foreach ($items as $item) {
            DB::table('inventory_items')->updateOrInsert(
                ['name' => $item['name']],
                [
                    'category' => $item['category'],
                    'stock' => $item['stock'],
                    'unit_price' => $item['unit_price'],
                    'min_stock' => $item['min_stock'],
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('inventory_items')->whereIn('name', [
            'Amoxicilina 500mg',
            'Antiparasitario oral',
            'Pomada dermatologica',
            'Croquetas premium 10kg',
            'Sobre humedo felino',
            'Collar nylon mediano',
            'Correa reforzada',
            'Sueter canino talla M',
            'Impermeable canino talla L',
        ])->delete();
    }
};
