<?php
// database/migrations/2024_01_01_000004_create_skills_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->enum('level', ['pemula', 'menengah', 'mahir'])->default('menengah');
            $table->text('description')->nullable();
            $table->integer('proficiency')->default(50); // Percentage 0-100
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
