@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Daftar Tagihan</h1>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
         <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600 text-sm">
                        <th class="p-3 font-medium">ID</th>
                        <th class="p-3 font-medium">Supplier</th>
                        <th class="p-3 font-medium">Proyek</th>
                        <th class="p-3 font-medium">No. Invoice</th>
                        <th class="p-3 font-medium">Nilai Tagihan</th>
                        <th class="p-3 font-medium">Jatuh Tempo</th>
                        <th class="p-3 font-medium">Status</th>
                        <th class="p-3 font-medium">Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tagihans as $tagihan)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ $tagihan->id }}</td>
                        <td class="p-3">{{ $tagihan->nama_supplier }}</td>
                        <td class="p-3">{{ $tagihan->proyek->nama_proyek ?? 'N/A' }}</td>
                        <td class="p-3">{{ $tagihan->no_invoice ?? '-' }}</td>
                        <td class="p-3">Rp. {{ number_format($tagihan->nilai_tagihan, 0, ',', '.') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}</td>
                        <td class="p-3">
                            @if($tagihan->status_bayar == 'Sudah Dibayar')
                                <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $tagihan->status_bayar }}</span>
                            @else
                                <span class="bg-red-200 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $tagihan->status_bayar }}</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $tagihan->tanggal_bayar ? \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-4 text-gray-500">Tidak ada data tagihan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $tagihans->links() }}
        </div>
    </div>
@endsection

