<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Proyek;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan halaman utama laporan keuangan dengan filter.
     */
    public function index(Request $request)
    {
        // Panggil method private untuk mengambil data yang sudah difilter
        $items = $this->getFilteredData($request);

        // Data untuk dropdown filter
        $proyeks = Proyek::orderBy('nama_proyek')->get();
        $listSupplier = $this->getSupplierList();

        return view('pengeluaran.index', compact('items', 'proyeks', 'listSupplier'));
    }

    /**
     * Method untuk generate dan download PDF.
     */
    public function exportPDF(Request $request)
    {
        // Panggil method private yang sama untuk konsistensi data
        $items = $this->getFilteredData($request);

        // Ambil nama proyek dari filter untuk judul PDF
        $namaProyek = 'Semua Proyek';
        if ($request->filled('proyek_id')) {
            $proyekData = Proyek::find($request->proyek_id);
            if ($proyekData) {
                $namaProyek = $proyekData->nama_proyek;
            }
        }
        
        $data = [
            'items' => $items,
            'namaProyek' => $namaProyek,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
            'totalNominal' => $items->sum('nominal')
        ];
        
        $fileName = 'Laporan_Keuangan_' . str_replace(' ', '_', $namaProyek) . '.pdf';
        
        $pdf = PDF::loadView('pengeluaran.pdf', $data);
        return $pdf->download($fileName);
    }

    /**
     * Method private terpusat untuk mengambil dan memfilter data.
     */
    private function getFilteredData(Request $request)
    {
        // Query dasar dengan Eager Loading relasi 'proyek' sejak awal
        $pengeluaranQuery = Pengeluaran::with('proyek');
        $tagihanQuery = Tagihan::with('proyek');

        // Filter berdasarkan Proyek
        if ($request->filled('proyek_id')) {
            $pengeluaranQuery->where('id_proyek', $request->proyek_id);
            $tagihanQuery->where('id_proyek', $request->proyek_id);
        }

        // Filter berdasarkan Periode Tanggal
        if ($request->filled('periode_mulai') && $request->filled('periode_selesai')) {
            $pengeluaranQuery->whereBetween('tanggal_struk', [$request->periode_mulai, $request->periode_selesai]);
            $tagihanQuery->whereBetween('tanggal_tagihan', [$request->periode_mulai, $request->periode_selesai]);
        }

        // Filter berdasarkan Supplier / Toko
        if ($request->filled('supplier')) {
            $pengeluaranQuery->where('toko', $request->supplier);
            $tagihanQuery->where('nama_vendor', $request->supplier);
        }

        // Ambil data
        $pengeluarans = $pengeluaranQuery->get();
        $tagihans = $tagihanQuery->get();

        // Filter berdasarkan Status Pembayaran
        if ($request->filled('status_bayar')) {
            if ($request->status_bayar == 'Sudah Bayar') {
                $tagihans = $tagihans->where('status_bayar', 'Lunas');
            } elseif ($request->status_bayar == 'Belum Bayar') {
                $pengeluarans = collect();
                $tagihans = $tagihans->where('status_bayar', '!=', 'Lunas');
            }
        }

        // Normalisasi data
        $this->formatData($pengeluarans, 'Pengeluaran');
        $this->formatData($tagihans, 'Tagihan');

        // Gabungkan dan urutkan
        return $pengeluarans->concat($tagihans)->sortByDesc('tanggal');
    }


    // --- CRUD Methods ---
    
    public function create()
    {
        $proyeks = Proyek::where('status', '!=', 'Selesai')->orderBy('nama_proyek')->get();
        return view('pengeluaran.create', compact('proyeks'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
            'toko' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'items' => 'required|string',
            'tanggal_struk' => 'required|date',
            'waktu_input' => 'required',
            'bukti_struk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('bukti_struk')) {
            $validatedData['bukti_struk'] = $request->file('bukti_struk')->store('bukti_struk', 'public');
        }

        Pengeluaran::create($validatedData);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $proyeks = Proyek::orderBy('nama_proyek')->get();
        return view('pengeluaran.edit', compact('pengeluaran', 'proyeks'));
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
        ]);

        if ($request->hasFile('bukti_struk')) {
            if ($pengeluaran->bukti_struk) {
                Storage::disk('public')->delete($pengeluaran->bukti_struk);
            }
            $validatedData['bukti_struk'] = $request->file('bukti_struk')->store('bukti_struk', 'public');
        }

        $pengeluaran->update($validatedData);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->bukti_struk) {
            Storage::disk('public')->delete($pengeluaran->bukti_struk);
        }
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    // --- Helper Functions ---

    private function formatData($collection, $type)
    {
        $collection->each(function($item) use ($type) {
            $item->jenis = $type;
            if ($type == 'Pengeluaran') {
                $item->sumber = $item->toko;
                $item->nominal = $item->total;
                $item->tanggal = $item->tanggal_struk;
                $item->status_pembayaran = 'Sudah Bayar';
                // [DIUBAH] Tambahkan detail item untuk Pengeluaran
                $item->detail_items = $item->items; 
            } else { // Tagihan
                $item->sumber = $item->nama_vendor;
                $item->nominal = $item->nilai_tagihan;
                $item->tanggal = $item->tanggal_tagihan;
                $item->status_pembayaran = $item->status_bayar;
                // [DIUBAH] Tambahkan detail item untuk Tagihan
                $item->detail_items = $item->deskripsi;
            }
        });
    }

    private function getSupplierList()
    {
        $suppliers = Tagihan::select('nama_vendor')->distinct()->pluck('nama_vendor');
        $tokos = Pengeluaran::select('toko')->distinct()->pluck('toko');
        return $suppliers->concat($tokos)->unique()->sort();
    }
}

