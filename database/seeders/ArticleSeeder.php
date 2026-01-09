<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Transformasi Digital: Mengoptimalkan Efisiensi Kantor dengan Smart Office Solutions',
                'content' => 'Di era industri 4.0, PT Samafitro berkomitmen membantu perusahaan beralih dari manajemen dokumen konvensional menuju ekosistem digital yang cerdas. Melalui integrasi teknologi AI dan cloud computing, pengelolaan alur kerja kini menjadi lebih cepat, aman, dan efisien. Solusi ini tidak hanya mengurangi penggunaan kertas, tetapi juga meningkatkan produktivitas karyawan secara signifikan melalui otomatisasi proses bisnis.',
                'user_id' => 1, // Diarahkan ke ID Admin
                'year' => '2025',
                'image' => 'articles/smart-office.jpg',
            ],
            [
                'title' => 'Panduan Perawatan Berkala Mesin Fotokopi Canon imageRUNNER untuk Performa Maksimal',
                'content' => 'Menjaga durabilitas mesin fotokopi Canon imageRUNNER memerlukan perawatan yang konsisten. PT Samafitro merekomendasikan pemeriksaan rutin pada komponen pemanas (fuser unit) dan pembersihan kaca scanner secara berkala. Dengan dukungan teknisi bersertifikat kami, pelanggan dapat memastikan operasional kantor tetap berjalan tanpa hambatan teknis, sekaligus memperpanjang usia pakai investasi perangkat kantor Anda.',
                'user_id' => 1,
                'year' => '2025',
                'image' => 'articles/maintenance-tips.jpg',
            ],
            [
                'title' => 'Implementasi Keamanan Data pada Sistem Manajemen Dokumen Terpadu',
                'content' => 'Keamanan informasi adalah prioritas utama dalam setiap solusi yang ditawarkan PT Samafitro. Kami mengintegrasikan fitur enkripsi data tingkat lanjut dan sistem autentikasi pengguna pada setiap perangkat multifungsi kami. Hal ini dirancang untuk mencegah kebocoran data sensitif perusahaan dan memastikan bahwa setiap dokumen yang dicetak maupun dipindai tetap berada dalam pengawasan protokol keamanan yang ketat.',
                'user_id' => 1,
                'year' => '2026',
                'image' => 'articles/data-security.jpg',
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['title' => $article['title']],
                $article
            );
        }
    }
}
