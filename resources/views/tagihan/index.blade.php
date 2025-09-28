@extends('layouts.app')

@section('content')
<div class="space-y-8">
    {{-- Header Halaman --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Tagihan & Vendor</h1>
            <p class="text-gray-500 mt-1">Kelola semua data tagihan dan vendor Anda.</p>
        </div>
        <a href="{{ route('tagihan.create') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
            + Tambah Data Baru
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Tabel Data --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
         <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">
                <thead class="bg-gray-100">
                    <tr class="text-gray-600 text-sm uppercase">
                        <th class="p-3 font-semibold">Nama Vendor</th>
                        <th class="p-3 font-semibold">Proyek</th>
                        <th class="p-3 font-semibold">Nilai Tagihan</th>
                        <th class="p-3 font-semibold">Jatuh Tempo</th>
                        <th class="p-3 font-semibold">Status</th>
                        <th class="p-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($tagihans as $tagihan)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-medium text-gray-800">
                            {{ $tagihan->nama_vendor }}
                            <span class="block text-xs text-gray-500">{{ $tagihan->jenis_toko }}</span>
                        </td>
                        <td class="p-3 text-gray-600">{{ $tagihan->proyek->nama_proyek ?? 'N/A' }}</td>
                        <td class="p-3 font-semibold text-gray-800">Rp. {{ number_format($tagihan->nilai_tagihan, 0, ',', '.') }}</td>
                        <td class="p-3 text-gray-600">{{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') : '-' }}</td>
                        <td class="p-3">
                            @if($tagihan->status_bayar == 'Lunas')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $tagihan->status_bayar }}</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $tagihan->status_bayar ?? 'Belum Dibayar' }}</span>
                            @endif
                        </td>
                        <td class="p-3 flex items-center justify-center space-x-2">
                            @if($tagihan->status_bayar !== 'Lunas')
                                {{-- PERBAIKAN: Menggunakan objek $tagihan --}}
                                <form action="{{ route('tagihan.lunas', $tagihan) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menandai tagihan ini lunas?');">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-xs">
                                        Lunas
                                    </button>
                                </form>
                            @endif
                            {{-- PERBAIKAN: Menggunakan objek $tagihan --}}
                            <a href="{{ route('tagihan.edit', $tagihan) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                            {{-- PERBAIKAN: Menggunakan objek $tagihan --}}
                            <form action="{{ route('tagihan.destroy', $tagihan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-500">Belum ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $tagihans->links() }}
        </div>
    </div>
</div>
@endsection
