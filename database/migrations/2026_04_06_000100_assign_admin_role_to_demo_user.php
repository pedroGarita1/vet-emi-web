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
        $adminRoleId = DB::table('roles')->where('name', 'administrador')->value('id');

        if ($adminRoleId) {
            DB::table('users')
                ->where('email', 'demo@emi.com')
                ->update([
                    'role_id' => $adminRoleId,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('email', 'demo@emi.com')
            ->update([
                'role_id' => null,
                'updated_at' => now(),
            ]);
    }
};
