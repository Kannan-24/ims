<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable(); // Admin, Faculty, etc.
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action_type'); // Report Generated, Mail Sent, etc.
            $table->string('module')->nullable(); // Invoice, Report, etc.
            $table->text('description')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
