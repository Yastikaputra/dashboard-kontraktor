<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index()
    {
        $proyeks = Proyek::withSum('pengeluarans as total_pengeluaran', 'total')
                         ->latest()
                         ->paginate(10);
                         
        return view('proyek.index', compact('proyeks'));
    }

    /**
     * [BARU] Method untuk menampilkan halaman detail proyek
     */
    public function show(Proyek $proyek)
    {
        // Memuat data pengeluaran yang terkait dengan proyek ini
        $proyek->load('pengeluarans');
        return view('proyek.show', compact('proyek'));
    }

    public function create()
    {
        return view('proyek.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek'    => 'required|string|max:255',
            'klien'          => 'required|string|max:255',
            'nilai_kontrak'  => 'required|numeric',
            'tanggal_mulai'  => 'required|date',
            'target_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'pic'            => 'required|string|max:255',
            'no_pic'         => 'required|string|max:20',
            'deskripsi'      => 'nullable|string', // Validasi untuk deskripsi
        ]);

        $data = $request->all();
        $data['status'] = 'Sedang Berjalan';

        Proyek::create($data);

        return redirect()->route('proyek.index')
                         ->with('success', 'Proyek baru berhasil ditambahkan.');
    }

    public function edit(Proyek $proyek)
    {
        return view('proyek.edit', compact('proyek'));
    }

    public function update(Request $request, Proyek $proyek)
    {
        $request->validate([
            'nama_proyek'    => 'required|string|max:255',
            'klien'          => 'required|string|max:255',
            'nilai_kontrak'  => 'required|numeric|min:0',
            'tanggal_mulai'  => 'required|date',
            'target_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'         => 'required|string',
            'pic'            => 'required|string|max:255',
            'no_pic'         => 'required|string|max:20',
            'deskripsi'      => 'nullable|string', // Validasi untuk deskripsi
        ]);
        
        $proyek->update($request->all());

        return redirect()->route('proyek.index')
                         ->with('success', 'Data proyek berhasil diperbarui.');
    }

    public function destroy(Proyek $proyek)
    {
        if ($proyek->pengeluarans()->count() > 0) {
            return redirect()->route('proyek.index')
                             ->with('error', 'Proyek tidak bisa dihapus karena memiliki data pengeluaran.');
        }

        $proyek->delete();

        return redirect()->route('proyek.index')
                         ->with('success', 'Proyek berhasil dihapus.');
    }
    
    public function tandaiSelesai(Proyek $proyek)
    {
        $proyek->status = 'Selesai';
        $proyek->save();

        return redirect()->route('proyek.index')->with('success', 'Status proyek berhasil diubah menjadi Selesai.');
    }
}

