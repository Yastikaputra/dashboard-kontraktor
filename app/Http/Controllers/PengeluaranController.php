<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar semua pengeluaran.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data pengeluaran dengan relasi proyeknya
        // Mengurutkan berdasarkan yang terbaru dan menggunakan pagination
        $pengeluarans = Pengeluaran::with('proyek')->latest()->paginate(10);

        // Mengirim data ke view
        return view('pengeluaran.index', compact('pengeluarans'));
    }
}

