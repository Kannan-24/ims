<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','totp_enabled')) {
                $table->boolean('totp_enabled')->default(false)->after('two_factor_enabled');
            }
            if (!Schema::hasColumn('users','email_otp_enabled')) {
                $table->boolean('email_otp_enabled')->default(false)->after('totp_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','totp_enabled')) {
                $table->dropColumn('totp_enabled');
            }
            if (Schema::hasColumn('users','email_otp_enabled')) {
                $table->dropColumn('email_otp_enabled');
            }
        });
    }
};
