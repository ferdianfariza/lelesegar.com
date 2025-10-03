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
        Schema::create('income_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->date('transaction_date');
            $table->enum('income_type', ['sales', 'capital', 'other'])->default('sales');
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payment_method')->default('cash'); // cash, bank_transfer, etc.
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_transactions');
    }
};
