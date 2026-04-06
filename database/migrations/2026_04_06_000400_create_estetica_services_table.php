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
        Schema::create('estetica_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->nullable()->constrained('pets')->nullOnDelete();
            $table->string('pet_name');
            $table->string('owner_name')->nullable();
            $table->string('owner_phone', 30)->nullable();
            $table->string('owner_email')->nullable();
            $table->string('service_type', 120);
            $table->string('status', 30)->default('pendiente');
            $table->text('notes')->nullable();
            $table->dateTime('requested_at');
            $table->dateTime('ready_at')->nullable();
            $table->dateTime('notified_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'requested_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estetica_services');
    }
};
