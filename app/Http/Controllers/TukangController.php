<?php

namespace App\Http\Controllers;

use App\Models\Tukang;
use App\Models\Proyek;
use Illuminate\Http\Request;

class TukangController extends Controller
{
    /**
     * Menampilkan daftar semua data tukang.
     */
    public function index()
    {
        $tukangs = Tukang::with('proyek')->latest()->paginate(10);
        return view('tukang.index', compact('tukangs'));
    }

    /**
     * Menampilkan form untuk menambah data tukang baru.
     */
    public function create()
    {
        $proyeks = Proyek::where('status', '!=', 'Selesai')->orderBy('nama_proyek')->get();
        return view('tukang.create', compact('proyeks'));
    }

    /**
     * Menyimpan data tukang baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
            'nama_tukang' => 'required|string|max:255',
            'nama_mandor' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
        ]);

        $data = $request->all();
        $data['status'] = 'Belum Lunas';

        Tukang::create($data);

        return redirect()->route('tukang.index')
                         ->with('success', 'Data tukang berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data tukang.
     */
    public function edit(Tukang $tukang)
    {
        $proyeks = Proyek::orderBy('nama_proyek')->get();
        return view('tukang.edit', compact('tukang', 'proyeks'));
    }

    /**
     * Memperbarui data tukang di database.
     */
    public function update(Request $request, Tukang $tukang)
    {
        $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
            'nama_tukang' => 'required|string|max:255',
            'nama_mandor' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
            'status' => 'required|in:Lunas,Belum Lunas',
        ]);

        $tukang->update($request->all());

        return redirect()->route('tukang.index')
                         ->with('success', 'Data tukang berhasil diperbarui.');
    }

    /**
     * Menghapus data tukang dari database.
     */
    public function destroy(Tukang $tukang)
    {
        $tukang->delete();
        return redirect()->route('tukang.index')
                         ->with('success', 'Data tukang berhasil dihapus.');
    }
    
    /**
     * Menandai upah tukang sebagai lunas.
     */
    public function tandaiLunas(Tukang $tukang)
    {
        // 1. Ubah nilai kolom status
        $tukang->status = 'Lunas';
        
        // 2. Simpan perubahan ke database
        $tukang->save();

        // 3. Kembali ke halaman index dengan notifikasi
        return redirect()->route('tukang.index')->with('success', 'Upah tukang berhasil ditandai lunas.');
    }
}