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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary();
            // 2. RELASI
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('courier_id')->nullable()->constrained('users')->onDelete('set null');

            // 3. DATA TRANSAKSI
            $table->string('order_number')->unique(); // Invoice user (misal: INV/2024/XI/001)

            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->text('note')->nullable();


            // 4. KEUANGAN
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_price', 15, 2)->default(0); // Flat Rate
            $table->decimal('total_price', 15, 2);

            // 5. STATUS
            $table->string('status')->default('pending'); // pending, paid, processing, shipped, completed, cancelled

            // 6. PEMBAYARAN (MIDTRANS)
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_type')->nullable();
            $table->string('snap_token')->nullable();

            // 7. KURIR
            $table->string('proof_of_delivery')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
