<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tukang;

class TukangController extends Controller
{
    public function index()
    {
        $tukangs = Tukang::with('proyek')->latest()->paginate(10);
        return view('tukang.index', compact('tukangs'));
    }

    /**
     * **FITUR BARU:** Method untuk menandai pembayaran tukang sebagai 'Sudah Dibayar'.
     */
    public function tandaiLunas(Tukang $tukang)
    {
        $tukang->status = 'Sudah Dibayar';
        $tukang->save();

        return redirect()->route('tukang.index')->with('success', 'Pembayaran tukang berhasil ditandai lunas.');
    }
}

