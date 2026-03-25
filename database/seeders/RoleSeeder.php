<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'administrador', 'description' => 'Acceso total al sistema'],
            ['name' => 'empleado', 'description' => 'Acceso limitado a operaciones'],
            ['name' => 'cliente', 'description' => 'Acceso solo a su información'],
        ]);
    }
}
