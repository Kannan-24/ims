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
        Schema::table('delivery_challans', function (Blueprint $table) {
            // Rename dc_date to delivery_date
            $table->renameColumn('dc_date', 'delivery_date');
            
            // Add generated_at column
            $table->timestamp('generated_at')->nullable()->after('status');
            
            // Update status enum to include more values
            $table->enum('status', ['pending', 'generated', 'in_transit', 'delivered', 'cancelled', 'returned'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_challans', function (Blueprint $table) {
            // Rename back to dc_date
            $table->renameColumn('delivery_date', 'dc_date');
            
            // Drop generated_at column
            $table->dropColumn('generated_at');
            
            // Revert status enum
            $table->enum('status', ['pending', 'delivered', 'returned'])->default('pending')->change();
        });
    }
};
