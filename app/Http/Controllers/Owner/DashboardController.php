<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini ada

class DashboardController extends Controller
{
    public function index()
    {
        // [PERBAIKAN] Ambil user yang login & muat HANYA proyek yang ditugaskan padanya
        $user = Auth::user()->load('proyeks.pengeluarans');

        // Ambil koleksi proyek yang sudah terfilter milik user ini
        $proyekList = $user->proyeks;

        // --- Kalkulasi Ulang Berdasarkan Proyek yang Sudah Difilter ---

        // 1. Total Pemasukan hanya dari proyek yang ditugaskan
        $totalPemasukan = $proyekList->sum('nilai_kontrak');

        // 2. Total Pengeluaran hanya dari proyek yang ditugaskan
        $totalPengeluaran = $proyekList->flatMap(function ($proyek) {
            return $proyek->pengeluarans;
        })->sum('total');

        // 3. Profit/Loss dihitung dari data yang sudah terfilter
        $profitLoss = $totalPemasukan - $totalPengeluaran;

        // --- Data untuk Grafik Pie ---
        $pieChartData = [
            'labels' => ['Pemasukan', 'Pengeluaran'],
            'values' => [$totalPemasukan, $totalPengeluaran],
        ];
        
        // --- Data Laporan per Proyek (sudah otomatis terfilter) ---
        $laporanProyek = $proyekList->map(function ($proyek) {
            $total_pengeluaran_proyek = $proyek->pengeluarans->sum('total');
            $profit_loss_proyek = $proyek->nilai_kontrak - $total_pengeluaran_proyek;

            return (object) [
                'nama_proyek' => $proyek->nama_proyek,
                'status' => $proyek->status,
                'nilai_kontrak' => $proyek->nilai_kontrak,
                'total_pengeluaran' => $total_pengeluaran_proyek,
                'profit_loss' => $profit_loss_proyek,
            ];
        });

        // Kirim data yang sudah spesifik ke view
        return view('layouts.owner.dashboard', compact(
            'totalPemasukan', 
            'totalPengeluaran', 
            'profitLoss',
            'pieChartData',
            'laporanProyek'
        ));
    }
}