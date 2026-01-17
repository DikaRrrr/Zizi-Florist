<?php

use Faker\Guesser\Name;
use App\Models\AkunBank;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackEnd\UserController;
use App\Http\Controllers\Backend\RatingController;
use App\Http\Controllers\Frontend\ProdukController;
use App\Http\Controllers\BackEnd\AkunBankController;
use App\Http\Controllers\FrontEnd\BerandaController;
use App\Http\Controllers\Frontend\PesananController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\KeranjangController;
use App\Http\Controllers\FrontEnd\PembayaranController;
use App\Http\Controllers\Backend\ProdukController as AdminProduk;
use App\Http\Controllers\Backend\BerandaController as AdminBeranda;
use App\Http\Controllers\Backend\PesananController as AdminPesanan;
use App\Http\Controllers\Backend\VoucherController as AdminVoucher;

Route::get('/', [BerandaController::class, 'index']);
Route::get('frontend/beranda', [BerandaController::class, 'index'])->name('frontend.beranda');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.detail');
Route::get('/pencarian', [BerandaController::class, 'search'])->name('produk.search');

Route::middleware(['auth'])->group(function () {

    // 1. Keranjang Belanja
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
    Route::post('/keranjang/add/{id}', [KeranjangController::class, 'TambahKeranjang'])->name('keranjang.tambah');
    Route::delete('/keranjang/remove/{id}', [KeranjangController::class, 'remove'])->name('keranjang.hapus');
    Route::patch('/keranjang/update', [KeranjangController::class, 'KeranjangUpdate'])->name('keranjang.update');

    // 2. Checkout
    Route::get('/checkout', [PesananController::class, 'checkout'])->name('checkout');
    Route::post('/place-order', [PesananController::class, 'processCheckout'])->name('place.order');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'showPayment'])->name('payment.show');
    Route::post('/pembayaran/{id}', [PembayaranController::class, 'processPayment'])->name('payment.process');
    Route::post('/checkout/apply-voucher', [PesananController::class, 'applyVoucher'])->name('checkout.apply_voucher');

    // 3. Riwayat Pesanan (Pesanan Saya)
    Route::get('/pesanan-saya', [PesananController::class, 'index'])->name('pesanan.index');
    Route::post('/pesanan-diterima/{id}', [PesananController::class, 'markAsReceived'])->name('order.received');
    Route::post('/pesanan-batal/{id}', [PesananController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/kirim-ulasan', [PesananController::class, 'submitRating'])->name('submit.rating');

    // 4. Profil User
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update.avatar');
});

// 1. Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// 2. Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// 3. Logout (Keluar)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Dashboard Admin

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [AdminBeranda::class, 'index'])->name('dashboard');
    Route::get('produk/form-cetak', [AdminProduk::class, 'formcetak'])->name('produk.formcetak');
    Route::post('produk/cetak-laporan', [AdminProduk::class, 'cetak'])->name('produk.cetak');
    Route::resource('produk', AdminProduk::class);
    Route::get('pesanan/form-cetak', [AdminPesanan::class, 'formCetak'])->name('pesanan.formcetak');
    Route::post('pesanan/cetak-laporan', [AdminPesanan::class, 'cetak'])->name('pesanan.cetak');
    Route::resource('pesanan', AdminPesanan::class)->only(['index', 'show', 'destroy', 'update']);
    Route::get('voucher/form-cetak', [AdminVoucher::class, 'formCetak'])->name('voucher.formcetak');
    Route::post('voucher/cetak-laporan', [AdminVoucher::class, 'cetak'])->name('voucher.cetak');
    Route::resource('voucher', AdminVoucher::class);
    Route::resource('rekening', AkunBankController::class)->only(['index', 'store', 'destroy']);
    Route::get('user/form-cetak', [UserController::class, 'formCetak'])->name('user.formcetak');
    Route::post('user/cetak-laporan', [UserController::class, 'cetak'])->name('user.cetak');
    Route::resource('user', UserController::class);
    Route::get('rating/form-cetak', [RatingController::class, 'formCetak'])->name('rating.formcetak');
    Route::post('rating/cetak-laporan', [RatingController::class, 'cetak'])->name('rating.cetak');
    Route::resource('rating', RatingController::class)->only(['index']);
});
