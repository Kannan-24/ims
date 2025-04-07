<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTermsConditionToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Adding a new column 'terms_condition' to store terms and conditions for the quotation
            $table->string('terms_condition')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Dropping the 'terms_condition' column if the migration is rolled back
            $table->dropColumn('terms_condition');
        });
    }
}
