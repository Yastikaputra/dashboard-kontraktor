<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Proyek;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $items = $this->getFilteredData($request);
        $proyeks = Proyek::orderBy('nama_proyek')->get();
        $listVendor = $this->getVendorList();

        // [BARU] Logika untuk mengambil data ringkasan proyek yang dipilih
        $selectedProyekData = null;
        if ($request->filled('proyek_id')) {
            // Ambil proyek yang dipilih, hitung total pengeluaran dan total upah
            $selectedProyek = Proyek::withSum('pengeluarans as total_pengeluaran', 'total')
                                    ->withSum('tukangs as total_upah', 'jumlah')
                                    ->find($request->proyek_id);

            if ($selectedProyek) {
                // Hitung total biaya dan sisa dana
                $totalBiayaProyek = $selectedProyek->total_pengeluaran + $selectedProyek->total_upah;
                $sisaDana = $selectedProyek->nilai_kontrak - $totalBiayaProyek;

                $selectedProyekData = [
                    'nama_proyek' => $selectedProyek->nama_proyek,
                    'total_nilai_kontrak' => $selectedProyek->nilai_kontrak,
                    'sisa_dana' => $sisaDana,
                ];
            }
        }
        // [AKHIR BARU]

        // [DIUBAH] Tambahkan 'selectedProyekData' ke compact
        return view('pengeluaran.index', compact('items', 'proyeks', 'listVendor', 'selectedProyekData'));
    }

    private function getFilteredData(Request $request)
    {
        // ... (sisa method getFilteredData tetap sama) ...
        $query = Pengeluaran::with('proyek')->orderBy('tanggal_struk', 'desc');

        if ($request->filled('proyek_id')) { $query->where('id_proyek', $request->proyek_id); }
        if ($request->filled('periode_mulai') && $request->filled('periode_selesai')) { $query->whereBetween('tanggal_struk', [$request->periode_mulai, $request->periode_selesai]); }
        if ($request->filled('vendor')) { $query->where('toko', $request->vendor); }
        if ($request->filled('status_bayar')) { $query->where('status_bayar', $request->status_bayar); }
        
        $items = $query->get();

        if ($request->filled('jenis')) {
            $jenisFilter = $request->jenis;
            $now = Carbon::now();
            $items = $items->filter(function ($item) use ($jenisFilter, $now) {
                $isTagihan = strtolower($item->status_bayar) == 'belum bayar' && $item->tanggal_bayar && $now->diffInDays(Carbon::parse($item->tanggal_bayar), false) >= 0 && $now->diffInDays(Carbon::parse($item->tanggal_bayar), false) <= 5;
                if ($jenisFilter == 'Tagihan') { return $isTagihan; }
                if ($jenisFilter == 'Pengeluaran') { return !$isTagihan; }
            });
        }
        
        return $items;
    }

    // ... (sisa controller tetap sama) ...
    private function getVendorList()
    {
        $vendorsFromTagihan = Tagihan::select('nama_vendor')->distinct()->pluck('nama_vendor');
        $vendorsFromPengeluaran = Pengeluaran::select('toko')->distinct()->pluck('toko');
        return $vendorsFromTagihan->concat($vendorsFromPengeluaran)->unique()->sort();
    }
    
    public function create()
    {
        $proyeks = Proyek::where('status', '!=', 'Selesai')->orderBy('nama_proyek')->get();
        $vendors = $this->getVendorList();
        return view('pengeluaran.create', compact('proyeks', 'vendors'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
            'toko' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'items' => 'required|string',
            'tanggal_struk' => 'required|date',
            'bukti_struk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_bayar' => 'required|in:Sudah Bayar,Belum Bayar',
            'tanggal_bayar' => 'nullable|required_if:status_bayar,Belum Bayar|date|after_or_equal:tanggal_struk',
        ]);
        
        $dataToSave = $validatedData;
        $dataToSave['waktu_input'] = now()->format('H:i:s');
        
        if ($request->hasFile('bukti_struk')) {
            $dataToSave['bukti_struk'] = $request->file('bukti_struk')->store('bukti_struk', 'public');
        }

        if ($dataToSave['status_bayar'] == 'Sudah Bayar') {
            $dataToSave['tanggal_bayar'] = $dataToSave['tanggal_struk'];
        } else {
            $dataToSave['tanggal_bayar'] = $request->tanggal_bayar;
        }

        Pengeluaran::create($dataToSave);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $proyeks = Proyek::orderBy('nama_proyek')->get();
        $vendors = $this->getVendorList();
        return view('pengeluaran.edit', compact('pengeluaran', 'proyeks', 'vendors'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validatedData = $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
            'toko' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'items' => 'required|string',
            'tanggal_struk' => 'required|date',
            'waktu_input' => 'required',
            'bukti_struk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_bayar' => 'required|in:Sudah Bayar,Belum Bayar',
            'tanggal_bayar' => 'nullable|required_if:status_bayar,Belum Bayar|date|after_or_equal:tanggal_struk',
        ]);
        
        $dataToUpdate = $validatedData;
        
        if ($request->hasFile('bukti_struk')) {
            if ($pengeluaran->bukti_struk) { Storage::disk('public')->delete($pengeluaran->bukti_struk); }
            $dataToUpdate['bukti_struk'] = $request->file('bukti_struk')->store('bukti_struk', 'public');
        }
        
        if ($dataToUpdate['status_bayar'] == 'Sudah Bayar') {
            $dataToUpdate['tanggal_bayar'] = $dataToUpdate['tanggal_struk'];
        } else {
            $dataToUpdate['tanggal_bayar'] = $request->tanggal_bayar;
        }

        $pengeluaran->update($dataToUpdate);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->bukti_struk) { Storage::disk('public')->delete($pengeluaran->bukti_struk); }
        $pengeluaran->delete();
        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    public function exportPDF(Request $request)
    {
        $items = $this->getFilteredData($request); 
        $namaProyek = 'Semua Proyek';
        if ($request->filled('proyek_id')) {
            $proyekData = Proyek::find($request->proyek_id);
            if ($proyekData) { $namaProyek = $proyekData->nama_proyek; }
        }
        $data = [
            'items' => $items, 'namaProyek' => $namaProyek,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
            'totalNominal' => $items->sum('total') 
        ];
        $fileName = 'Laporan_Pengeluaran_' . str_replace(' ', '_', $namaProyek) . '.pdf';
        $pdf = PDF::loadView('pengeluaran.pdf', $data); 
        return $pdf->download($fileName);
    }
}