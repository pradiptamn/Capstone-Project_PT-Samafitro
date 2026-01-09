<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data pelanggan dan kurir yang ada
        $customers = User::where('role', 'user')->pluck('id')->toArray();
        $couriers = User::where('role', 'courier')->pluck('id')->toArray();

        // Daftar produk dummy berdasarkan image_36d449.jpg
        $dummyProducts = [
            ['id' => 'PROD-01', 'name' => 'Canon iR 2625 (Best Seller)', 'price' => 28000000],
            ['id' => 'PROD-02', 'name' => 'Canon iR Advance DX C3826i', 'price' => 55000000],
            ['id' => 'PROD-03', 'name' => 'Canon Plotter TX-5410', 'price' => 45000000],
            ['id' => 'PROD-04', 'name' => 'Toner NPG-84 Black', 'price' => 1200000],
        ];

        // Looping untuk tiap bulan di tahun 2025 (1 - 12)
        for ($month = 1; $month <= 12; $month++) {

            // Tentukan jumlah transaksi per bulan (1 sampai 3)
            $transactionsThisMonth = rand(1, 3);

            for ($i = 1; $i <= $transactionsThisMonth; $i++) {

                // 1. Generate Order Number: AR-25MMXXXXX-SBDG
                $yearCode = '25';
                $monthCode = str_pad($month, 2, '0', STR_PAD_LEFT);
                $sequenceCode = str_pad($i, 5, '0', STR_PAD_LEFT);
                $orderNumber = "AR-{$yearCode}{$monthCode}{$sequenceCode}-SBDG";

                // 2. Tentukan Tanggal Transaksi Random di bulan tersebut
                $createdAt = Carbon::create(2025, $month, rand(1, 28), rand(9, 17), rand(0, 59));

                $subtotal = 0;
                $itemsToSave = [];

                // 3. Generate 1-2 item per pesanan
                $itemCount = rand(1, 2);
                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $dummyProducts[array_rand($dummyProducts)];
                    $qty = rand(1, 3);
                    $itemSubtotal = $product['price'] * $qty;

                    $itemsToSave[] = [
                        'product_id' => $product['id'],
                        'product_name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $qty,
                        'subtotal' => $itemSubtotal,
                    ];
                    $subtotal += $itemSubtotal;
                }

                $insuranceFee = (rand(0, 1) == 1) ? 20000 : 0; // Kadang ada asuransi, kadang tidak
                $totalPrice = $subtotal + $insuranceFee;

                // 4. Simpan ke tabel Orders
                $order = Order::create([
                    'id' => (string) Str::uuid(), // ID menggunakan UUID Random
                    'user_id' => $customers[array_rand($customers)],
                    'courier_id' => ($month > 6) ? $couriers[array_rand($couriers)] : null, // Semester 2 sudah banyak kurir
                    'order_number' => $orderNumber, // Format AR-25...
                    'shipping_phone' => '0812' . rand(10000000, 99999999),
                    'shipping_address' => 'Alamat Pelanggan ke-' . $i . ' di Bulan ' . $month,
                    'subtotal' => $subtotal,
                    'insurance_fee' => $insuranceFee,
                    'total_price' => $totalPrice,
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_type' => 'bank_transfer',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // 5. Simpan ke tabel Order Items
                foreach ($itemsToSave as $item) {
                    $item['order_id'] = $order->id; // Link ke UUID Order
                    $item['created_at'] = $createdAt;
                    $item['updated_at'] = $createdAt;
                    OrderItem::create($item);
                }
            }
        }
    }
}
