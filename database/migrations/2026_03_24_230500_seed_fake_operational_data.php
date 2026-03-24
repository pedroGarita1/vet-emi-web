<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        $users = [
            ['name' => 'Usuario Demo Emi', 'email' => 'demo@emi.com', 'password' => 'EmiVet123*'],
            ['name' => 'Dra. Laura Medina', 'email' => 'laura.medina@emi.com', 'password' => 'EmiVet123*'],
            ['name' => 'Recepcion Emi', 'email' => 'recepcion@emi.com', 'password' => 'EmiVet123*'],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $species = [
            'Canino',
            'Felino',
            'Conejo',
            'Ave',
            'Hamster',
            'Tortuga',
        ];

        foreach ($species as $name) {
            DB::table('species')->updateOrInsert(
                ['name' => $name],
                ['is_active' => true, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $speciesByName = DB::table('species')->pluck('id', 'name');

        $pets = [
            ['name' => 'Luna', 'owner_name' => 'Pedro Ramos', 'species' => 'Canino', 'breed' => 'Pastor Aleman'],
            ['name' => 'Max', 'owner_name' => 'Ana Ruiz', 'species' => 'Canino', 'breed' => 'Husky'],
            ['name' => 'Rocky', 'owner_name' => 'Jose Paz', 'species' => 'Canino', 'breed' => 'Mestizo'],
            ['name' => 'Nina', 'owner_name' => 'Lucia Paz', 'species' => 'Felino', 'breed' => 'Siames'],
            ['name' => 'Milo', 'owner_name' => 'Carlos Vega', 'species' => 'Felino', 'breed' => 'Persa'],
            ['name' => 'Copito', 'owner_name' => 'Sofia Mena', 'species' => 'Conejo', 'breed' => null],
            ['name' => 'Kiwi', 'owner_name' => 'Jorge Rios', 'species' => 'Ave', 'breed' => 'Periquito'],
            ['name' => 'Lolo', 'owner_name' => 'Martha Diaz', 'species' => 'Ave', 'breed' => 'Agaporni'],
            ['name' => 'Bolt', 'owner_name' => 'Erik Solis', 'species' => 'Hamster', 'breed' => null],
            ['name' => 'Pepa', 'owner_name' => 'Rocio Leon', 'species' => 'Tortuga', 'breed' => null],
        ];

        foreach ($pets as $pet) {
            $speciesId = $speciesByName[$pet['species']] ?? null;
            if (! $speciesId) {
                continue;
            }

            DB::table('pets')->updateOrInsert(
                ['name' => $pet['name'], 'owner_name' => $pet['owner_name']],
                [
                    'species_id' => $speciesId,
                    'breed' => $pet['breed'],
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $pricingRules = [
            ['species' => 'Canino', 'diagnosis' => 'Dermatitis', 'default_cost' => 350],
            ['species' => 'Canino', 'diagnosis' => 'Otitis', 'default_cost' => 300],
            ['species' => 'Canino', 'diagnosis' => 'Gastroenteritis', 'default_cost' => 420],
            ['species' => 'Canino', 'diagnosis' => 'Control general', 'default_cost' => 250],
            ['species' => 'Felino', 'diagnosis' => 'Gingivitis', 'default_cost' => 320],
            ['species' => 'Felino', 'diagnosis' => 'Parasitosis', 'default_cost' => 280],
            ['species' => 'Felino', 'diagnosis' => 'Control general', 'default_cost' => 240],
            ['species' => 'Conejo', 'diagnosis' => 'Revision general', 'default_cost' => 250],
            ['species' => 'Ave', 'diagnosis' => 'Chequeo respiratorio', 'default_cost' => 290],
            ['species' => 'Hamster', 'diagnosis' => 'Revision general', 'default_cost' => 220],
            ['species' => 'Tortuga', 'diagnosis' => 'Control caparazon', 'default_cost' => 260],
        ];

        foreach ($pricingRules as $rule) {
            $speciesId = $speciesByName[$rule['species']] ?? null;
            if (! $speciesId) {
                continue;
            }

            DB::table('consultation_pricing_rules')->updateOrInsert(
                ['species_id' => $speciesId, 'diagnosis' => $rule['diagnosis']],
                [
                    'default_cost' => $rule['default_cost'],
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $inventoryItems = [
            ['name' => 'Amoxicilina 500mg', 'category' => 'Medicamentos', 'stock' => 80, 'unit_price' => 12.50, 'min_stock' => 20],
            ['name' => 'Antiparasitario oral', 'category' => 'Medicamentos', 'stock' => 60, 'unit_price' => 18.00, 'min_stock' => 15],
            ['name' => 'Pomada dermatologica', 'category' => 'Medicamentos', 'stock' => 35, 'unit_price' => 27.00, 'min_stock' => 10],
            ['name' => 'Antiinflamatorio veterinario', 'category' => 'Medicamentos', 'stock' => 45, 'unit_price' => 19.90, 'min_stock' => 12],
            ['name' => 'Vitamina B12 inyectable', 'category' => 'Medicamentos', 'stock' => 30, 'unit_price' => 22.50, 'min_stock' => 8],
            ['name' => 'Croquetas premium 10kg', 'category' => 'Alimentos', 'stock' => 25, 'unit_price' => 45.90, 'min_stock' => 8],
            ['name' => 'Croquetas cachorro 4kg', 'category' => 'Alimentos', 'stock' => 32, 'unit_price' => 26.40, 'min_stock' => 10],
            ['name' => 'Sobre humedo felino', 'category' => 'Alimentos', 'stock' => 120, 'unit_price' => 2.80, 'min_stock' => 30],
            ['name' => 'Snack dental canino', 'category' => 'Alimentos', 'stock' => 90, 'unit_price' => 3.90, 'min_stock' => 20],
            ['name' => 'Arena para gato 10L', 'category' => 'Alimentos', 'stock' => 40, 'unit_price' => 11.75, 'min_stock' => 10],
            ['name' => 'Collar nylon mediano', 'category' => 'Accesorios', 'stock' => 40, 'unit_price' => 9.50, 'min_stock' => 12],
            ['name' => 'Correa reforzada', 'category' => 'Accesorios', 'stock' => 30, 'unit_price' => 14.20, 'min_stock' => 10],
            ['name' => 'Placa identificacion', 'category' => 'Accesorios', 'stock' => 50, 'unit_price' => 6.30, 'min_stock' => 15],
            ['name' => 'Juguete cuerda mordedera', 'category' => 'Accesorios', 'stock' => 28, 'unit_price' => 8.80, 'min_stock' => 8],
            ['name' => 'Transportadora mediana', 'category' => 'Accesorios', 'stock' => 10, 'unit_price' => 49.00, 'min_stock' => 3],
            ['name' => 'Sueter canino talla M', 'category' => 'Ropa', 'stock' => 18, 'unit_price' => 21.00, 'min_stock' => 6],
            ['name' => 'Impermeable canino talla L', 'category' => 'Ropa', 'stock' => 12, 'unit_price' => 28.50, 'min_stock' => 4],
            ['name' => 'Pechera ajustable', 'category' => 'Ropa', 'stock' => 22, 'unit_price' => 16.40, 'min_stock' => 7],
        ];

        foreach ($inventoryItems as $item) {
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

        $petsByName = DB::table('pets')->pluck('id', 'name');
        $consultations = [
            ['pet' => 'Luna', 'species' => 'Canino', 'owner_name' => 'Pedro Ramos', 'diagnosis' => 'Dermatitis', 'treatment' => 'Baño medicado y crema topica.', 'cost' => 350.00, 'consulted_at' => '2026-03-18 10:30:00'],
            ['pet' => 'Max', 'species' => 'Canino', 'owner_name' => 'Ana Ruiz', 'diagnosis' => 'Otitis', 'treatment' => 'Limpieza auricular y gotas 2 veces al dia.', 'cost' => 300.00, 'consulted_at' => '2026-03-19 11:15:00'],
            ['pet' => 'Nina', 'species' => 'Felino', 'owner_name' => 'Lucia Paz', 'diagnosis' => 'Gingivitis', 'treatment' => 'Higiene oral y antiinflamatorio.', 'cost' => 320.00, 'consulted_at' => '2026-03-20 09:40:00'],
            ['pet' => 'Kiwi', 'species' => 'Ave', 'owner_name' => 'Jorge Rios', 'diagnosis' => 'Chequeo respiratorio', 'treatment' => 'Nebulizacion y control en 72 horas.', 'cost' => 290.00, 'consulted_at' => '2026-03-20 16:10:00'],
            ['pet' => 'Copito', 'species' => 'Conejo', 'owner_name' => 'Sofia Mena', 'diagnosis' => 'Revision general', 'treatment' => 'Control preventivo y vitaminas.', 'cost' => 250.00, 'consulted_at' => '2026-03-21 14:20:00'],
            ['pet' => 'Pepa', 'species' => 'Tortuga', 'owner_name' => 'Rocio Leon', 'diagnosis' => 'Control caparazon', 'treatment' => 'Suplemento de calcio y ajuste de habitat.', 'cost' => 260.00, 'consulted_at' => '2026-03-22 12:00:00'],
        ];

        foreach ($consultations as $consultation) {
            $speciesId = $speciesByName[$consultation['species']] ?? null;
            $petId = $petsByName[$consultation['pet']] ?? null;
            if (! $speciesId || ! $petId) {
                continue;
            }

            DB::table('consultations')->updateOrInsert(
                [
                    'pet_id' => $petId,
                    'diagnosis' => $consultation['diagnosis'],
                    'consulted_at' => $consultation['consulted_at'],
                ],
                [
                    'species_id' => $speciesId,
                    'pet_name' => $consultation['pet'],
                    'species' => $consultation['species'],
                    'owner_name' => $consultation['owner_name'],
                    'treatment' => $consultation['treatment'],
                    'cost' => $consultation['cost'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $sales = [
            ['product_name' => 'Croquetas premium 10kg', 'customer_name' => 'Pedro Ramos', 'quantity' => 1, 'unit_price' => 45.90, 'sold_at' => '2026-03-20 18:00:00'],
            ['product_name' => 'Collar nylon mediano', 'customer_name' => 'Ana Ruiz', 'quantity' => 1, 'unit_price' => 9.50, 'sold_at' => '2026-03-21 17:15:00'],
            ['product_name' => 'Sobre humedo felino', 'customer_name' => 'Lucia Paz', 'quantity' => 6, 'unit_price' => 2.80, 'sold_at' => '2026-03-22 10:35:00'],
            ['product_name' => 'Sueter canino talla M', 'customer_name' => 'Jose Paz', 'quantity' => 1, 'unit_price' => 21.00, 'sold_at' => '2026-03-22 13:20:00'],
        ];

        $inventoryByName = DB::table('inventory_items')->pluck('id', 'name');

        foreach ($sales as $sale) {
            $inventoryId = $inventoryByName[$sale['product_name']] ?? null;
            $total = (float) $sale['quantity'] * (float) $sale['unit_price'];

            DB::table('sales')->updateOrInsert(
                [
                    'product_name' => $sale['product_name'],
                    'customer_name' => $sale['customer_name'],
                    'sold_at' => $sale['sold_at'],
                ],
                [
                    'inventory_item_id' => $inventoryId,
                    'quantity' => $sale['quantity'],
                    'unit_price' => $sale['unit_price'],
                    'total' => $total,
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
        DB::table('sales')->whereIn('customer_name', [
            'Pedro Ramos',
            'Ana Ruiz',
            'Lucia Paz',
            'Jose Paz',
        ])->delete();

        DB::table('consultations')->whereIn('diagnosis', [
            'Dermatitis',
            'Otitis',
            'Gingivitis',
            'Chequeo respiratorio',
            'Revision general',
            'Control caparazon',
        ])->delete();

        DB::table('inventory_items')->whereIn('name', [
            'Amoxicilina 500mg',
            'Antiparasitario oral',
            'Pomada dermatologica',
            'Antiinflamatorio veterinario',
            'Vitamina B12 inyectable',
            'Croquetas premium 10kg',
            'Croquetas cachorro 4kg',
            'Sobre humedo felino',
            'Snack dental canino',
            'Arena para gato 10L',
            'Collar nylon mediano',
            'Correa reforzada',
            'Placa identificacion',
            'Juguete cuerda mordedera',
            'Transportadora mediana',
            'Sueter canino talla M',
            'Impermeable canino talla L',
            'Pechera ajustable',
        ])->delete();

        DB::table('consultation_pricing_rules')->whereIn('diagnosis', [
            'Dermatitis',
            'Otitis',
            'Gastroenteritis',
            'Control general',
            'Gingivitis',
            'Parasitosis',
            'Revision general',
            'Chequeo respiratorio',
            'Control caparazon',
        ])->delete();

        DB::table('pets')->whereIn('name', [
            'Luna',
            'Max',
            'Rocky',
            'Nina',
            'Milo',
            'Copito',
            'Kiwi',
            'Lolo',
            'Bolt',
            'Pepa',
        ])->delete();

        DB::table('species')->whereIn('name', [
            'Canino',
            'Felino',
            'Conejo',
            'Ave',
            'Hamster',
            'Tortuga',
        ])->delete();

        DB::table('users')->whereIn('email', [
            'demo@emi.com',
            'laura.medina@emi.com',
            'recepcion@emi.com',
        ])->delete();
    }
};
