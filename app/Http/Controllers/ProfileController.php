<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    /**
     * Menampilkan daftar proyek.
     */
    public function index()
    {
        $proyeks = Proyek::latest()->paginate(10);
        return view('proyek.index', compact('proyeks'));
    }

    /**
     * Menampilkan form untuk membuat proyek baru.
     */
    public function create()
    {
        return view('proyek.create');
    }

    /**
     * Menyimpan proyek baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'anggaran_proyek' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'pic' => 'required|string|max:255',
            'nomer_pic' => 'required|string|max:20',
        ]);

        Proyek::create($request->all());

        return redirect()->route('proyek.index')
                         ->with('success', 'Proyek baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit proyek.
     */
    public function edit(Proyek $proyek)
    {
        return view('proyek.edit', compact('proyek'));
    }

    /**
     * Memperbarui data proyek di database.
     */
    public function update(Request $request, Proyek $proyek)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'anggaran_proyek' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'pic' => 'required|string|max:255',
            'nomer_pic' => 'required|string|max:20',
        ]);

        $proyek->update($request->all());

        return redirect()->route('proyek.index')
                         ->with('success', 'Data proyek berhasil diperbarui.');
    }

    /**
     * Menghapus proyek dari database.
     */
    public function destroy(Proyek $proyek)
    {
        // Tambahkan validasi, misalnya cek apakah ada pengeluaran terkait
        if ($proyek->pengeluarans()->count() > 0) {
            return redirect()->route('proyek.index')
                             ->with('error', 'Proyek tidak bisa dihapus karena memiliki data pengeluaran terkait.');
        }

        $proyek->delete();

        return redirect()->route('proyek.index')
                         ->with('success', 'Proyek berhasil dihapus.');
    }
}