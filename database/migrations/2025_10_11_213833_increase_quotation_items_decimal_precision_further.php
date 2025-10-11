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
        Schema::table('quotation_items', function (Blueprint $table) {
            // Increase precision for GST fields to handle very large amounts
            $table->decimal('cgst', 15, 4)->change();
            $table->decimal('sgst', 15, 4)->change();
            $table->decimal('igst', 15, 4)->change();
            $table->decimal('gst', 15, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            // Revert to previous precision
            $table->decimal('cgst', 10, 4)->change();
            $table->decimal('sgst', 10, 4)->change();
            $table->decimal('igst', 10, 4)->change();
            $table->decimal('gst', 10, 4)->change();
        });
    }
};
