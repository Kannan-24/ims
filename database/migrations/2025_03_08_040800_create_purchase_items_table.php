<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();

            // Foreign Key (Must be the same type as `id` in `purchases`)
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');

            $table->integer('quantity');
            $table->string('unit_type'); // kg, litre, etc.
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('cgst', 10, 2)->nullable();
            $table->decimal('sgst', 10, 2)->nullable();
            $table->decimal('igst', 10, 2)->nullable();

            $table->timestamps();

            // Ensure Purchases Table Exists Before Adding Foreign Key
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_items');
    }
};
