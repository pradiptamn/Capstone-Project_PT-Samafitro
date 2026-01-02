<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Membatalkan pesanan yang tidak dibayar lebih dari 24 jam';

    public function handle()
    {
        $expiredOrders = Order::where('status', 'pending')
            ->where('payment_status', 'unpaid')
            ->where('created_at', '<', Carbon::now()->subDay()) // 24 jam
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Tidak ada pesanan kadaluarsa hari ini.');
            return;
        }

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                // 1. KEMBALIKAN STOK KE GUDANG
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->increment('stok', $item->quantity);
                }

                // 2. SET CANCEL
                $order->update([
                    'status' => 'cancelled',
                    'note' => $order->note . ' (Sistem: Dibatalkan otomatis & stok dilepaskan kembali)'
                ]);
            });
            $this->info("Order {$order->order_number} kadaluarsa. Stok dikembalikan.");
        }

        $this->info(count($expiredOrders) . " pesanan diproses.");
    }
}
