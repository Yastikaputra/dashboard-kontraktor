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
        
        $recentVendors = Tagihan::with('proyek')->latest()->take(5)->get();

        // === [PERBAIKAN] Mengubah 'status' menjadi 'status_bayar' ===
        // Mengambil data tagihan vendor yang belum lunas
        $tagihanBelumLunas = Tagihan::where('status_bayar', '!=', 'Lunas')->with('proyek')->latest()->take(5)->get();

        // Mengambil data upah tukang yang belum lunas (pastikan nama kolom status di tabel tukang juga benar)
        // Jika nama kolom di tabel tukang berbeda, sesuaikan 'status_bayar' di bawah ini
        $tukangBelumLunas = Tukang::where('status', '!=', 'Lunas')->with('proyek')->latest()->take(5)->get();
        // === AKHIR PERBAIKAN ===

        return view('dashboard.index', compact(
            'proyekBerjalan',
            'proyekSelesai',
            'pengeluaranBulanIni',
            'totalVendors',
            'totalPemasukan',
            'totalPengeluaran',
            'totalUpahTukang',
            'profitLoss',
            'chartLabels',
            'chartValues',
            'recentVendors',
            'tagihanBelumLunas',
            'tukangBelumLunas'
        ));
    }
}