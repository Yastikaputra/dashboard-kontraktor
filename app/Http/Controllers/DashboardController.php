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

        // [DIHAPUS] Logika lama untuk tagihan vendor belum lunas tidak digunakan lagi
        // $tagihanBelumLunas = Tagihan::where('status_bayar', '!=', 'Lunas')->with('proyek')->latest()->take(5)->get();

        // [BARU] Mengambil data PENGELUARAN yang berstatus tagihan dan akan jatuh tempo
        $tagihanJatuhTempo = Pengeluaran::where('status_bayar', 'Belum Bayar')
                                        ->whereNotNull('tanggal_bayar')
                                        ->where('tanggal_bayar', '<=', now()->addDays(7)) // Jatuh tempo dalam 7 hari ke depan atau sudah lewat
                                        ->with('proyek')
                                        ->orderBy('tanggal_bayar', 'asc') // Urutkan dari yang paling mendesak
                                        ->take(5)
                                        ->get();

        // Mengambil data upah tukang yang belum lunas
        $tukangBelumLunas = Tukang::where('status', '!=', 'Lunas')->with('proyek')->latest()->take(5)->get();

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
            'tagihanJatuhTempo', // Mengirim data tagihan jatuh tempo baru
            'tukangBelumLunas'
        ));
    }
}