<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Pengeluaran;
use App\Models\Tukang;
use App\Models\Tagihan; // Import model Tagihan
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class InputdataController extends Controller
{
    /**
     * LANGKAH 1: Menampilkan halaman upload awal.
     */
    public function index()
    {
        $proyeks = Proyek::orderBy('nama_proyek', 'asc')->get();
        
        $vendors = Tagihan::select('nama_vendor')
                          ->whereNotNull('nama_vendor')
                          ->distinct()
                          ->orderBy('nama_vendor', 'asc')
                          ->get();

        return view('inputdata.index', compact('proyeks', 'vendors'));
    }

    /**
     * [DIUBAH - LANGKAH 1B]
     * Mem-parsing file CSV, menyimpan ke session (secara terpisah), dan redirect ke halaman review.
     */
    public function parseUpload(Request $request)
    {
        $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
            'nama_vendor' => 'nullable|string|required_with:file_pengeluaran', 
            'file_pengeluaran' => 'nullable|file|mimes:csv,txt|required_without:file_tukang',
            'file_tukang' => 'nullable|file|mimes:csv,txt|required_without:file_pengeluaran',
        ], [
            'file_pengeluaran.required_without' => 'Anda harus mengunggah setidaknya file pengeluaran atau file tukang.',
            'file_tukang.required_without' => 'Anda harus mengunggah setidaknya file pengeluaran atau file tukang.',
            'nama_vendor.required_with' => 'Anda harus memilih Vendor jika ingin mengunggah file pengeluaran.',
        ]);

        $idProyek = $request->input('id_proyek');
        $namaVendor = $request->input('nama_vendor');
        
        // [DIUBAH] Buat dua array terpisah
        $pengeluaranData = []; 
        $tukangData = [];

        try {
            // --- Proses file Pengeluaran (Jika ada) ---
            if ($request->hasFile('file_pengeluaran')) {
                $path = $request->file('file_pengeluaran')->getRealPath();
                $file = fopen($path, 'r');
                fgetcsv($file); // Lewati header

                while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {
                    if (count(array_filter($row)) == 0) continue;
                    
                    // [DIUBAH] Normalisasi data ke format PENGELUARAN
                    $pengeluaranData[] = [
                        'toko' => $namaVendor,
                        'total' => $row[0] ?? 0,
                        'items' => $row[1] ?? null,
                        'tanggal_struk' => $row[2] ?? null,
                        'status_bayar' => $row[3] ?? 'sudah bayar',
                        'tanggal_bayar' => $row[4] ?? null,
                    ];
                }
                fclose($file);
            }

            // --- Proses file Tukang (Jika ada) ---
            if ($request->hasFile('file_tukang')) {
                $path = $request->file('file_tukang')->getRealPath();
                $file = fopen($path, 'r');
                fgetcsv($file); // Lewati header

                while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {
                    if (count(array_filter($row)) == 0) continue;
                    
                    // [DIUBAH] Normalisasi data ke format TUKANG
                    $tukangData[] = [
                        'nama_tukang' => $row[0] ?? null,
                        'nama_mandor' => $row[1] ?? null,
                        'jumlah' => $row[2] ?? 0,
                        'jatuh_tempo' => $row[3] ?? null,
                        'status' => $row[4] ?? 'Belum Lunas',
                    ];
                }
                fclose($file);
            }

            if (empty($pengeluaranData) && empty($tukangData)) {
                return redirect()->route('inputdata.index')->with('error', 'File CSV kosong atau format tidak sesuai.');
            }

            // [DIUBAH] Simpan 2 array terpisah ke session
            $request->session()->put('pengeluaran_data', $pengeluaranData);
            $request->session()->put('tukang_data', $tukangData);
            $request->session()->put('id_proyek_import', $idProyek);

            return redirect()->route('inputdata.review');

        } catch (Exception $e) {
            Log::error('CSV Parse Error: ' . $e->getMessage());
            return redirect()->route('inputdata.index')->with('error', 'Gagal mem-parsing file CSV. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * [DIUBAH - LANGKAH 2A]
     * Menampilkan halaman review dengan 2 set data.
     */
    public function review(Request $request)
    {
        // [DIUBAH] Ambil 2 array data
        $pengeluaranData = $request->session()->get('pengeluaran_data', []);
        $tukangData = $request->session()->get('tukang_data', []);
        $idProyek = $request->session()->get('id_proyek_import');
        
        if (empty($pengeluaranData) && empty($tukangData)) {
            return redirect()->route('inputdata.index')->with('error', 'Tidak ada data untuk di-review. Silakan upload ulang.');
        }

        $proyek = Proyek::find($idProyek);
        if (!$proyek) {
            $request->session()->forget(['pengeluaran_data', 'tukang_data', 'id_proyek_import']);
            return redirect()->route('inputdata.index')->with('error', 'Proyek tidak ditemukan. Sesi import telah dibatalkan.');
        }

        // [DIUBAH] Kirim 2 array data ke view
        return view('inputdata.review', compact('pengeluaranData', 'tukangData', 'idProyek', 'proyek'));
    }

    /**
     * [DIUBAH - LANGKAH 2B]
     * Memproses data dari 2 form terpisah.
     */
    public function process(Request $request)
    {
        // [DIUBAH] Ambil 2 array dari form
        $rowsPengeluaran = $request->input('rows_pengeluaran', []);
        $rowsTukang = $request->input('rows_tukang', []);
        $idProyek = $request->input('id_proyek');

        if (empty($rowsPengeluaran) && empty($rowsTukang)) {
            return redirect()->route('inputdata.index')->with('error', 'Tidak ada data yang diproses.');
        }

        $counters = ['pengeluaran' => 0, 'tukang' => 0];
        $errors = [];

        DB::beginTransaction();
        try {
            // --- Loop 1: Proses Pengeluaran ---
            foreach ($rowsPengeluaran as $index => $row) {
                $lineNumber = $index + 1;
                try {
                    Pengeluaran::create([
                        'id_proyek' => $idProyek,
                        'toko' => $row['toko'],
                        'total' => (float) $row['total'],
                        'items' => $row['items'],
                        'tanggal_struk' => Carbon::parse($row['tanggal_struk'])->format('Y-m-d'),
                        'status_bayar' => $row['status_bayar'] ?? 'sudah bayar',
                        'tanggal_bayar' => isset($row['tanggal_bayar']) ? Carbon::parse($row['tanggal_bayar'])->format('Y-m-d') : null,
                        'waktu_input' => Carbon::now()->format('H:i:s'),
                    ]);
                    $counters['pengeluaran']++;
                } catch (Exception $e) {
                    $errors[] = "Gagal memproses baris Pengeluaran {$lineNumber}: ". $e->getMessage();
                    Log::error("CSV Process Error (Pengeluaran {$lineNumber}): " . $e->getMessage(), ['row' => $row]);
                }
            }

            // --- Loop 2: Proses Tukang ---
            foreach ($rowsTukang as $index => $row) {
                $lineNumber = $index + 1;
                try {
                    Tukang::create([
                        'id_proyek' => $idProyek,
                        'nama_tukang' => $row['nama_tukang'],
                        'nama_mandor' => $row['nama_mandor'],
                        'jumlah' => (float) $row['jumlah'],
                        'jatuh_tempo' => Carbon::parse($row['jatuh_tempo'])->format('Y-m-d'),
                        'status' => $row['status'] ?? 'Belum Lunas',
                    ]);
                    $counters['tukang']++;
                } catch (Exception $e) {
                    $errors[] = "Gagal memproses baris Tukang {$lineNumber}: ". $e->getMessage();
                    Log::error("CSV Process Error (Tukang {$lineNumber}): " . $e->getMessage(), ['row' => $row]);
                }
            }

            DB::commit();
            $request->session()->forget(['pengeluaran_data', 'tukang_data', 'id_proyek_import']);

            $successMessage = "Impor Selesai! Berhasil mengimpor: 
                              {$counters['pengeluaran']} Pengeluaran, 
                              {$counters['tukang']} Upah Tukang.";

            if (count($errors) > 0) {
                session()->flash('csv_errors', $errors);
            }
            return redirect()->route('inputdata.index')->with('success', $successMessage);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CSV Process Gagal Total: ' . $e->getMessage());
            return redirect()->route('inputdata.index')->with('error', 'Terjadi kesalahan besar saat menyimpan data. Tidak ada data yang diimpor. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Memberikan file template CSV untuk diunduh. (Tidak berubah)
     */
    public function downloadTemplate($template)
    {
        // ... (Fungsi ini tidak perlu diubah dari versi Anda sebelumnya)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_' . $template . '.csv"',
        ];

        $content = '';
        switch ($template) {
            case 'pengeluaran':
                $content = "total,items,tanggal_struk(Y-m-d),status_bayar,tanggal_bayar(Y-m-d)";
                break;
            case 'tukang':
                $content = "nama_tukang,nama_mandor,jumlah,jatuh_tempo(Y-m-d),status";
                break;
            default:
                abort(404, 'Template not found.');
        }

        return response($content, 200, $headers);
    }
}

