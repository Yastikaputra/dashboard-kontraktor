<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihans = Tagihan::with('proyek')->latest()->paginate(10);
        return view('tagihan.index', compact('tagihans'));
    }

    /**
     * **FITUR BARU:** Method untuk menandai tagihan sebagai 'Sudah Dibayar'.
     */
    public function tandaiLunas(Tagihan $tagihan)
    {
        $tagihan->status_bayar = 'Sudah Dibayar';
        $tagihan->tanggal_bayar = now();
        $tagihan->save();

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil ditandai lunas.');
    }
}

