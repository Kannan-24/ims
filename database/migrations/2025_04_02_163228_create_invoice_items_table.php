<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key to product
            $table->uuid('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Foreign key to invoice
            $table->uuid('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

            // Foreign key to service (optional)
            $table->uuid('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');

            // Type: 'product' or 'service'
            $table->string('type')->default('product');

            $table->string('unit_type');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);

            // Tax columns
            $table->decimal('cgst', 10, 2)->nullable();
            $table->decimal('sgst', 10, 2)->nullable();
            $table->decimal('igst', 10, 2)->nullable();
            $table->decimal('gst', 8, 2)->default(0); // Combined GST

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
