<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('password_last_reminder_sent_at');
            }
            if (!Schema::hasColumn('users','two_factor_secret')) {
                $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            }
            if (!Schema::hasColumn('users','two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            }
            if (!Schema::hasColumn('users','two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            }
            if (!Schema::hasColumn('users','preferred_2fa_method')) {
                $table->string('preferred_2fa_method')->nullable()->after('two_factor_confirmed_at');
            }
            if (!Schema::hasColumn('users','pending_otp_code')) {
                $table->string('pending_otp_code')->nullable()->after('preferred_2fa_method');
            }
            if (!Schema::hasColumn('users','pending_otp_expires_at')) {
                $table->timestamp('pending_otp_expires_at')->nullable()->after('pending_otp_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_enabled',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'preferred_2fa_method',
                'pending_otp_code',
                'pending_otp_expires_at',
            ]);
        });
    }
};
