<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->string('presentation')->nullable()->after('category');
            $table->string('sale_unit', 40)->default('unidad')->after('presentation');
            $table->string('target_species')->nullable()->after('sale_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn(['presentation', 'sale_unit', 'target_species']);
        });
    }
};
