<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('hsn_code');
            $table->string('unit_type');
            $table->decimal('gst_percentage', 5, 2); //gst -> gst/2 = cgst, gst/2 = sgst
            $table->boolean('is_igst')->default(false); // Default is GST
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
