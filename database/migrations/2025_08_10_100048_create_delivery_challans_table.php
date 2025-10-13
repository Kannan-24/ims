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
        Schema::create('delivery_challans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('dc_no')->unique();
            $table->uuid('invoice_id');
            $table->date('dc_date');
            $table->string('order_no')->nullable();
            $table->date('order_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'delivered', 'returned'])->default('pending');
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->index(['dc_no', 'invoice_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_challans');
    }
};
