@extends('layouts.app')

@section('content')

@php
/**
 * Helper function untuk mem-parsing tanggal dengan aman di dalam view.
 * Mencegah error InvalidFormatException.
 */
function safeParseDateForInput($dateString) {
    if (empty($dateString)) {
        return '';
    }
    // Jika nilainya angka saja, itu bukan format tanggal yang valid untuk input.
    if (is_numeric($dateString)) {
        return ''; // Kembalikan string kosong
    }
    try {
        // Coba parse secara normal
        return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
    } catch (\Exception $e) {
        // Jika gagal (format tidak dikenal), kembalikan string kosong
        return '';
    }
}
@endphp

<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Review dan Penyesuaian Data</h1>
        @if($proyek)
            <p class="text-gray-500 mt-1">
                Data untuk proyek: <strong class="text-blue-600">{{ $proyek->nama_proyek }}</strong>
            </p>
        @endif
        <p class="text-gray-500 mt-1">
            Silakan periksa data di bawah ini. Anda dapat melakukan penyesuaian sebelum menyimpan.
        </p>
    </div>

    <form action="{{ route('inputdata.process') }}" method="POST">
        @csrf
        <input type="hidden" name="id_proyek" value="{{ $idProyek }}">

        {{-- =============================================== --}}
        {{-- FORM 1: DATA PENGELUARAN --}}
        {{-- =============================================== --}}
        @if (!empty($pengeluaranData))
        <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Review Data Pengeluaran</h2>
                <p class="text-sm text-gray-500 mt-1">Ditemukan {{ count($pengeluaranData) }} data pengeluaran untuk diimpor.</p>
            </div>
            
            <div class="overflow-x-auto p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toko</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Struk</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Bayar</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pengeluaranData as $index => $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2">
                                    <input type="text" name="rows_pengeluaran[{{ $index }}][toko]" value="{{ $row['toko'] ?? '' }}" class="w-40 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="rows_pengeluaran[{{ $index }}][items]" value="{{ $row['items'] ?? '' }}" class="w-48 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <input type="number" step="0.01" name="rows_pengeluaran[{{ $index }}][total]" value="{{ $row['total'] ?? '' }}" class="w-32 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{-- [DIUBAH] Menggunakan helper safeParseDateForInput --}}
                                    <input type="date" name="rows_pengeluaran[{{ $index }}][tanggal_struk]" value="{{ safeParseDateForInput($row['tanggal_struk'] ?? null) }}" class="w-36 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <select name="rows_pengeluaran[{{ $index }}][status_bayar]" class="w-36 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                        <option value="sudah bayar" @if(strtolower($row['status_bayar'] ?? '') == 'sudah bayar') selected @endif>Sudah Bayar</option>
                                        <option value="belum bayar" @if(strtolower($row['status_bayar'] ?? '') == 'belum bayar') selected @endif>Belum Bayar</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{-- [DIUBAH] Menggunakan helper safeParseDateForInput --}}
                                    <input type="date" name="rows_pengeluaran[{{ $index }}][tanggal_bayar]" value="{{ safeParseDateForInput($row['tanggal_bayar'] ?? null) }}" class="w-36 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif


        {{-- =============================================== --}}
        {{-- FORM 2: DATA TUKANG --}}
        {{-- =============================================== --}}
        @if (!empty($tukangData))
        <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Review Data Upah Tukang</h2>
                <p class="text-sm text-gray-500 mt-1">Ditemukan {{ count($tukangData) }} data tukang untuk diimpor.</p>
            </div>
            
            <div class="overflow-x-auto p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tukang</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mandor</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($tukangData as $index => $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2">
                                    <input type="text" name="rows_tukang[{{ $index }}][nama_tukang]" value="{{ $row['nama_tukang'] ?? '' }}" class="w-40 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="rows_tukang[{{ $index }}][nama_mandor]" value="{{ $row['nama_mandor'] ?? '' }}" class="w-40 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <input type="number" step="0.01" name="rows_tukang[{{ $index }}][jumlah]" value="{{ $row['jumlah'] ?? '' }}" class="w-32 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{-- [DIUBAH] Menggunakan helper safeParseDateForInput --}}
                                    <input type="date" name="rows_tukang[{{ $index }}][jatuh_tempo]" value="{{ safeParseDateForInput($row['jatuh_tempo'] ?? null) }}" class="w-36 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <select name="rows_tukang[{{ $index }}][status]" class="w-36 mt-1 block rounded-md border-gray-300 shadow-sm text-sm">
                                        <option value="Belum Lunas" @if(strtolower($row['status'] ?? '') == 'belum lunas') selected @endif>Belum Lunas</option>
                                        <option value="Lunas" @if(strtolower($row['status'] ?? '') == 'lunas') selected @endif>Lunas</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Tombol Aksi --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex justify-end gap-4">
                <a href="{{ route('inputdata.index') }}" 
                   class="bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 transition duration-300 text-base">
                   Batal
                </a>
                <button type="submit" 
                        class="w-auto bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition duration-300 text-base">
                    Simpan Semua Data Penyesuaian
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

