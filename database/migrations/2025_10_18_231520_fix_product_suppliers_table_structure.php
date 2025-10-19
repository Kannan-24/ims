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
        // Drop the table and recreate it with proper structure
        Schema::dropIfExists('product_suppliers');

        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->id(); // Use auto-incrementing integer ID
            $table->uuid('product_id');
            $table->uuid('supplier_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unique(['product_id', 'supplier_id']); // Prevent duplicate relationships
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the original structure
        Schema::dropIfExists('product_suppliers');

        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->uuid('supplier_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }
};
