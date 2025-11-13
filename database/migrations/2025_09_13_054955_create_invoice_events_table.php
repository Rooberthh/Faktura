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
        Schema::create(config('faktura.table_prefix') . 'invoice_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained(config('faktura.table_prefix') . 'invoices')->cascadeOnDelete();
            $table->string('type');
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['invoice_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('faktura.table_prefix') . 'invoice_events');
    }
};
