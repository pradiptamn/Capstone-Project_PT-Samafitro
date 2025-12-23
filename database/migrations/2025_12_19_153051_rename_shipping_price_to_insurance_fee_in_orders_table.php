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
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah nama kolom shipping_price menjadi insurance_fee
            $table->renameColumn('shipping_price', 'insurance_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jaga-jaga kalau mau rollback
            $table->renameColumn('insurance_fee', 'shipping_price');
        });
    }
};
