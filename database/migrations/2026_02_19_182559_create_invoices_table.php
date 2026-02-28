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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('date');
            $table->foreignId('financial_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('debitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('debitor_site_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('total_qty', 10, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_a_amount', 15, 2)->nullable()->default(0);

            $table->decimal('cgst_percent', 5, 2)->nullable()->default(0);
            $table->decimal('cgst_amount', 15, 2)->nullable()->default(0);

            $table->decimal('sgst_percent', 5, 2)->nullable()->default(0);
            $table->decimal('sgst_amount', 15, 2)->nullable()->default(0);

            $table->decimal('igst_percent', 5, 2)->nullable()->default(0);
            $table->decimal('igst_amount', 15, 2)->nullable()->default(0);

            $table->decimal('freight_amount', 15, 2)->nullable()->default(0);

            $table->string('discount_type')->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable()->default(0);

            $table->decimal('net_amount', 15, 2)->nullable()->default(0);
            $table->decimal('net_a_amount', 15, 2)->nullable()->default(0);

            $table->boolean('with_tax')->nullable()->default(true);

            $table->text('remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
