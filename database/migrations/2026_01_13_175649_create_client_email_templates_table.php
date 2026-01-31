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
        Schema::create('client_email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('role')->nullable();
            $table->unsignedBigInteger('client_user_id');
            $table->unsignedBigInteger('email_templates_id');
            $table->string('subject')->nullable();
            $table->text('template')->nullable();
            $table->string('tag_desc')->nullable();
            $table->unsignedBigInteger('status')->default(0);

            $table->foreign('client_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('email_templates_id')->references('id')->on('email_templates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_email_templates');
    }
};
