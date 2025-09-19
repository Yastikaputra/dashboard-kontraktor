<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\Pengeluaran;
use App\Models\Tagihan;
use App\Models\Tukang;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk owner.
     */
    public function index()
    {
        // --- Logika Laporan Keuangan ---
        $totalPemasukan = Proyek::sum('nilai_kontrak');
        
        $pengeluaranDariSupplier = Pengeluaran::sum('total');
        $pengeluaranDariTagihan = Tagihan::where('status_bayar', 'Sudah Dibayar')->sum('nilai_tagihan');
        $pengeluaranDariUpahTukang = Tukang::sum('jumlah');
        $totalPengeluaran = $pengeluaranDariSupplier + $pengeluaranDariTagihan + $pengeluaranDariUpahTukang;

        $profitLoss = $totalPemasukan - $totalPengeluaran;

        // --- Data untuk Grafik Pie ---
        $pieChartData = [
            'labels' => ['Total Pemasukan', 'Total Pengeluaran'],
            'values' => [$totalPemasukan, $totalPengeluaran],
        ];

        return view('layouts.owner.dashboard', compact(
            'totalPemasukan', 
            'totalPengeluaran', 
            'profitLoss',
            'pieChartData'
        ));
    }
}

