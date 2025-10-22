<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\InputdataController; // Pastikan ini ada
use App\Http\Controllers\UserController;

// ... Rute Login, Logout, dll. ...
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
    Route::post('/proyek/{proyek}/tandai-selesai', [ProyekController::class, 'tandaiSelesai'])->name('proyek.tandaiSelesai');
    Route::resource('proyek', ProyekController::class);

    // Pengeluaran
    Route::get('/pengeluaran/export-pdf', [PengeluaranController::class, 'exportPDF'])->name('pengeluaran.exportPdf');
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);

    // Tagihan (Vendor)
    Route::resource('tagihan', TagihanController::class);
    // Tukang
    Route::resource('tukang', TukangController::class);
    Route::post('/tukang/{tukang}/lunas', [TukangController::class, 'tandaiLunas'])->name('tukang.lunas');
    // Route untuk halaman laporan
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    // [TAMBAHKAN BARIS INI]
    // Rute ini yang akan menangani permintaan ekspor
    Route::get('/report/export/{id_proyek}', [ReportController::class, 'export'])->name('report.export');
    
    // [BARU] Route untuk halaman settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // [DIUBAH] Rute untuk Fitur Input Data Awal (dengan 2-langkah review)
    Route::controller(InputdataController::class)->prefix('inputdata')->name('inputdata.')->group(function () {
        Route::get('/', 'index')->name('index'); // Halaman upload awal
        Route::get('/review', 'review')->name('review'); // Halaman review/penyesuaian
        Route::post('/upload-parse', 'parseUpload')->name('parse'); // Aksi upload dari halaman index
        Route::post('/process', 'process')->name('process'); // Aksi simpan dari halaman review
        Route::get('/download/{template}', 'downloadTemplate')->name('downloadTemplate'); // Aksi download template
    });

    // Route Users baru
    Route::resource('users', UserController::class);
});

// === GRUP RUTE UNTUK USER BIASA (OWNER) ===
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard.owner');
});


//require __DIR__.'/auth.php';
