<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Pengeluaran;
use App\Models\Tagihan;
use App\Models\Tukang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Import DB Facade

class DashboardController extends Controller
{
    public function index()
    {
        // STATISTIK UTAMA
        $proyekBerjalan = Proyek::where('status', '!=', 'Selesai')->count();
        $proyekSelesai = Proyek::where('status', 'Selesai')->count();
        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal_struk', Carbon::now()->month)
            ->whereYear('tanggal_struk', Carbon::now()->year)
            ->sum('total');
        $tagihanJatuhTempo = Tagihan::where('status_bayar', 'Belum Dibayar')
            ->where('jatuh_tempo', '<', Carbon::now()->addDays(30))
            ->count();

        // LAPORAN KEUANGAN
        $totalPemasukan = Proyek::sum('nilai_kontrak');
        $totalPengeluaran = Pengeluaran::sum('total');
        $totalUpahTukang = Tukang::sum('jumlah');
        $profitLoss = $totalPemasukan - ($totalPengeluaran + $totalUpahTukang);

        // DATA UNTUK TABEL DI DASHBOARD
        $proyekAktif = Proyek::where('status', '!=', 'Selesai')->latest()->take(5)->get();
        $daftarTagihanJatuhTempo = Tagihan::where('status_bayar', 'Belum Dibayar')
            ->whereDate('jatuh_tempo', '<=', Carbon::now()->addDays(30))
            ->whereHas('proyek')
            ->orderBy('jatuh_tempo', 'asc')
            ->get();
        $daftarSupplier = Tagihan::select('nama_supplier')->distinct()->get();

        // **FITUR BARU: Menyiapkan data untuk chart pengeluaran per proyek**
        $chartData = Pengeluaran::join('proyeks', 'pengeluarans.id_proyek', '=', 'proyeks.id_proyek')
            ->select('proyeks.nama_proyek', DB::raw('SUM(pengeluarans.total) as total_pengeluaran'))
            ->groupBy('proyeks.nama_proyek')
            ->pluck('total_pengeluaran', 'proyeks.nama_proyek');

        $chartLabels = $chartData->keys();
        $chartValues = $chartData->values();


        return view('dashboard.index', compact(
            'proyekBerjalan',
            'proyekSelesai',
            'pengeluaranBulanIni',
            'tagihanJatuhTempo',
            'totalPemasukan',
            'totalPengeluaran',
            'totalUpahTukang',
            'profitLoss',
            'proyekAktif',
            'daftarTagihanJatuhTempo',
            'daftarSupplier',
            'chartLabels', // Mengirim label chart ke view
            'chartValues'  // Mengirim nilai chart ke view
        ));
    }
}

