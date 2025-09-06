<?php
// database/migrations/2024_01_01_000010_create_portfolio_analytics_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // page_view, project_view, contact_form, etc.
            $table->string('page')->nullable();
            $table->json('data')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_analytics');
    }
};
