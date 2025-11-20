<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

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

// Authentication
Route::middleware('guest')->group(function () {
    // Tampilkan form login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    // Proses login
    Route::post('/login', [LoginController::class, 'login']);
    // Tampilkan form daftar
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Proses pendaftaran
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// RESET PASSWORD
// 1. Halaman Input Email
Route::get('forgot-password', [ForgotPasswordController::class, 'showEmailForm'])->name('password.request');

// 2. Cek Email (Apakah ke Challenge atau Email)
Route::post('forgot-password/check', [ForgotPasswordController::class, 'showChallengeForm'])->name('password.check'); // Action form email mengarah ke sini

// 3. Verifikasi Jawaban (Challenge)
Route::post('forgot-password/challenge', [ForgotPasswordController::class, 'verifyChallenge'])->name('password.challenge.verify');

// 4. Opsi Fallback: Kirim Email (jika user klik "Kirim ke Email")
Route::post('forgot-password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// 5. Halaman Reset Password (dari link email atau redirect sukses challenge)
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');

// 6. Proses Simpan Password Baru
Route::post('reset-password', [ForgotPasswordController::class, 'storeNewPassword'])->name('password.update');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// User & Admin Routes
Route::middleware(['auth', 'role:user,admin'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});