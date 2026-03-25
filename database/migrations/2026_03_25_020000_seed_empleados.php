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
        // Buscar el rol de empleado
        $roleId = DB::table('roles')->where('name', 'empleado')->value('id');
        if (!$roleId) return;

        $empleados = [
            ['name' => 'Pedro González', 'email' => 'pedro.gonzalez@emi.com'],
            ['name' => 'María López', 'email' => 'maria.lopez@emi.com'],
            ['name' => 'Juan Pérez', 'email' => 'juan.perez@emi.com'],
            ['name' => 'Ana Ramírez', 'email' => 'ana.ramirez@emi.com'],
        ];

        foreach ($empleados as $empleado) {
            DB::table('users')->updateOrInsert(
                ['email' => $empleado['email']],
                [
                    'name' => $empleado['name'],
                    'password' => Hash::make('admin1234'),
                    'role_id' => $roleId,
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
        DB::table('users')->whereIn('email', [
            'pedro.gonzalez@emi.com',
            'maria.lopez@emi.com',
            'juan.perez@emi.com',
            'ana.ramirez@emi.com',
        ])->delete();
    }
};
