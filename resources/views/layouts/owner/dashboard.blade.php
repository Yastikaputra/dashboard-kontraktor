<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Pengeluaran;
use App\Models\Tagihan; // Ini sekarang merepresentasikan Vendor
use App\Models\Tukang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Card Statistik Utama
        $proyekBerjalan = Proyek::where('status', 'Sedang Berjalan')->count();
        $proyekSelesai = Proyek::where('status', 'Selesai')->count();
        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal_struk', now()->month)
                                          ->whereYear('tanggal_struk', now()->year)
                                          ->sum('total');
        // [DIUBAH] Mengganti Tagihan Jatuh Tempo menjadi Total Vendor
        $totalVendors = Tagihan::count();

        // Laporan Keuangan
        $totalPemasukan = Proyek::sum('nilai_kontrak');
        $totalPengeluaran = Pengeluaran::sum('total');
        $totalUpahTukang = Tukang::sum('jumlah');
        $profitLoss = $totalPemasukan - ($totalPengeluaran + $totalUpahTukang);

        // Data untuk Grafik
        $pengeluaranPerProyek = Proyek::withSum('pengeluarans', 'total')
                                      ->has('pengeluarans')
                                      ->get();
        $chartLabels = $pengeluaranPerProyek->pluck('nama_proyek');
        $chartValues = $pengeluaranPerProyek->pluck('pengeluarans_sum_total');
        
        // [DIUBAH] Mengambil daftar vendor terbaru, bukan tagihan jatuh tempo
        $recentVendors = Tagihan::with('proyek')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'proyekBerjalan',
            'proyekSelesai',
            'pengeluaranBulanIni',
            'totalVendors', // Variabel baru
            'totalPemasukan',
            'totalPengeluaran',
            'totalUpahTukang',
            'profitLoss',
            'chartLabels',
            'chartValues',
            'recentVendors' // Variabel baru
        ));
    }
}

