@extends('layouts.app')

@section('content')
{{-- Kontainer Alpine.js diletakkan di luar, hanya untuk mengontrol state --}}
<div x-data="{ showModal: false, modalContent: '' }">

    {{-- Semua konten halaman sekarang dibungkus dalam div terpisah --}}
    <div class="space-y-8"> 
        {{-- Header Halaman --}}
        <div class="flex justify-between items-center">
            {{-- ... (kode header halaman tetap sama) ... --}}
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Pengeluaran Proyek</h1>
                <p class="text-gray-500 mt-1">Lacak semua pengeluaran proyek Anda.</p>
            </div>
            <a href="{{ route('pengeluaran.create') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
                + Catat Pengeluaran
            </a>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            {{-- ... (kode notifikasi tetap sama) ... --}}
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Panel Filter --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            {{-- ... (kode form filter tetap sama) ... --}}
            <form id="filter-form" action="{{ route('pengeluaran.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-x-6 gap-y-4 items-end">
                    <div class="lg:col-span-2">
                        <label for="proyek_id" class="block text-sm font-medium text-gray-700">Proyek</label>
                        <select name="proyek_id" id="proyek_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                            <option value="">Semua Proyek</option>
                            @foreach($proyeks as $proyek)
                                <option value="{{ $proyek->id_proyek }}" {{ request('proyek_id') == $proyek->id_proyek ? 'selected' : '' }}>
                                    {{ $proyek->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label for="vendor" class="block text-sm font-medium text-gray-700">Toko / Vendor</label>
                        <select name="vendor" id="vendor" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                            <option value="">Semua Vendor</option>
                            @foreach($listVendor ?? [] as $vendor)
                                <option value="{{ $vendor }}" {{ request('vendor') == $vendor ? 'selected' : '' }}>{{ $vendor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis</label>
                        <select name="jenis" id="jenis" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                            <option value="">Semua Jenis</option>
                            <option value="Pengeluaran" {{ request('jenis') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            <option value="Tagihan" {{ request('jenis') == 'Tagihan' ? 'selected' : '' }}>Tagihan (Jatuh Tempo <= 5 Hari)</option>
                        </select>
                    </div>
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <div class="flex items-center space-x-2 mt-1">
                            <input type="text" name="periode_mulai" value="{{ request('periode_mulai') }}" class="date-picker w-full p-2 border border-gray-300 rounded-lg" placeholder="Tgl Mulai">
                            <span class="text-gray-500">-</span>
                            <input type="text" name="periode_selesai" value="{{ request('periode_selesai') }}" class="date-picker w-full p-2 border border-gray-300 rounded-lg" placeholder="Tgl Selesai">
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <label for="status_bayar" class="block text-sm font-medium text-gray-700">Status Bayar</label>
                        <select name="status_bayar" id="status_bayar" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                            <option value="">Semua Status</option>
                            <option value="Sudah Bayar" {{ request('status_bayar') == 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                            <option value="Belum Bayar" {{ request('status_bayar') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end items-center mt-6 pt-6 border-t border-gray-200 space-x-4">
                    <a href="{{ route('pengeluaran.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Reset Filter</a>
                    <button type="submit" class="py-2 px-6 rounded-lg text-white bg-blue-600 hover:bg-blue-700">Filter</button>
                    <a href="{{ route('pengeluaran.exportPdf', request()->query()) }}" target="_blank" class="py-2 px-6 rounded-lg text-white bg-green-600 hover:bg-green-700">Export PDF</a>
                </div>
            </form>
        </div>

        {{-- [BARU] Panel Info Dana Proyek --}}
        @if(isset($selectedProyekData))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                    <h3 class="text-sm font-medium text-blue-800">Proyek Dipilih</h3>
                    <p class="text-lg font-bold text-blue-900 mt-1 truncate" title="{{ $selectedProyekData['nama_proyek'] }}">
                        {{ $selectedProyekData['nama_proyek'] }}
                    </p>
                </div>
                <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                    <h3 class="text-sm font-medium text-green-800">Total Dana Proyek (Kontrak)</h3>
                    <p class="text-lg font-bold text-green-900 mt-1">
                        Rp. {{ number_format($selectedProyekData['total_nilai_kontrak'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
                    <h3 class="text-sm font-medium text-orange-800">Sisa Dana Proyek</h3>
                    <p class="text-lg font-bold {{ $selectedProyekData['sisa_dana'] < 0 ? 'text-red-600' : 'text-orange-900' }} mt-1">
                        Rp. {{ number_format($selectedProyekData['sisa_dana'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        @endif
        {{-- [AKHIR BARU] --}}


        {{-- Tabel Data --}}
        <div class="bg-white p-6 rounded-lg shadow-lg">
             {{-- ... (kode tabel data tetap sama) ... --}}
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Proyek</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Toko / Vendor</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Tanggal Struk</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Nominal</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Jenis</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Status Bayar</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($items as $item)
                        @php
                            // Default values
                            $jenis = 'Pengeluaran';
                            $jenisClass = 'bg-blue-100 text-blue-800';
                            $statusText = $item->status_bayar;
                            $statusClass = strtolower($item->status_bayar) == 'sudah bayar' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

                            // Logic for unpaid items
                            if (strtolower($item->status_bayar) == 'belum bayar' && $item->tanggal_bayar) {
                                $dueDate = $item->tanggal_bayar->startOfDay();
                                $now = \Carbon\Carbon::now()->startOfDay();
                                $diff = $now->diffInDays($dueDate, false);

                                if ($diff < 0) {
                                    $jenis = 'Jatuh Tempo';
                                    $jenisClass = 'bg-red-200 text-red-900 font-bold';
                                    $statusText = 'Jatuh Tempo';
                                    $statusClass = 'bg-red-200 text-red-900 font-bold';
                                } elseif ($diff <= 5) {
                                    $jenis = 'Tagihan';
                                    $jenisClass = 'bg-orange-100 text-orange-800';
                                    $statusText = 'Segera Jatuh Tempo';
                                    $statusClass = 'bg-orange-100 text-orange-800';
                                }
                            }
                        @endphp
                        <tr>
                            <td class="py-3 px-4 font-medium">{{ $item->proyek->nama_proyek ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $item->toko }}</td>
                            <td class="py-3 px-4">{{ $item->tanggal_struk->format('d M Y') }}</td>
                            <td class="py-3 px-4 font-semibold">
                                <button @click="showModal = true; modalContent = `{{ nl2br(e($item->items)) }}`" class="text-blue-600 hover:underline cursor-pointer text-left">
                                    Rp. {{ number_format($item->total, 0, ',', '.') }}
                                </button>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $jenisClass }}">
                                    {{ $jenis }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="py-3 px-4 flex items-center space-x-2">
                                <a href="{{ route('pengeluaran.edit', $item->id_pengeluaran) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                                <form action="{{ route('pengeluaran.destroy', $item->id_pengeluaran) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">Tidak ada data pengeluaran yang cocok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div> {{-- Penutup untuk div.space-y-8 --}}

    {{-- Kode Modal SEKARANG DI LUAR KONTEN UTAMA, ini adalah perbaikannya --}}
    <div x-show="showModal" 
         {{-- ... (kode modal tetap sama) ... --}}
         @keydown.escape.window="showModal = false"
         @click.self="showModal = false" 
         class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-[9999]" 
         style="display: none;">
        
        {{-- Konten Modal --}}
        <div @click.stop class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 transform transition-all"
             x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">

            {{-- Header Modal --}}
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">Rincian Pengeluaran</h3>
                <button @click="showModal = false" class="text-gray-500 hover:text-gray-800 text-3xl font-light">&times;</button>
            </div>

            {{-- Body Modal --}}
            <div class="mt-4 prose max-w-none max-h-80 overflow-y-auto text-gray-700 pr-2">
                <p x-html="modalContent"></p>
            </div>
            
            {{-- Footer Modal --}}
            <div class="flex justify-end pt-4 mt-4 border-t border-gray-200">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div> {{-- Penutup untuk div x-data --}}
@endsection

@push('scripts')
    {{-- ... (kode scripts tetap sama) ... --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.date-picker', {
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
        });
    });
</script>
@endpush