<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->date('order_date');
            $table->string('order_no');
            $table->uuid('customer_id');
            $table->uuid('contactperson_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('contactperson_id')->references('id')->on('contact_persons')->onDelete('cascade');
            $table->decimal('sub_total', 10, 2);
            $table->decimal('cgst', 10, 2)->nullable();
            $table->decimal('sgst', 10, 2)->nullable();
            $table->decimal('igst', 10, 2)->nullable();
            $table->decimal('gst', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
