<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;

// Halaman utama welcome
Route::get('/', function () {
    if (!session()->has('splash_shown')) {
        session(['splash_shown' => true]);
        return view('pages.splash');
    }
    return redirect('/beranda');
});
Route::get('/beranda', function () {
    return view('pages.beranda');
});

// Halaman Tentang Kami
Route::get('/tentangkami', function () {
    return view('pages.tentangkami');
});

// Halaman produk user
Route::get('/produk', function () {
    return view('pages.produk');
});

// API untuk data produk
Route::get('/produk/json', function () {
    $categories = Category::select('id', 'name')->get();
    $products = Product::select('id', 'kategori_id', 'nama_produk', 'deskripsi', 'gambar')->get();

    return response()->json([
        'categories' => $categories,
        'products' => $products
    ]);
});

// Halaman Kantor Cabang
Route::get('/kantor-cabang', function () {
    return view('pages.kantor-cabang');
});

// Halaman Hubungi Kami
Route::get('/hubungi-kami', function () {
    return view('pages.hubungikami');
});
