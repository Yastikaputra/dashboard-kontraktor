@extends('layouts.app')

@section('content')
<div class="space-y-8">
    {{-- Header Halaman --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Proyek</h1>
            <p class="text-gray-500 mt-1">Kelola semua proyek yang sedang berjalan, ditunda, atau telah selesai.</p>
        </div>
        <a href="{{ route('proyek.create') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors duration-200">
            + Tambah Proyek Baru
        </a>
    </div>

    {{-- Notifikasi Sukses atau Error --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
            <p class="font-bold">Gagal!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Tabel Proyek --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Nama Proyek</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Anggaran</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Total Pengeluaran</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Sisa Waktu</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">PIC</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($proyeks as $proyek)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-800">{{ $proyek->nama_proyek }}</td>
                        <td class="py-3 px-4 text-gray-600">Rp. {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-red-600 font-semibold">Rp. {{ number_format($proyek->totalPengeluaran(), 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-gray-600 font-semibold">{{ $proyek->sisa_waktu }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $proyek->pic }} ({{ $proyek->no_pic }})</td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                @if($proyek->status == 'Selesai') bg-green-100 text-green-800
                                @elseif($proyek->status == 'Ditunda') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $proyek->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 flex items-center space-x-2">
                            @if($proyek->status !== 'Selesai')
                                <form action="{{ route('proyek.selesai', $proyek->id_proyek) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menandai proyek ini sebagai selesai?');">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-xs">
                                        Selesai
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('proyek.edit', $proyek->id_proyek) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                            
                            <form action="{{ route('proyek.destroy', $proyek->id_proyek) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">Tidak ada data proyek yang dapat ditampilkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Link Pagination --}}
        <div class="mt-6">
            {{ $proyeks->links() }}
        </div>
    </div>
</div>
@endsection