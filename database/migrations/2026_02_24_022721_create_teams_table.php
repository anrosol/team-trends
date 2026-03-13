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
        Schema::create('teams', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('token')->nullable()->unique();
            $table->foreignUlid('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->unique();
            $table->integer('max_respondents');
            $table->boolean('active');
            $table->integer('likert_scale');
            $table->json('questions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
