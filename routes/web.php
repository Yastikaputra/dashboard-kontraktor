<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama, akan langsung diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// === RUTE AUTENTIKASI ===
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');


// === GRUP RUTE UNTUK ADMIN (KONTRAKTOR) ===
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.kontraktor');
    
    // PERUBAHAN DI SINI: Menambahkan rute CRUD untuk Proyek
    Route::resource('proyek', ProyekController::class)->except(['show']);
    Route::post('/proyek/{id}/selesai', [ProyekController::class, 'tandaiSelesai'])->name('proyek.selesai');

    // Pengeluaran
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    
    // Tagihan
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('/tagihan/{id}/lunas', [TagihanController::class, 'tandaiLunas'])->name('tagihan.lunas');
    
    // Tukang
    Route::get('/tukang', [TukangController::class, 'index'])->name('tukang.index');
    Route::post('/tukang/{id}/lunas', [TukangController::class, 'tandaiLunas'])->name('tukang.lunas');
});

// === GRUP RUTE UNTUK USER BIASA (OWNER) ===
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard.owner');
});

