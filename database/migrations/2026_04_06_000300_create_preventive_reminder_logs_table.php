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
        Schema::create('preventive_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->cascadeOnDelete();
            $table->string('reminder_type', 30);
            $table->date('due_date');
            $table->string('channel', 20);
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->unique(['consultation_id', 'reminder_type', 'due_date', 'channel'], 'uq_preventive_reminder_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_reminder_logs');
    }
};
