<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentItemsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('reference_number')->nullable();
            $table->enum('payment_method', ['cash', 'cheque', 'upi', 'bank_transfer']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_items');
    }
}
