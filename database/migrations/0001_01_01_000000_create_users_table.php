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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Add password tracking fields
            $table->timestamp('password_changed_at')->nullable()->after('password');
            $table->timestamp('last_login_at')->nullable()->after('password_changed_at');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->text('last_login_user_agent')->nullable()->after('last_login_ip');

            // Optional: Add avatar field for profile pictures
            $table->string('avatar')->nullable()->after('last_login_user_agent');

            // Optional: Add two-factor authentication fields
            $table->timestamp('two_factor_enabled_at')->nullable()->after('avatar');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled_at');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'password_changed_at',
                'last_login_at',
                'last_login_ip',
                'last_login_user_agent',
                'avatar',
                'two_factor_enabled_at',
                'two_factor_secret',
                'two_factor_recovery_codes'
            ]);
        });
    }
};
