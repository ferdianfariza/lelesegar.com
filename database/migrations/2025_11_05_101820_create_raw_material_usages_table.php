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
        Schema::create('raw_material_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_material_id')->constrained()->onDelete('cascade');
            $table->date('usage_date'); // Tanggal pemakaian
            $table->decimal('quantity', 15, 2); // Jumlah yang dipakai
            $table->decimal('price_per_unit', 15, 2); // Harga per satuan saat dipakai
            $table->decimal('total_cost', 15, 2); // Total biaya (quantity * price_per_unit)
            $table->text('notes')->nullable(); // Catatan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_usages');
    }
};
