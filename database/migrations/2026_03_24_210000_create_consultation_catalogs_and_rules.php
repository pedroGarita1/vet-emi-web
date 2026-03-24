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
        Schema::create('species', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('owner_name')->nullable();
            $table->foreignId('species_id')->nullable()->constrained('species')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['name', 'owner_name']);
        });

        Schema::create('consultation_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('species_id')->constrained('species')->cascadeOnDelete();
            $table->string('diagnosis');
            $table->decimal('default_cost', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['species_id', 'diagnosis'], 'uq_species_diagnosis');
        });

        Schema::table('consultations', function (Blueprint $table) {
            $table->foreignId('species_id')->nullable()->after('id')->constrained('species')->nullOnDelete();
            $table->foreignId('pet_id')->nullable()->after('species_id')->constrained('pets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('species_id');
            $table->dropConstrainedForeignId('pet_id');
        });

        Schema::dropIfExists('consultation_pricing_rules');
        Schema::dropIfExists('pets');
        Schema::dropIfExists('species');
    }
};
