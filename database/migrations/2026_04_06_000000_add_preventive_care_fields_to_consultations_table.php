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
        Schema::table('consultations', function (Blueprint $table) {
            $table->boolean('vaccination_applied')->default(false)->after('consulted_at');
            $table->string('vaccination_note')->nullable()->after('vaccination_applied');
            $table->date('next_vaccination_at')->nullable()->after('vaccination_note');

            $table->boolean('deworming_applied')->default(false)->after('next_vaccination_at');
            $table->string('deworming_note')->nullable()->after('deworming_applied');
            $table->date('next_deworming_at')->nullable()->after('deworming_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'vaccination_applied',
                'vaccination_note',
                'next_vaccination_at',
                'deworming_applied',
                'deworming_note',
                'next_deworming_at',
            ]);
        });
    }
};
