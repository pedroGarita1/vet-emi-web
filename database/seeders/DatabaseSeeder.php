<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\InventoryItem;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'demo@emi.com'],
            [
                'name' => 'Usuario Demo Emi',
                'password' => Hash::make('admin1234'),
            ]
        );

        $vacuna = InventoryItem::query()->updateOrCreate(
            ['name' => 'Vacuna Triple Felina'],
            [
                'category' => 'Vacunas',
                'stock' => 25,
                'unit_price' => 18.50,
                'min_stock' => 10,
                'is_active' => true,
            ]
        );

        $alimento = InventoryItem::query()->updateOrCreate(
            ['name' => 'Alimento Premium Canino 10kg'],
            [
                'category' => 'Alimentos',
                'stock' => 14,
                'unit_price' => 42.90,
                'min_stock' => 5,
                'is_active' => true,
            ]
        );

        Sale::query()->updateOrCreate(
            ['product_name' => 'Vacuna Triple Felina', 'customer_name' => 'Ana Torres'],
            [
                'inventory_item_id' => $vacuna->id,
                'quantity' => 1,
                'unit_price' => 18.50,
                'total' => 18.50,
                'sold_at' => now()->subHours(5),
            ]
        );

        Sale::query()->updateOrCreate(
            ['product_name' => 'Alimento Premium Canino 10kg', 'customer_name' => 'Carlos Vega'],
            [
                'inventory_item_id' => $alimento->id,
                'quantity' => 2,
                'unit_price' => 42.90,
                'total' => 85.80,
                'sold_at' => now()->subDays(1),
            ]
        );

        Consultation::query()->updateOrCreate(
            ['pet_name' => 'Luna', 'owner_name' => 'Marcela Ruiz'],
            [
                'species' => 'Felino',
                'diagnosis' => 'Gastritis leve',
                'treatment' => 'Dieta blanda por 3 dias y protector gastrico.',
                'cost' => 30.00,
                'consulted_at' => now()->subDays(2),
            ]
        );

        Consultation::query()->updateOrCreate(
            ['pet_name' => 'Rocky', 'owner_name' => 'Jose Paz'],
            [
                'species' => 'Canino',
                'diagnosis' => 'Dermatitis alergica',
                'treatment' => 'Shampoo medicado y antihistaminico por 7 dias.',
                'cost' => 45.00,
                'consulted_at' => now()->subDay(),
            ]
        );
        $this->call(RoleSeeder::class);
    }
}
