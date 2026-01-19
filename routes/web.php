<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\DashboardPembeliController;
use App\Http\Controllers\DashboardSuperAdminController;
use App\Http\Controllers\KonfirmasiPetaniController;
use App\Http\Controllers\DashboardPetaniController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\CekHargaPasarController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\KelolaPesananController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ===== FORGOT PASSWORD =====
Route::get('/forgot-password', function () {
    return view('auth.forgotPassword');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Link reset password telah dikirim ke email.')
        : back()->withErrors(['email' => 'Email tidak terdaftar.']);
})->middleware('guest')->name('password.email');


// ===== RESET PASSWORD =====
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.resetPassword', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password berhasil direset.')
        : back()->withErrors(['email' => 'Token tidak valid atau kadaluarsa.']);
})->middleware('guest')->name('password.update');


Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landingPage');

// Halaman statis (bisa bikin controller terpisah nanti)
Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/bantuan', 'bantuan')->name('bantuan');
Route::view('/kontak', 'kontak')->name('kontak');

/*
|--------------------------------------------------------------------------
| SUPERADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('role:superadmin')->group(function () {
    Route::get('/admin/dashboard', [DashboardSuperAdminController::class, 'index'])
        ->name('superAdmin.dashboardSuperAdmin');

    Route::get('/admin/approval', [SuperAdminController::class, 'approval'])
        ->name('superAdmin.approval');

    Route::post('/admin/approve/{id}', [SuperAdminController::class, 'approve'])
        ->name('superAdmin.approve');

    //Route::get('/toko-petani', function () {
        //return view('superAdmin.tokoPetani');
    //})->name('superAdmin.tokoPetani');

    /// Ubah nama route-nya biar gak bentrok
    Route::get('/admin/toko', [TokoController::class, 'adminIndex'])
        ->name('superAdmin.tokoPetani');  // ← Ini yang dipanggil dari sidebar

    Route::get('/admin/toko/{id}', [TokoController::class, 'adminShow'])
        ->name('superAdmin.toko.show');

    Route::get('/konfirmasi-petani', function () {
        return view('superAdmin.konfirmasi');
    })->name('superAdmin.konfirmasiPetani');

    Route::get('/konfirmasi-petani', [KonfirmasiPetaniController::class, 'index'])
    ->name('superAdmin.konfirmasiPetani');

    Route::post('/konfirmasi-petani/approve/{id}', [KonfirmasiPetaniController::class, 'approve'])
    ->name('superAdmin.approvePetani');

    Route::post('/konfirmasi-petani/reject/{id}', [KonfirmasiPetaniController::class, 'reject'])
    ->name('superAdmin.rejectPetani');

    // Landing Page Management
    Route::get('/admin/landing-page', [LandingPageController::class, 'index'])
        ->name('superAdmin.kelolaLandingPage');
    Route::post('/admin/landing-page/update', [LandingPageController::class, 'update'])
        ->name('admin.landingPage.update');
    Route::post('/admin/landing-page/feature/{id}', [LandingPageController::class, 'updateFeature'])
        ->name('admin.landingPage.feature.update');

});


/*
|--------------------------------------------------------------------------
| PETANI ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('role:petani')->group(function () {
    Route::get('/petani/dashboard', [DashboardPetaniController::class, 'index'])
        ->name('petani.dashboardPetani');

    // ✅ Route untuk filter chart (AJAX)
    Route::get('/petani/chart-data', [DashboardPetaniController::class, 'getChartByProduct'])
        ->name('petani.chartData');

    Route::get('/pesanan-petani', function () {
        return view('petani.pesanan');
    })->name('petani.pesanan');

    Route::get('/cek-harga-pasar', [CekHargaPasarController::class, 'cekHargaPasar'])->name('petani.cekHargaPasar');


    Route::get('/profil', [PetaniController::class, 'profil'])->name('petani.profil');
    Route::put('/profil/update', [PetaniController::class, 'updateProfil'])->name('petani.updateProfil');
    Route::put('/profil/password', [PetaniController::class, 'updatePassword'])->name('petani.updatePassword');

    // KATALOG SAYA - CRUD Produk
    Route::get('/katalog-saya', [KatalogController::class, 'index'])->name('petani.katalogSaya');
    Route::post('/katalog-saya', [KatalogController::class, 'store'])->name('petani.katalogStore');
    Route::get('/katalog-saya/{product}/edit', [KatalogController::class, 'edit'])->name('petani.katalogEdit');
    Route::put('/katalog-saya/{product}', [KatalogController::class, 'update'])->name('petani.katalogUpdate');
    Route::delete('/katalog-saya/{product}', [KatalogController::class, 'destroy'])->name('petani.katalogDestroy');


    // Toggle Status Produk (Aktif/Nonaktif)
    Route::patch('/katalog-saya/{product}/toggle-status', [KatalogController::class, 'toggleStatus'])->name('katalog.toggleStatus');

    // Route untuk toko
    Route::get('/toko-saya', [TokoController::class, 'edit'])->name('petani.tokoEdit');
    Route::put('/toko-saya', [TokoController::class, 'update'])->name('petani.tokoUpdate');

    // Route Kelola Pesanan
    Route::get('/pesanan-petani', [KelolaPesananController::class, 'index'])
        ->name('petani.kelolaPesanan');
    Route::get('/pesanan-petani/{id}', [KelolaPesananController::class, 'show'])
        ->name('petani.pesananDetail');
    Route::patch('/pesanan-petani/{id}/update-status', [KelolaPesananController::class, 'updateStatus'])
        ->name('petani.updateStatusPesanan');

});


/*
|--------------------------------------------------------------------------
| PEMBELI ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('role:pembeli')->group(function () {
    Route::get('/pembeli/dashboard', [DashboardPembeliController::class, 'index'])
        ->name('pembeli.dashboardPembeli');

    Route::get('/pembeli/profil', [PembeliController::class, 'profil'])->name('pembeli.profil');
    Route::put('/pembeli/profil/update', [PembeliController::class, 'updateProfil'])->name('pembeli.updateProfil');
    Route::put('/pembeli/profil/password', [PembeliController::class, 'updatePassword'])->name('pembeli.updatePassword');

    // Route Keranjang
    Route::get('/pembeli/keranjang', [CartController::class, 'index'])->name('pembeli.cart');
    Route::post('/pembeli/keranjang/add/{product}', [CartController::class, 'add'])->name('pembeli.cartAdd');
    Route::patch('/pembeli/keranjang/update/{cart}', [CartController::class, 'update'])->name('pembeli.cartUpdate');
    Route::delete('/pembeli/keranjang/remove/{cart}', [CartController::class, 'remove'])->name('pembeli.cartRemove');

    // Halaman daftar semua toko
    Route::get('/pembeli/toko', [TokoController::class, 'index'])->name('toko.index');

    // Halaman detail toko + produknya
    Route::get('/pembeli/toko/{id}', [TokoController::class, 'show'])->name('toko.show');

    // Route Checkout
    Route::get('/pembeli/checkout', [CheckoutController::class, 'index'])->name('pembeli.checkout');
    Route::post('/pembeli/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Route untuk AJAX ambil kota
    Route::get('/api/cities/{province}', [CheckoutController::class, 'getCities']);

    // Payment Routes
    Route::get('/payment/qris/{order}', [PaymentController::class, 'showQRIS'])->name('payment.qris');
    Route::post('/payment/simulate/{order}', [PaymentController::class, 'simulatePayment'])->name('payment.simulate');
    Route::get('/payment/check/{order}', [PaymentController::class, 'checkStatus'])->name('payment.check');

    Route::get('/pembeli/riwayat', [PembeliController::class, 'riwayat'])->name('pembeli.riwayatPesanan');
    Route::get('/pembeli/riwayat/{order}', [PembeliController::class, 'detailRiwayat'])->name('pembeli.riwayatDetail');

    Route::post('/pembeli/pesanan/{id}/selesaikan', [PembeliController::class, 'selesaikanPesanan'])
        ->name('pembeli.selesaikanPesanan');

    Route::post('/pembeli/pesanan/{id}/review', [PembeliController::class, 'storeReview'])
        ->name('pembeli.storeReview');

    Route::post('/pesanan/{id}/beli-lagi', [PembeliController::class, 'beliLagi'])
        ->name('pembeli.beliLagi');
});

//Webhook dari Xendit (TANPA AUTH - harus di luar middleware auth!)
Route::post('/xendit/callback', [PaymentController::class, 'xenditCallback'])->name('xendit.callback');

