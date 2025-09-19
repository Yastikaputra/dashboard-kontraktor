@extends('layouts.app')

@section('content')
<div class="px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Pengeluaran</h1>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Proyek</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Toko</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Total</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Items</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tanggal Struk</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Waktu Input</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Bukti Struk</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($pengeluarans as $item)
                    <tr class="border-b hover:bg-gray-50">
                        {{-- PERBAIKAN: Menggunakan nama kolom snake_case dari hasil debug --}}
                        <td class="py-3 px-4">{{ $item->id_pengeluaran }}</td>
                        <td class="py-3 px-4">
                            {{-- Sekarang relasi proyek akan berfungsi --}}
                            {{ $item->proyek->nama_proyek ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-4">{{ $item->toko }}</td>
                        <td class="py-3 px-4">Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">{{ $item->items }}</td>
                        <td class="py-3 px-4">
                            {{ $item->tanggal_struk ? \Carbon\Carbon::parse($item->tanggal_struk)->format('d M Y') : '-' }}
                        </td>
                        <td class="py-3 px-4">
                            {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="py-3 px-4">
                            @if($item->bukti_struk)
                                <a href="{{ $item->bukti_struk }}" target="_blank" class="text-blue-500 hover:underline">Lihat</a>
                            @else
                                Tidak Ada
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data pengeluaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Menampilkan link pagination --}}
        <div class="mt-6">
            {{ $pengeluarans->links() }}
        </div>
    </div>
</div>
@endsection

