<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('faktura.table_prefix') . 'invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained(config('faktura.table_prefix') . 'invoices')->cascadeOnDelete();
            $table->string('sku');
            $table->string('description');
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('unit_price_ex_vat');
            $table->unsignedBigInteger('unit_vat_amount');
            $table->unsignedBigInteger('unit_price_inc_vat');
            $table->unsignedTinyInteger('vat_rate')->default(25);
            $table->unsignedBigInteger('sub_total');
            $table->unsignedBigInteger('vat_total');
            $table->unsignedBigInteger('total');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('faktura.table_prefix') . 'invoice_lines');
    }
};
