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
        Schema::create('debitors', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->text('actual_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('villages')->nullOnDelete();
            $table->string('gst_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('lease_area')->nullable();
            $table->string('lease_period')->nullable();
            $table->text('remark')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debitors');
    }
};
