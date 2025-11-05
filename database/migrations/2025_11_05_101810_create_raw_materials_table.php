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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama bahan baku (cup, teh, kopi, dll)
            $table->string('code')->unique(); // Kode bahan baku
            $table->text('description')->nullable(); // Deskripsi
            $table->string('unit')->default('pcs'); // Satuan (pcs, kg, liter, dll)
            $table->decimal('price_per_unit', 15, 2)->default(0); // Harga per satuan
            $table->decimal('beginning_stock', 15, 2)->default(0); // Stok awal
            $table->decimal('current_stock', 15, 2)->default(0); // Stok saat ini
            $table->decimal('minimum_stock', 15, 2)->default(0); // Stok minimum
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
