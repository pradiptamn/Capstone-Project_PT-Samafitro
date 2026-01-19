<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promos = [
            [
                'name' => 'Gebyar Awal Tahun 2026 - Diskon Toner 15%',
                'vendor' => 'Canon',
                'label' => 'Best Seller',
                'discount' => 10,
                'image' => 'promo_images/toner-gebyar.jpg',
                'terms' => "- Hanya berlaku pada periode tertentu\n- Syarat & Ketentuan berlaku untuk pelanggan kontrak",
                'periode' => '2026-01-31',
            ],
            [
                'name' => 'Promo Premium Canon iR Advance DX C3826i',
                'vendor' => 'Canon',
                'label' => 'Hot Deal',
                'discount' => 5,
                'image' => 'promo_images/ir-advance-dx.jpg',
                'terms' => 'Berlaku untuk instansi pemerintah dan swasta. Termasuk biaya instalasi awal dan pelatihan operator.',
                'periode' => '2026-02-28',
            ],
            [
                'name' => 'Flash Sale Ploter TX-5410 - Potongan Harga Spesial',
                'vendor' => 'Canon',
                'label' => 'Limited Offer',
                'discount' => 8,
                'image' => 'promo_images/plotter-flash-sale.jpg',
                'terms' => 'Berlaku selama persediaan unit Dead Stock masih tersedia di gudang pusat Jakarta.',
                'periode' => '2026-02-15',
            ],
        ];

        foreach ($promos as $promo) {
            Promo::firstOrCreate(
                ['name' => $promo['name']],
                $promo
            );
        }
    }
}
