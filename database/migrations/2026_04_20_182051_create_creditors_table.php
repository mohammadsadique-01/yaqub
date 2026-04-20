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
        Schema::create('creditors', function (Blueprint $table) {
            $table->id();

            // BASIC
            $table->string('account_name');

            // CONSIGNEE ADDRESS
            $table->text('consignee_address')->nullable();
            $table->string('consignee_address2')->nullable();

            $table->string('consignee_district')->nullable();
            $table->string('consignee_city')->nullable();
            $table->string('consignee_state')->nullable();

            // BILLING
            $table->text('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();

            // CONTACT
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('customer_code')->nullable();

            // EXTRA
            $table->string('magazine_no')->nullable();
            $table->string('thana')->nullable();

            // LICENSE
            $table->string('licence_no')->nullable();
            $table->date('licence_valid')->nullable();
            $table->string('licence_type')->nullable();

            $table->string('gstin')->nullable();

            // AGREEMENT / LEASE
            $table->string('agreement_period')->nullable();
            $table->string('lease_area')->nullable();
            $table->string('lease_period')->nullable();

            $table->boolean('hide_quantity')->default(0);

            // REMARK
            $table->text('remarks')->nullable();

            // ACCOUNT
            $table->decimal('opening_amount', 12, 2)->default(0);
            $table->enum('amount_type', ['DR', 'CR'])->default('DR');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creditors');
    }
};
