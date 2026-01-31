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
        Schema::create('drilling_reports', function (Blueprint $table) {
            $table->id();

            $table->date('date');

            $table->foreignId('financial_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('debitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('debitor_site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('operator_id')->constrained()->cascadeOnDelete();

            $table->decimal('start_time', 5, 2);
            $table->decimal('end_time', 5, 2);
            $table->decimal('total_hours', 5, 2);

            $table->decimal('diesel', 8, 2)->nullable();
            $table->integer('hole')->nullable();
            $table->decimal('meter', 8, 2)->nullable();
            $table->decimal('balance_diesel', 8, 2)->nullable();

            $table->text('remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drilling_reports');
    }
};
