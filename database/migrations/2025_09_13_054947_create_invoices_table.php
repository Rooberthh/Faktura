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
        Schema::create(config('faktura.table_prefix') . 'invoices', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('number');
            $table->nullableMorphs('billable');
            $table->unsignedBigInteger('total')->default(0);
            $table->string('billing_name');
            $table->string('billing_address');
            $table->string('billing_postal_code');
            $table->string('billing_city');
            $table->string('billing_country');
            $table->string('billing_org_number')->nullable();
            $table->string('billing_vat_number')->nullable();
            $table->string('billing_external_id')->nullable();
            $table->string('seller_name');
            $table->string('seller_address');
            $table->string('seller_postal_code');
            $table->string('seller_city');
            $table->string('seller_country');
            $table->string('seller_org_number');
            $table->string('seller_vat_number');
            $table->string('seller_iban')->nullable();
            $table->string('seller_payment_reference')->nullable();
            $table->string('provider')->nullable();
            $table->string('external_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('faktura.table_prefix') . 'invoices');
    }
};
