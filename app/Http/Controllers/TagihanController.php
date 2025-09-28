<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Proyek;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar semua data.
     */
    public function index()
    {
        $tagihans = Tagihan::with('proyek')->latest()->paginate(10);
        return view('tagihan.index', compact('tagihans'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        $proyeks = Proyek::where('status', '!=', 'Selesai')->orderBy('nama_proyek')->get();
        return view('tagihan.create', compact('proyeks'));
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
            'nama_vendor' => 'required|string|max:255', // KEMBALIKAN KE nama_vendor
            'no_invoice' => 'required|string|max:255',
            'nilai_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'jatuh_tempo' => 'required|date',
            'deskripsi' => 'nullable|string',
            'status_bayar' => 'required|string',
        ]);

        Tagihan::create($request->all());

        return redirect()->route('tagihan.index')
                         ->with('success', 'Data tagihan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(Tagihan $tagihan)
    {
        $proyeks = Proyek::orderBy('nama_proyek')->get();
        return view('tagihan.edit', compact('tagihan', 'proyeks'));
    }

    /**
     * Memperbarui data di database.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
            'nama_vendor' => 'required|string|max:255', // KEMBALIKAN KE nama_vendor
            'no_invoice' => 'required|string|max:255',
            'nilai_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'jatuh_tempo' => 'required|date',
            'deskripsi' => 'nullable|string',
            'status_bayar' => 'required|string',
        ]);

        $tagihan->update($request->all());

        return redirect()->route('tagihan.index')
                         ->with('success', 'Data tagihan berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return redirect()->route('tagihan.index')
                         ->with('success', 'Data tagihan berhasil dihapus.');
    }

    /**
     * Method untuk menandai tagihan sebagai lunas.
     */
    public function tandaiLunas($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->status_bayar = 'Lunas';
        $tagihan->save();

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil ditandai lunas.');
    }
}

