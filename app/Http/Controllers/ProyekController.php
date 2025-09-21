<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    /**
     * Menampilkan daftar semua proyek.
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
     * Menyimpan proyek baru ke database dengan status default.
     */
    public function store(Request $request)
    {
        // Validasi tanpa 'status'
        $request->validate([
            'nama_proyek'    => 'required|string|max:255',
            'klien'          => 'required|string|max:255',
            'nilai_kontrak'  => 'required|numeric',
            'tanggal_mulai'  => 'required|date',
            'target_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'pic'            => 'required|string|max:255',
            'no_pic'         => 'required|string|max:20',
        ]);

        // Tambahkan status "Sedang Berjalan" secara otomatis
        $data = $request->all();
        $data['status'] = 'Sedang Berjalan';

        Proyek::create($data);

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
        // Validasi dengan 'status' karena bisa diubah saat edit
        $request->validate([
            'nama_proyek'    => 'required|string|max:255',
            'klien'          => 'required|string|max:255',
            'nilai_kontrak'  => 'required|numeric|min:0',
            'tanggal_mulai'  => 'required|date',
            'target_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'         => 'required|string', // Validasi status ditambahkan di sini
            'pic'            => 'required|string|max:255',
            'no_pic'         => 'required|string|max:20',
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
        // Disarankan menambahkan pengecekan sebelum menghapus
        if ($proyek->pengeluarans()->count() > 0) {
            return redirect()->route('proyek.index')
                             ->with('error', 'Proyek tidak bisa dihapus karena memiliki data pengeluaran.');
        }

        $proyek->delete();

        return redirect()->route('proyek.index')
                         ->with('success', 'Proyek berhasil dihapus.');
    }

    /**
     * Menandai proyek sebagai "Selesai".
     * Menerima $id dari rute /proyek/{id}/selesai
     */
    public function tandaiSelesai($id)
    {
        $proyek = Proyek::findOrFail($id);
        $proyek->status = 'Selesai';
        $proyek->save();

        return redirect()->route('proyek.index')->with('success', 'Status proyek berhasil diubah menjadi Selesai.');
    }
}