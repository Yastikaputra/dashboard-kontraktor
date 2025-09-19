<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proyeks = Proyek::latest()->paginate(10);
        return view('proyek.index', compact('proyeks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proyek.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'klien' => 'required|string|max:255',
            'nilai_kontrak' => 'required|numeric',
            'tanggal_mulai' => 'required|date',
            'target_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|string',
            'pic' => 'required|string|max:255',
        ]);

        Proyek::create($request->all());

        return redirect()->route('proyek.index')
                         ->with('success', 'Proyek baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyek $proyek)
    {
        return view('proyek.edit', compact('proyek'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyek $proyek)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'klien' => 'required|string|max:255',
            'nilai_kontrak' => 'required|numeric',
            'tanggal_mulai' => 'required|date',
            'target_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|string',
            'pic' => 'required|string|max:255',
        ]);

        $proyek->update($request->all());

        return redirect()->route('proyek.index')
                         ->with('success', 'Data proyek berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyek $proyek)
    {
        $proyek->delete();

        return redirect()->route('proyek.index')
                         ->with('success', 'Proyek berhasil dihapus.');
    }

    /**
     * Tandai proyek sebagai Selesai.
     */
    public function tandaiSelesai($id)
    {
        $proyek = Proyek::findOrFail($id);
        $proyek->status = 'Selesai';
        $proyek->save();

        return redirect()->route('proyek.index')->with('success', 'Status proyek berhasil diubah menjadi Selesai.');
    }
}

