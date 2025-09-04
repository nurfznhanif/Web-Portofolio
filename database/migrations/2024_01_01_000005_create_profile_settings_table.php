<?php
// database/migrations/2024_01_01_000005_create_profile_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Profile Settings Table
        Schema::create('profile_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('type')->default('text'); // text, json, image, file, number, boolean
            $table->text('value')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->default('general');
            $table->boolean('is_public')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'sort_order']);
            $table->index('is_public');
        });

        // Social Links Table
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        // Achievements Table
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('date_achieved');
            $table->string('category'); // academic, professional, research, personal
            $table->string('issuer')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'is_active', 'sort_order']);
            $table->index('is_featured');
        });

        // Languages Table
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('level'); // native, fluent, intermediate, basic
            $table->integer('proficiency')->default(0); // 0-100
            $table->text('description')->nullable();
            $table->string('certificate')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        // Interests Table
        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('general'); // technical, personal, academic
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'is_active', 'sort_order']);
            $table->index('is_featured');
        });

        // Certifications Table
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('issuer');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->json('skills')->nullable(); // Array of skills
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index('issue_date');
        });

        // Contact Messages Table
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('country')->nullable();
            $table->enum('status', ['new', 'read', 'replied', 'archived'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('email');
        });

        // Analytics Table
        Schema::create('portfolio_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // page_view, contact_form, cv_download, project_view
            $table->string('page')->nullable();
            $table->json('data')->nullable(); // Additional event data
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->timestamp('created_at');

            $table->index(['event_type', 'created_at']);
            $table->index('page');
        });

        // Cache Management Table
        Schema::create('cache_management', function (Blueprint $table) {
            $table->id();
            $table->string('cache_key');
            $table->timestamp('last_cleared_at');
            $table->integer('clear_count')->default(0);
            $table->string('cleared_by')->nullable(); // admin user who cleared
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index('cache_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_management');
        Schema::dropIfExists('portfolio_analytics');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('interests');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('profile_settings');
    }
};
