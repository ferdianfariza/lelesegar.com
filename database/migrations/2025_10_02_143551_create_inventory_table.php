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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('minimum_stock', 15, 2)->default(0);
            $table->string('unit')->default('kg');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Inventory movements table for tracking changes
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('movement_type', ['in', 'out', 'adjustment'])->default('in');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('quantity_before', 15, 2)->default(0);
            $table->decimal('quantity_after', 15, 2)->default(0);
            $table->string('reference_type')->nullable(); // order, expense_transaction, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('inventory');
    }
};
