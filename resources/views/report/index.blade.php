@extends('layouts.app')

@section('content')
<div class="space-y-8">
    {{-- ... (Bagian Judul, Filter, dan Summary Card Anda tidak berubah) ... --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Laporan Proyek</h1>
        <p class="text-gray-500 mt-1">Dokumentasi dan ringkasan finansial dari seluruh proyek.</p>
    </div>

    {{-- ... (Filter Section - Salin dari kode Anda sebelumnya) ... --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <form action="{{ route('report.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                {{-- Filter Proyek --}}
                <div>
                    <label for="proyek_id" class="text-sm font-medium text-gray-700">Pilih Proyek</label>
                    <select name="proyek_id" id="proyek_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="semua">Semua Proyek</option>
                        @foreach($all_proyeks as $proyek)
                            <option value="{{ $proyek->id_proyek }}" {{ request('proyek_id') == $proyek->id_proyek ? 'selected' : '' }}>
                                {{ $proyek->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tanggal Mulai --}}
                <div>
                    <label for="tanggal_mulai" class="text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Filter Tanggal Selesai --}}
                <div>
                    <label for="tanggal_selesai" class="text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Filter Status --}}
                <div>
                    <label for="status" class="text-sm font-medium text-gray-700">Status Proyek</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Sedang Berjalan" {{ request('status') == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                {{-- Tombol Filter --}}
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Filter</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ... (Summary Cards Section - Salin dari kode Anda sebelumnya) ... --}}
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-green-50 p-6 rounded-xl border border-green-200">
            <h3 class="text-sm font-medium text-green-800">Total Nilai Kontrak</h3>
            <p class="text-2xl font-bold text-green-900 mt-1">Rp. {{ number_format($summary['total_nilai_kontrak'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 p-6 rounded-xl border border-red-200">
            <h3 class="text-sm font-medium text-red-800">Total Biaya Proyek</h3>
            <p class="text-2xl font-bold text-red-900 mt-1">Rp. {{ number_format($summary['total_biaya_proyek'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
            <h3 class="text-sm font-medium text-blue-800">Estimasi Profit / Loss</h3>
            <p class="text-2xl font-bold text-blue-900 mt-1">Rp. {{ number_format($summary['total_profit_loss'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- [PERUBAHAN UTAMA DI SINI] Bagian Tabel Laporan --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="w-12"></th> 
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Proyek</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Nilai Kontrak</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Biaya</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Profit/Loss</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                {{-- [PERUBAHAN UTAMA] @forelse dipindahkan KELUAR 
                     dan kita buat <tbody> untuk SETIAP proyek --}}
                @forelse($proyeks as $proyek)
                    @php
                        $totalBiaya = $proyek->total_pengeluaran + $proyek->total_upah;
                        $profitLoss = $proyek->nilai_kontrak - $totalBiaya;
                    @endphp
                    
                    {{-- Setiap proyek adalah <tbody>-nya sendiri, yang memegang state 'open' --}}
                    {{-- class "divide-y" dipindahkan dari <tbody> utama ke sini --}}
                    <tbody x-data="{ open: false }" class="divide-y divide-gray-200 border-b border-gray-200 last:border-b-0">
                        
                        {{-- Baris Klik (Sekarang di dalam <tbody> yang benar) --}}
                        <tr @click="open = !open" class="hover:bg-gray-50 transition cursor-pointer">
                            <td class="py-3 px-4 text-center">
                                <button title="Lihat Rincian" class="text-gray-400 hover:text-blue-600">
                                    <svg class="w-5 h-5 transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $proyek->nama_proyek }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $proyek->status == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $proyek->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">Rp. {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-red-600">Rp. {{ number_format($totalBiaya, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-bold {{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp. {{ number_format($profitLoss, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('report.export', $proyek->id_proyek) }}" 
                                   @click.stop 
                                   title="Ekspor ke Sheet" 
                                   class="text-green-600 hover:text-green-800 p-1 rounded-full hover:bg-green-100 inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V3zm2 2v10h10V5H5zM8 8a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1zm0 4a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
                                </a>
                            </td>
                        </tr>

                        {{-- Baris Rincian (Sekarang bisa melihat 'open' dari <tbody>) --}}
                        <tr x-show="open" x-transition x-cloak>
                            <td colspan="7" class="p-4 sm:p-6 bg-slate-50">
                                
                                {{-- Layout Grid 2 Kolom untuk Rincian --}}
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    
                                    {{-- Kolom 1: Rincian Vendor (Format Tabel Rapi) --}}
                                    <div class="bg-white p-4 border rounded-lg shadow-sm">
                                        <h4 class="font-semibold text-gray-800 border-b pb-2 mb-3">Rincian Pengeluaran Vendor</h4>
                                        @if($proyek->pengeluarans->isNotEmpty())
                                            <div class="overflow-auto max-h-60 text-sm">
                                                <table class="min-w-full">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="text-left font-medium text-gray-600 p-2">Toko/Vendor</th>
                                                            <th class="text-left font-medium text-gray-600 p-2">Tanggal</th>
                                                            <th class="text-left font-medium text-gray-600 p-2">Rincian</th>
                                                            <th class="text-right font-medium text-gray-600 p-2">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        @foreach($proyek->pengeluarans as $pengeluaran)
                                                            <tr class="align-top">
                                                                <td class="p-2 whitespace-nowrap">{{ $pengeluaran->toko }}</td>
                                                                <td class="p-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($pengeluaran->tanggal_struk)->format('d/m/y') }}</td>
                                                                <td class="p-2 whitespace-normal min-w-[200px]">{{ $pengeluaran->items }}</td>
                                                                <td class="p-2 text-right whitespace-nowrap"><strong>Rp. {{ number_format($pengeluaran->total, 0, ',', '.') }}</strong></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 mt-1">- Tidak ada data pengeluaran vendor -</p>
                                        @endif
                                    </div>

                                    {{-- Kolom 2: Rincian Tukang (Format Tabel Rapi) --}}
                                    <div class="bg-white p-4 border rounded-lg shadow-sm">
                                        <h4 class="font-semibold text-gray-800 border-b pb-2 mb-3">Rincian Upah Tukang</h4>
                                        @if($proyek->tukangs->isNotEmpty())
                                            <div class="overflow-auto max-h-60 text-sm">
                                                <table class="min-w-full">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="text-left font-medium text-gray-600 p-2">Nama Tukang</th>
                                                            <th class="text-left font-medium text-gray-600 p-2">Jatuh Tempo</th>
                                                            <th class="text-right font-medium text-gray-600 p-2">Jumlah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        @foreach($proyek->tukangs as $tukang)
                                                            <tr>
                                                                <td class="p-2 whitespace-nowakrap">{{ $tukang->nama_tukang }}</td>
                                                                <td class="p-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($tukang->jatuh_tempo)->format('d/m/y') }}</td>
                                                                <td class="p-2 text-right whitespace-nowrap"><strong>Rp. {{ number_format($tukang->jumlah, 0, ',', '.') }}</strong></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 mt-1">- Tidak ada data upah tukang -</p>
                                        @endif
                                    </div>

                                </div>
                            </td>
                        </tr>
                    </tbody> {{-- Tutup <tbody> untuk setiap proyek --}}

                @empty
                    {{-- [PERUBAHAN UTAMA] @empty sekarang punya <tbody> sendiri --}}
                    <tbody>
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <p class="text-lg">Tidak ada data proyek</p>
                                <p class="text-sm">Silakan ubah filter atau tambahkan data proyek baru.</p>
                            </td>
                        </tr>
                    </tbody>
                @endforelse
                
            </table>
        </div>
    </div>
</div>
@endsection