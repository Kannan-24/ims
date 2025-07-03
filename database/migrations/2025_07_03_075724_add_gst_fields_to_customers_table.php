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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('pan_number', 10)->nullable()->after('gst_number');
            $table->enum('gst_status', ['Active', 'Cancelled', 'Suspended'])->default('Active')->after('pan_number');
            $table->timestamp('gst_verification_date')->nullable()->after('gst_status');
            $table->string('business_type', 100)->nullable()->after('gst_verification_date');
            $table->date('gst_registration_date')->nullable()->after('business_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'pan_number',
                'gst_status', 
                'gst_verification_date',
                'business_type',
                'gst_registration_date'
            ]);
        });
    }
};
