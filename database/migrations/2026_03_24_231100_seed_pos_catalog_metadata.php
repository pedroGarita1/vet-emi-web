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

        $speciesMap = DB::table('species')->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim($name)) => (int) $id]);

        $resolveSpecies = function (array $names) use ($speciesMap): ?string {
            $ids = collect($names)
                ->map(fn ($name) => mb_strtolower(trim($name)))
                ->map(fn ($name) => $speciesMap[$name] ?? null)
                ->filter()
                ->unique()
                ->values();

            return $ids->isEmpty() ? null : $ids->implode(',');
        };

        $items = [
            ['name' => 'Croquetas premium 1 kg', 'category' => 'Alimentos', 'presentation' => 'Bolsa 1 kg', 'sale_unit' => 'kg', 'target_species' => $resolveSpecies(['Canino', 'Felino']), 'stock' => 60, 'unit_price' => 110.00, 'min_stock' => 10],
            ['name' => 'Croquetas premium 500 g', 'category' => 'Alimentos', 'presentation' => 'Bolsa 500 g', 'sale_unit' => 'kg', 'target_species' => $resolveSpecies(['Canino', 'Felino']), 'stock' => 80, 'unit_price' => 62.00, 'min_stock' => 12],
            ['name' => 'Croquetas costal 20 kg', 'category' => 'Alimentos', 'presentation' => 'Bulto 20 kg', 'sale_unit' => 'bulto', 'target_species' => $resolveSpecies(['Canino']), 'stock' => 12, 'unit_price' => 1650.00, 'min_stock' => 3],
            ['name' => 'Sobre humedo premium', 'category' => 'Alimentos', 'presentation' => 'Sobre 85 g', 'sale_unit' => 'sobre', 'target_species' => $resolveSpecies(['Felino', 'Canino']), 'stock' => 140, 'unit_price' => 22.00, 'min_stock' => 30],
            ['name' => 'Shampoo antipulgas', 'category' => 'Higiene', 'presentation' => 'Frasco 350 ml', 'sale_unit' => 'frasco', 'target_species' => $resolveSpecies(['Canino', 'Felino']), 'stock' => 45, 'unit_price' => 135.00, 'min_stock' => 10],
            ['name' => 'Shampoo piel sensible', 'category' => 'Higiene', 'presentation' => 'Frasco 500 ml', 'sale_unit' => 'frasco', 'target_species' => $resolveSpecies(['Canino', 'Felino']), 'stock' => 38, 'unit_price' => 152.00, 'min_stock' => 8],
            ['name' => 'Pasta dental canina', 'category' => 'Higiene', 'presentation' => 'Tubo 100 g', 'sale_unit' => 'tubo', 'target_species' => $resolveSpecies(['Canino']), 'stock' => 26, 'unit_price' => 98.00, 'min_stock' => 6],
            ['name' => 'Amoxicilina 500 mg', 'category' => 'Medicamentos', 'presentation' => 'Caja 10 tabletas', 'sale_unit' => 'caja', 'target_species' => null, 'stock' => 70, 'unit_price' => 125.00, 'min_stock' => 15],
            ['name' => 'Antiparasitario oral', 'category' => 'Medicamentos', 'presentation' => 'Tableta', 'sale_unit' => 'tableta', 'target_species' => null, 'stock' => 90, 'unit_price' => 38.00, 'min_stock' => 20],
            ['name' => 'Collar nylon mediano', 'category' => 'Accesorios', 'presentation' => 'Pieza', 'sale_unit' => 'pieza', 'target_species' => $resolveSpecies(['Canino']), 'stock' => 40, 'unit_price' => 95.00, 'min_stock' => 10],
            ['name' => 'Cadena reforzada', 'category' => 'Accesorios', 'presentation' => 'Pieza', 'sale_unit' => 'pieza', 'target_species' => $resolveSpecies(['Canino']), 'stock' => 35, 'unit_price' => 185.00, 'min_stock' => 8],
            ['name' => 'Cama mediana impermeable', 'category' => 'Camas', 'presentation' => 'Pieza', 'sale_unit' => 'pieza', 'target_species' => $resolveSpecies(['Canino', 'Felino']), 'stock' => 20, 'unit_price' => 540.00, 'min_stock' => 5],
            ['name' => 'Ropa canina talla M', 'category' => 'Ropa', 'presentation' => 'Pieza', 'sale_unit' => 'pieza', 'target_species' => $resolveSpecies(['Canino']), 'stock' => 28, 'unit_price' => 230.00, 'min_stock' => 6],
        ];

        foreach ($items as $item) {
            DB::table('inventory_items')->updateOrInsert(
                ['name' => $item['name']],
                [
                    'category' => $item['category'],
                    'presentation' => $item['presentation'],
                    'sale_unit' => $item['sale_unit'],
                    'target_species' => $item['target_species'],
                    'stock' => $item['stock'],
                    'unit_price' => $item['unit_price'],
                    'min_stock' => $item['min_stock'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
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
            'Croquetas premium 1 kg',
            'Croquetas premium 500 g',
            'Croquetas costal 20 kg',
            'Sobre humedo premium',
            'Shampoo antipulgas',
            'Shampoo piel sensible',
            'Pasta dental canina',
            'Amoxicilina 500 mg',
            'Antiparasitario oral',
            'Collar nylon mediano',
            'Cadena reforzada',
            'Cama mediana impermeable',
            'Ropa canina talla M',
        ])->delete();
    }
};
