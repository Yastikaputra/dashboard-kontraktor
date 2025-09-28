@extends('layouts.app')

@section('content')
{{-- Inisialisasi Alpine.js untuk mengelola state pop-up (modal) --}}
<div class="space-y-8" x-data="{ showModal: false, modalContent: '' }">
    {{-- Header Halaman dan Tombol Tambah --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan Proyek</h1>
            <p class="text-gray-500 mt-1">Lacak semua pengeluaran dan tagihan proyek Anda.</p>
        </div>
        <a href="{{ route('pengeluaran.create') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
            + Catat Pengeluaran
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Panel Filter --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <form id="filter-form" action="{{ route('pengeluaran.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                
                {{-- Filter Proyek --}}
                <div>
                    <label for="proyek_id" class="block text-sm font-medium text-gray-700">Filter Proyek</label>
                    <select name="proyek_id" id="proyek_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Proyek</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id_proyek }}" {{ request('proyek_id') == $proyek->id_proyek ? 'selected' : '' }}>
                                {{ $proyek->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Periode --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Filter Periode</label>
                    <div class="flex items-center space-x-2 mt-1">
                        <input type="date" name="periode_mulai" value="{{ request('periode_mulai') }}" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Mulai">
                        <span class="text-gray-500">-</span>
                        <input type="date" name="periode_selesai" value="{{ request('periode_selesai') }}" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Selesai">
                    </div>
                </div>

                {{-- Filter Status Bayar --}}
                <div>
                    <label for="status_bayar" class="block text-sm font-medium text-gray-700">Filter Status Bayar</label>
                    <select name="status_bayar" id="status_bayar" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Sudah Bayar" {{ request('status_bayar') == 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar / Lunas</option>
                        <option value="Belum Bayar" {{ request('status_bayar') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>

                {{-- Filter Supplier / Toko --}}
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700">Filter Supplier/Toko</label>
                    <select name="supplier" id="supplier" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Supplier/Toko</option>
                        @foreach($listSupplier as $supplier)
                            <option value="{{ $supplier }}" {{ request('supplier') == $supplier ? 'selected' : '' }}>{{ $supplier }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            {{-- Tombol Aksi Filter --}}
            <div class="flex justify-end items-center mt-6 border-t border-gray-200 pt-6 space-x-4">
                <a href="{{ route('pengeluaran.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Reset Filter</a>
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('pengeluaran.exportPdf', request()->query()) }}" target="_blank" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">
                    Export PDF
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Proyek</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Sumber</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Nominal</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Jenis</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Status Bayar</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $item)
                    <tr>
                        <td class="py-3 px-4 font-medium">{{ $item->proyek->nama_proyek ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $item->sumber }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        
                        {{-- [DIUBAH] Nominal sekarang bisa diklik untuk memunculkan pop-up --}}
                        <td class="py-3 px-4 font-semibold">
                            <button 
                                @click="showModal = true; modalContent = `{{ nl2br(e($item->detail_items)) }}`"
                                class="text-blue-600 hover:underline cursor-pointer text-left">
                                Rp. {{ number_format($item->nominal, 0, ',', '.') }}
                            </button>
                        </td>

                        <td class="py-3 px-4">
                             <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->jenis == 'Pengeluaran' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $item->jenis }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                             <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_pembayaran == 'Lunas' || $item->status_pembayaran == 'Sudah Bayar' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->status_pembayaran }}
                            </span>
                        </td>
                        <td class="py-3 px-4 flex items-center space-x-2">
                            @if($item->jenis == 'Pengeluaran')
                                <a href="{{ route('pengeluaran.edit', $item->id_pengeluaran) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                                <form action="{{ route('pengeluaran.destroy', $item->id_pengeluaran) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">Tidak ada data yang cocok dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- [TAMBAHAN] Struktur HTML untuk Pop-up/Modal --}}
    <div x-show="showModal" @keydown.escape.window="showModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" style="display: none;">
        <div @click.outside="showModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Detail Items</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
            </div>
            {{-- Konten detail akan ditampilkan di sini --}}
            <div class="prose max-w-none text-gray-700" x-html="modalContent"></div>
            <div class="flex justify-end mt-6 border-t pt-4">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg hover:bg-gray-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

