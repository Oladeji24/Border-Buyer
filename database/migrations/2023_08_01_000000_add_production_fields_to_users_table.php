<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->string('two_factor_secret')->nullable()->after('password');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->string('phone_verification_code')->nullable()->after('phone');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_verification_code');
            $table->string('timezone')->default('UTC')->after('country');
            $table->string('preferred_language')->default('en')->after('timezone');
            $table->boolean('email_notifications_enabled')->default(true)->after('preferred_language');
            $table->boolean('sms_notifications_enabled')->default(false)->after('email_notifications_enabled');
            $table->string('avatar')->nullable()->after('profile_image');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('verification_token')->nullable()->after('bio');
            $table->timestamp('verification_token_expires_at')->nullable()->after('verification_token');
            $table->timestamp('suspended_at')->nullable()->after('verification_token_expires_at');
            $table->string('suspension_reason')->nullable()->after('suspended_at');
            $table->softDeletes();
        });

        // Add indexes for performance
        Schema::table('users', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('role');
            $table->index('email_verified_at');
            $table->index('last_login_at');
            $table->index('verification_token');
            $table->index('phone_verification_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['role']);
            $table->dropIndex(['email_verified_at']);
            $table->dropIndex(['last_login_at']);
            $table->dropIndex(['verification_token']);
            $table->dropIndex(['phone_verification_code']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'last_login_at',
                'last_login_ip',
                'email_verified_at',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'phone_verification_code',
                'phone_verified_at',
                'timezone',
                'preferred_language',
                'email_notifications_enabled',
                'sms_notifications_enabled',
                'avatar',
                'bio',
                'verification_token',
                'verification_token_expires_at',
                'suspended_at',
                'suspension_reason',
                'deleted_at'
            ]);
        });
    }
};