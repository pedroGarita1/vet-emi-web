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

        $speciesRows = [
            ['name' => 'Canino', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Felino', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Conejo', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ave', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($speciesRows as $row) {
            DB::table('species')->updateOrInsert(
                ['name' => $row['name']],
                ['is_active' => $row['is_active'], 'updated_at' => $row['updated_at'], 'created_at' => $row['created_at']]
            );
        }

        $speciesByName = DB::table('species')->pluck('id', 'name');

        $petsRows = [
            ['name' => 'Luna', 'owner_name' => 'Pedro', 'species' => 'Canino'],
            ['name' => 'Max', 'owner_name' => 'Ana Ruiz', 'species' => 'Canino'],
            ['name' => 'Milo', 'owner_name' => 'Carlos Vega', 'species' => 'Felino'],
            ['name' => 'Nina', 'owner_name' => 'Lucia Paz', 'species' => 'Felino'],
            ['name' => 'Copito', 'owner_name' => 'Sofia Mena', 'species' => 'Conejo'],
            ['name' => 'Kiwi', 'owner_name' => 'Jorge Rios', 'species' => 'Ave'],
        ];

        foreach ($petsRows as $row) {
            $speciesId = $speciesByName[$row['species']] ?? null;
            if (! $speciesId) {
                continue;
            }

            DB::table('pets')->updateOrInsert(
                ['name' => $row['name'], 'owner_name' => $row['owner_name']],
                [
                    'species_id' => $speciesId,
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $rulesRows = [
            ['species' => 'Canino', 'diagnosis' => 'Dermatitis', 'default_cost' => 350],
            ['species' => 'Canino', 'diagnosis' => 'Otitis', 'default_cost' => 300],
            ['species' => 'Canino', 'diagnosis' => 'Gastroenteritis', 'default_cost' => 420],
            ['species' => 'Felino', 'diagnosis' => 'Gingivitis', 'default_cost' => 320],
            ['species' => 'Felino', 'diagnosis' => 'Parasitosis', 'default_cost' => 280],
            ['species' => 'Conejo', 'diagnosis' => 'Revision general', 'default_cost' => 250],
            ['species' => 'Ave', 'diagnosis' => 'Chequeo respiratorio', 'default_cost' => 290],
        ];

        foreach ($rulesRows as $row) {
            $speciesId = $speciesByName[$row['species']] ?? null;
            if (! $speciesId) {
                continue;
            }

            DB::table('consultation_pricing_rules')->updateOrInsert(
                [
                    'species_id' => $speciesId,
                    'diagnosis' => $row['diagnosis'],
                ],
                [
                    'default_cost' => $row['default_cost'],
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
        $speciesNames = ['Canino', 'Felino', 'Conejo', 'Ave'];

        $speciesIds = DB::table('species')
            ->whereIn('name', $speciesNames)
            ->pluck('id');

        DB::table('consultation_pricing_rules')->whereIn('species_id', $speciesIds)->delete();
        DB::table('pets')->whereIn('species_id', $speciesIds)->delete();
        DB::table('species')->whereIn('name', $speciesNames)->delete();
    }
};
