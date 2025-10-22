<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Keuangan Proyek</title>
    <style>
        /* Pengaturan Font Dasar */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        /* Header Dokumen */
        .header-table {
            width: 100%;
            border-bottom: 2.5px solid #0d6efd;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }
        .header-table td {
            padding: 0;
            vertical-align: top;
        }
        .company-details h2 {
            margin: 0;
            color: #0d6efd;
            font-size: 26px;
            font-weight: bold;
        }
        .company-details p {
            margin: 0;
            font-size: 11px;
            color: #555;
        }
        .report-details {
            text-align: right;
        }
        .report-details h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }
        .report-details p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }

        /* Tabel Utama */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th, .main-table td {
            padding: 10px;
            text-align: left;
        }

        /* Header Tabel */
        .main-table thead th {
            background-color: #0d6efd;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            border: none;
            text-transform: uppercase;
        }

        /* --- [PERUBAHAN] STYLING UNTUK EFEK GRID --- */
        
        /* Menghapus border-bottom default di setiap sel */
        .main-table td {
            border-bottom: none;
        }

        /* Baris data utama */
        .main-data-row td {
            background-color: #f8f9fa;
            padding-bottom: 5px; /* Kurangi padding bawah agar lebih rapat dengan rincian */
        }
        
        /* Baris untuk rincian, sekarang menjadi bagian bawah dari "grid" */
        .details-row td {
            padding: 10px 15px 12px 35px; 
            background-color: #f8f9fa !important;
            font-size: 10px;
            color: #495057;
            line-height: 1.5;
            /* Garis tebal di bawah sebagai pemisah antar item */
            border-bottom: 2px solid #d8dde1;
        }
        .details-label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        /* Footer Tabel (Total) */
        .main-table tfoot .total-row td {
            font-weight: bold;
            font-size: 14px;
            background-color: #e9ecef;
            border-top: 2.5px solid #343a40;
            color: #343a40;
        }

        /* Footer Halaman */
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            border-top: 1px solid #ccc;
            font-size: 9px;
            color: #777;
            line-height: 40px;
        }
        .page-number:before {
            content: "Halaman " counter(page);
        }
    </style>
</head>
<body>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem Tohjaya Contractor | <span class="page-number"></span>
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <div class="company-details">
                    <h2>Tohjaya Contractor</h2>
                    <p>Jl. Gajah Mada No. 1, Buleleng, Bali</p>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="report-details">
                    <h1>Laporan Keuangan</h1>
                    <p><strong>Proyek:</strong> {{ $namaProyek }}</p>
                    <p><strong>Tanggal Cetak:</strong> {{ $tanggalCetak }}</p>
                </div>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 35%;">Sumber (Toko/Vendor)</th>
                <th style="width: 12%;">Jenis</th>
                <th style="width: 16%;">Status Bayar</th>
                <th class="text-right" style="width: 25%;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                @php
                    // Logika untuk menentukan Jenis dan Status dinamis
                    $jenis = 'Pengeluaran';
                    $statusText = $item->status_bayar;

                    if (strtolower($item->status_bayar) == 'belum bayar' && $item->tanggal_bayar) {
                        $dueDate = \Carbon\Carbon::parse($item->tanggal_bayar)->startOfDay();
                        $now = \Carbon\Carbon::now()->startOfDay();
                        $diff = $now->diffInDays($dueDate, false);

                        if ($diff < 0) {
                            $jenis = 'Jatuh Tempo';
                            $statusText = 'Jatuh Tempo';
                        } elseif ($diff <= 5) {
                            $jenis = 'Tagihan';
                            $statusText = 'Segera Jatuh Tempo';
                        }
                    }
                @endphp
                {{-- [DIUBAH] Menambahkan class 'main-data-row' --}}
                <tr class="main-data-row">
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_struk)->format('d/m/Y') }}</td>
                    <td>{{ $item->toko }}</td>
                    <td>{{ $jenis }}</td>
                    <td>{{ $statusText }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($item->total, 0, ',', '.') }}</strong></td>
                </tr>
                <tr class="details-row">
                    <td colspan="5">
                        <span class="details-label">Rincian:</span>
                        {!! nl2br(e($item->items)) !!}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">Tidak ada data pengeluaran yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>TOTAL KESELURUHAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
