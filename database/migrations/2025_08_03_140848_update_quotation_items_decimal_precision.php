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
            // Increase precision for monetary fields to handle larger amounts
            $table->decimal('unit_price', 15, 4)->change();
            $table->decimal('total', 15, 4)->change();
            $table->decimal('cgst', 10, 4)->change();
            $table->decimal('sgst', 10, 4)->change();
            $table->decimal('igst', 10, 4)->change();
            $table->decimal('gst', 10, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            // Revert to original precision
            $table->decimal('unit_price', 10, 2)->change();
            $table->decimal('total', 10, 2)->change();
            $table->decimal('cgst', 10, 2)->change();
            $table->decimal('sgst', 10, 2)->change();
            $table->decimal('igst', 10, 2)->change();
            $table->decimal('gst', 8, 2)->change();
        });
    }
};
