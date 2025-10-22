<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;
// [PENTING] Tambahkan ini untuk membuat file CSV
use Symfony\Component\HttpFoundation\StreamedResponse; 

// [DIHAPUS] Kita tidak pakai Excel atau ProyekExport lagi
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\ProyekExport;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan proyek dengan filter.
     * (Method ini tidak berubah)
     */
    public function index(Request $request)
    {
        $all_proyeks = Proyek::select('id_proyek', 'nama_proyek')->orderBy('nama_proyek', 'asc')->get();

        $query = Proyek::with(['pengeluarans', 'tukangs'])
                       ->withSum('pengeluarans as total_pengeluaran', 'total')
                       ->withSum('tukangs as total_upah', 'jumlah');

        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('proyek_id') && $request->proyek_id != 'semua') {
            $query->where('id_proyek', $request->proyek_id);
        }

        $proyeks = $query->orderBy('tanggal_mulai', 'desc')->get();

        $summary = [
            'total_nilai_kontrak' => $proyeks->sum('nilai_kontrak'),
            'total_biaya_proyek' => $proyeks->sum('total_pengeluaran') + $proyeks->sum('total_upah'),
        ];
        
        $summary['total_profit_loss'] = $summary['total_nilai_kontrak'] - $summary['total_biaya_proyek'];

        return view('report.index', compact('proyeks', 'summary', 'all_proyeks'));
    }

    /**
     * [DIUBAH TOTAL] Menangani ekspor ke CSV menggunakan PHP bawaan.
     * * @param string $id_proyek
     */
    public function export($id_proyek)
    {
        // 1. Ambil data proyek (sama seperti sebelumnya)
        $proyek = Proyek::with(['pengeluarans', 'tukangs'])
                       ->withSum('pengeluarans as total_pengeluaran', 'total')
                       ->withSum('tukangs as total_upah', 'jumlah')
                       ->findOrFail($id_proyek);
        
        // 2. Siapkan nama file, ganti ekstensi menjadi .csv
        $nama_file = 'Laporan Proyek - ' . preg_replace('/[^A-Za-z0-9\-]/', '', $proyek->nama_proyek) . '.csv';

        // 3. Siapkan header untuk download CSV
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $nama_file . '"',
        ];

        // 4. Buat callback untuk streaming data CSV
        $callback = function() use ($proyek) {
            // Buka output stream PHP
            $file = fopen('php://output', 'w');

            // Tambahkan BOM untuk kompatibilitas Excel (opsional tapi disarankan)
            fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // --- Mulai Tulis Data CSV ---

            // Judul
            fputcsv($file, ['LAPORAN PROYEK:', $proyek->nama_proyek]);
            fputcsv($file, ['KLIEN:', $proyek->klien]);
            fputcsv($file, []); // Baris kosong

            // Ringkasan Finansial
            $totalBiaya = $proyek->total_pengeluaran + $proyek->total_upah;
            $profitLoss = $proyek->nilai_kontrak - $totalBiaya;

            fputcsv($file, ['NILAI KONTRAK', 'TOTAL BIAYA', 'PROFIT/LOSS']);
            fputcsv($file, [
                $proyek->nilai_kontrak,
                $totalBiaya,
                $profitLoss
            ]);
            fputcsv($file, []); // Baris kosong

            // Rincian Vendor
            fputcsv($file, ['--- RINCIAN VENDOR ---']);
            fputcsv($file, ['Toko/Vendor', 'Tanggal', 'Rincian', 'Total']);
            if ($proyek->pengeluarans->isNotEmpty()) {
                foreach ($proyek->pengeluarans as $p) {
                    fputcsv($file, [$p->toko, $p->tanggal_struk, $p->items, $p->total]);
                }
                fputcsv($file, ['TOTAL VENDOR', '', '', $proyek->total_pengeluaran]);
            } else {
                fputcsv($file, ['- Tidak ada data -']);
            }
            fputcsv($file, []); // Baris kosong

            // Rincian Tukang
            fputcsv($file, ['--- RINCIAN UPAH TUKANG ---']);
            fputcsv($file, ['Nama Tukang', 'Jatuh Tempo', 'Jumlah']);
            if ($proyek->tukangs->isNotEmpty()) {
                foreach ($proyek->tukangs as $t) {
                    fputcsv($file, [$t->nama_tukang, $t->jatuh_tempo, $t->jumlah]);
                }
                fputcsv($file, ['TOTAL UPAH', '', $proyek->total_upah]);
            } else {
                fputcsv($file, ['- Tidak ada data -']);
            }

            // Tutup file stream
            fclose($file);
        };

        // 5. Kembalikan response sebagai file download
        return new StreamedResponse($callback, 200, $headers);
    }
}