@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Daftar Tukang & Upah</h1>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
         <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600 text-sm">
                        <th class="p-3 font-medium">ID</th>
                        <th class="p-3 font-medium">Nama Tukang</th>
                        <th class="p-3 font-medium">Mandor</th>
                        <th class="p-3 font-medium">Proyek</th>
                        <th class="p-3 font-medium">Jumlah Upah</th>
                        <th class="p-3 font-medium">Jatuh Tempo</th>
                        <th class="p-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tukangs as $tukang)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ $tukang->id }}</td>
                        <td class="p-3">{{ $tukang->nama_tukang }}</td>
                        <td class="p-3">{{ $tukang->nama_mandor ?? '-' }}</td>
                        <td class="p-3">{{ $tukang->proyek->nama_proyek ?? 'N/A' }}</td>
                        <td class="p-3">Rp. {{ number_format($tukang->jumlah, 0, ',', '.') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($tukang->jatuh_tempo)->format('d M Y') }}</td>
                        <td class="p-3">
                             @if($tukang->status == 'Sudah Dibayar')
                                <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $tukang->status }}</span>
                            @else
                                <span class="bg-red-200 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $tukang->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">Tidak ada data tukang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $tukangs->links() }}
        </div>
    </div>
@endsection

