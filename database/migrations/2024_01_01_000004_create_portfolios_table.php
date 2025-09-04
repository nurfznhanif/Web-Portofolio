<?php
// File: database/migrations/2024_01_01_000004_create_portfolios_table.php

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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('technologies');
            $table->text('challenges')->nullable();
            $table->text('solutions')->nullable();
            $table->string('github_url')->nullable();
            $table->json('images')->nullable();
            $table->string('project_type');
            $table->date('project_date');
            $table->timestamps();
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('position');
            $table->string('organization');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->enum('level', ['pemula', 'menengah', 'mahir']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('skills');
    }
};
