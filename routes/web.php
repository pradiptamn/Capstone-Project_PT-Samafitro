<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProfileController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// User
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PromoController as UserPromoController;
use App\Http\Controllers\User\ArticleController as UserArticleController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController as UserOrderController;

// Courier
use App\Http\Controllers\Courier\OrderController as CourierOrderController;
use App\Http\Controllers\Courier\ProfileController as CourierProfileController;

// Sales
use App\Http\Controllers\Sales\OrderController as SalesOrderController;

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
    $products = Product::select('id', 'kategori_id', 'nama_produk', 'harga', 'stok', 'deskripsi', 'gambar', 'link_brosur')->get();

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

// Authenticated
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
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

// Route Dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->intended('/admin/dashboard');
    } else if ($user->role === 'sales') {
        return redirect()->intended('/sales/dashboard');
    } else if ($user->role === 'manager') {
        return redirect()->intended('/manager/dashboard');
    } else if ($user->role === 'courier') {
        return redirect()->intended('/courier/dashboard');
    }

    return redirect()->intended('/user');
})->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/dashboard', [AdminDashboardController::class, 'store'])->name('admin.dashboard.store');
    Route::delete('/dashboard/{id}', [AdminDashboardController::class, 'destroy'])->name('admin.dashboard.destroy');

    Route::get('/export/pdf', [AdminDashboardController::class, 'exportPdf'])->name('admin.export.pdf');
    Route::get('/export/excel', [AdminDashboardController::class, 'exportExcel'])->name('admin.export.excel');

    // Promo Admin
    Route::prefix('promos')->name('admin.promos.')->group(function () {
        Route::get('/', [AdminPromoController::class, 'index'])->name('index');
        Route::get('/create', [AdminPromoController::class, 'create'])->name('create');
        Route::post('/', [AdminPromoController::class, 'store'])->name('store');
        Route::get('/{promo}/edit', [AdminPromoController::class, 'edit'])->name('edit');
        Route::put('/{promo}', [AdminPromoController::class, 'update'])->name('update');
        Route::delete('/{promo}', [AdminPromoController::class, 'destroy'])->name('destroy');
    });

    // Artikel Admin
    Route::resource('articles', AdminArticleController::class)->names('admin.articles');

    // Produk Admin
    Route::prefix('produk')->name('admin.produk.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::post('/store', [AdminProductController::class, 'store'])->name('store');
        Route::put('/update/{id}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AdminProductController::class, 'destroy'])->name('delete');

        Route::get('/trash', [AdminProductController::class, 'trash'])->name('trash');
        Route::put('/{id}/restore', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [AdminProductController::class, 'forceDelete'])->name('forceDelete');
    });

    // Order Admin
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('admin.orders.update');

    Route::resource('couriers', CourierController::class)->names('admin.couriers');
});

// User & Admin Routes
Route::middleware(['auth', 'role:user,admin'])->prefix('user')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Produk user (logged-in)
    Route::get('/products', function () {
        return view('pages.user.produk');
    })->name('produk.user');

    // Halaman promo untuk user
    Route::get('/promos', [UserPromoController::class, 'index'])->name('promo.index');
    Route::get('/promos/{promo}', [UserPromoController::class, 'show'])->name('promo.show');

    // Artikel user
    Route::get('/articles', [UserArticleController::class, 'index'])->name('article.index');
    Route::get('/articles/{id}', [UserArticleController::class, 'show'])->name('article.show');

    // Halaman Hubungi Kami
    Route::get('contact', function () {
        return view('pages.user.contact');
    })->name('contact-us');

    // Cart routes
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'showShippingForm'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    // Route untuk Cancel Order
    Route::put('/orders/{order}/cancel', [UserOrderController::class, 'cancel'])
        ->name('orders.cancel');
    // Route untuk menangani setelah pembayaran sukses/selesai
    Route::get('/payment/finish', [UserOrderController::class, 'paymentFinish'])->name('payment.finish');
    // Route untuk tombol Cek Status Manual
    Route::get('/orders/{order}/check', [UserOrderController::class, 'checkStatus'])->name('orders.check');
    // Route cetak invoice/kuitansi
    Route::get('/orders/{order}/invoice', [UserOrderController::class, 'downloadInvoice'])
        ->name('orders.invoice');
});

// Route Khusus Kurir
Route::middleware(['auth', 'role:courier'])->prefix('courier')->name('courier.')->group(function () {
    Route::get('/dashboard', [CourierOrderController::class, 'index'])->name('dashboard');
    Route::get('/orders/{order}', [CourierOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [CourierOrderController::class, 'update'])->name('orders.update');

    Route::get('/courier/profile', [CourierProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/courier/profile', [CourierProfileController::class, 'update'])->name('profile.update');
});

// Route Khusus Sales
Route::middleware(['auth', 'role:sales'])->prefix('sales')->name('sales.')->group(function () {
    // Dashboard menggunakan controller yang sama dengan Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/export/pdf', [AdminDashboardController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [AdminDashboardController::class, 'exportExcel'])->name('export.excel');

    // Melihat daftar pesanan
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    Route::get('/search-users', [SalesOrderController::class, 'searchUsers'])->name('users.search');
    // Form Pembuatan Pesanan (Form Input)
    Route::get('/order/create', [SalesOrderController::class, 'create'])->name('order.create');

    // Proses Simpan Pesanan (Quick Register & Store Logic)
    Route::post('/order/store', [SalesOrderController::class, 'store'])->name('order.store');
});

// Route Khusus Sales
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    // Dashboard menggunakan controller yang sama dengan Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/export/pdf', [AdminDashboardController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [AdminDashboardController::class, 'exportExcel'])->name('export.excel');

    // Melihat daftar pesanan
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
});
