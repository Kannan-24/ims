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
        Schema::create('hotkeys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('action_name'); // e.g., 'Dashboard', 'Create Invoice', 'Search'
            $table->string('hotkey_combination'); // e.g., 'Ctrl+Shift+D'
            $table->string('description')->nullable();
            $table->string('action_url')->nullable(); // URL to navigate to
            $table->string('action_type')->default('navigate'); // 'navigate', 'modal', 'function'
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure unique hotkey combinations per user
            $table->unique(['user_id', 'hotkey_combination']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotkeys');
    }
};
