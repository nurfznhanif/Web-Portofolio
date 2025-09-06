<?php
// database/migrations/2024_01_01_000001_create_profiles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->text('summary');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('location')->default('Pekanbaru, Riau');
            $table->string('photo')->nullable();
            $table->json('education')->nullable(); // JSON field for education details
            $table->string('thesis_title')->nullable();
            $table->json('availability')->nullable(); // JSON field for availability status
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
