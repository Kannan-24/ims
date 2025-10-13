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
        Schema::create('notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('content')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['created_by', 'is_pinned']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
