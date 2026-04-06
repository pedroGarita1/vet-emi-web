<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hacer product_name, quantity y unit_price nullables en sales
        // para que las ventas multi-artículo no necesiten esos campos en el encabezado
        Schema::table('sales', function (Blueprint $table) {
            $table->string('product_name')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->decimal('unit_price', 10, 2)->nullable()->change();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->unsignedBigInteger('inventory_item_id')->nullable();
            $table->string('product_name');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');

        Schema::table('sales', function (Blueprint $table) {
            $table->string('product_name')->nullable(false)->change();
            $table->integer('quantity')->nullable(false)->default(1)->change();
            $table->decimal('unit_price', 10, 2)->nullable(false)->default(0)->change();
        });
    }
};
