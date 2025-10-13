<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('quotation_code')->unique();
            $table->date('quotation_date');
            $table->uuid('customer_id');
            $table->uuid('contactperson_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('contactperson_id')->references('id')->on('contact_persons')->onDelete('cascade');
            $table->string('terms_condition')->nullable();
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
        Schema::dropIfExists('quotations');
    }
};
