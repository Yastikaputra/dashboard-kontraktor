<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web". Buat sesuatu yang hebat!
|
*/

// Halaman utama akan langsung diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});


// === RUTE OTENTIKASI (LOGIN & LOGOUT) ===
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');


// === GRUP RUTE UNTUK ADMIN (KONTRAKTOR) ===
// Hanya user dengan role 'admin' yang bisa mengakses rute-rute ini
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.kontraktor');

    // Proyek (CRUD Lengkap)
    Route::resource('proyek', ProyekController::class)->except(['show']);
    Route::post('/proyek/{id}/selesai', [ProyekController::class, 'tandaiSelesai'])->name('proyek.selesai');
    Route::patch('/proyek/{proyek}/selesai', [ProyekController::class, 'tandaiSelesai'])->name('proyek.tandaiSelesai');
    // Pengeluaran (CRUD Lengkap)
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);

    // Tagihan (CRUD Lengkap + Aksi Lunas)
    Route::resource('tagihan', TagihanController::class)->except(['show']);
    Route::post('/tagihan/{id}/lunas', [TagihanController::class, 'tandaiLunas'])->name('tagihan.lunas');

    // Tukang (CRUD Lengkap + Aksi Lunas)
    Route::resource('tukang', TukangController::class)->except(['show']);
    Route::post('/tukang/{id}/lunas', [TukangController::class, 'tandaiLunas'])->name('tukang.lunas');
});


// === GRUP RUTE UNTUK USER BIASA (OWNER) ===
// Hanya user dengan role 'user' yang bisa mengakses rute ini
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard.owner');
});