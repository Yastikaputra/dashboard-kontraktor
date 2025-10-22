<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar semua vendor.
     */
    public function index()
    {
        // Perubahan: Menghapus relasi 'proyek' yang tidak lagi digunakan di view.
        $tagihans = Tagihan::latest()->paginate(10);
        return view('tagihan.index', compact('tagihans'));
    }

    /**
     * Menampilkan form untuk membuat vendor baru.
     */
    public function create()
    {
        // Perubahan: Tidak perlu lagi mengambil data proyek.
        return view('tagihan.create');
    }

    /**
     * Menyimpan vendor baru ke database.
     */
    public function store(Request $request)
    {
        // Perubahan: Menyesuaikan validasi hanya untuk data vendor.
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'jenis_toko' => 'nullable|string|max:255',
            'daerah' => 'nullable|string|max:255',
            'nama_bank' => 'nullable|string|max:255',
            'nomor_rekening' => 'nullable|string|max:255',
        ]);

        Tagihan::create($request->all());

        // Perubahan: Mengubah pesan sukses.
        return redirect()->route('tagihan.index')
                         ->with('success', 'Data vendor berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data vendor.
     */
    public function edit(Tagihan $tagihan)
    {
        // Perubahan: Tidak perlu lagi mengambil data proyek.
        return view('tagihan.edit', compact('tagihan'));
    }

    /**
     * Memperbarui data vendor di database.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        // Perubahan: Menyesuaikan validasi hanya untuk data vendor.
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'jenis_toko' => 'nullable|string|max:255',
            'daerah' => 'nullable|string|max:255',
            'nama_bank' => 'nullable|string|max:255',
            'nomor_rekening' => 'nullable|string|max:255',
        ]);

        $tagihan->update($request->all());

        // Perubahan: Mengubah pesan sukses.
        return redirect()->route('tagihan.index')
                         ->with('success', 'Data vendor berhasil diperbarui.');
    }

    /**
     * Menghapus data vendor dari database.
     */
    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        // Perubahan: Mengubah pesan sukses.
        return redirect()->route('tagihan.index')
                         ->with('success', 'Data vendor berhasil dihapus.');
    }

    // Perubahan: Method tandaiLunas() dihapus karena tidak relevan lagi.
}
