<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;

// ... (Rute Login, Logout, dll.)
Route::get('/', function () { return redirect()->route('login'); });
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');


// === GRUP RUTE UNTUK ADMIN (KONTRAKTOR) ===
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.kontraktor');

    // Proyek
    Route::resource('proyek', ProyekController::class)->except(['show']);
    Route::post('/proyek/{id}/selesai', [ProyekController::class, 'tandaiSelesai'])->name('proyek.selesai');

    // Pengeluaran
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);
    Route::get('/pengeluaran/export-pdf', [PengeluaranController::class, 'exportPDF'])->name('pengeluaran.exportPdf');

    // Tagihan (sekarang berfungsi sebagai Tagihan & Vendor)
    Route::resource('tagihan', TagihanController::class);
    Route::post('/tagihan/{tagihan}/lunas', [TagihanController::class, 'tandaiLunas'])->name('tagihan.lunas');

    // Tukang
    Route::resource('tukang', TukangController::class);
    Route::post('/tukang/{tukang}/lunas', [TukangController::class, 'tandaiLunas'])->name('tukang.lunas');
});


// === GRUP RUTE UNTUK USER BIASA (OWNER) ===
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard.owner');
});

