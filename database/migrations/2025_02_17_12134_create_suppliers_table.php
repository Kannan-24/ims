<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('supplier_id', 50)->unique();
            $table->string('name', 100);
            $table->string('contact_person', 100);
            $table->string('phone_number', 20);
            $table->string('email', 100)->unique();
            $table->text('address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100);
            $table->string('postal_code', 20);
            $table->string('gst', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
