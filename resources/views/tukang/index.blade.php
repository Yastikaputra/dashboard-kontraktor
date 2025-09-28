@extends('layouts.app')

@section('content')
<div class="space-y-8">
    {{-- Header Halaman --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Tukang & Upah</h1>
            <p class="text-gray-500 mt-1">Kelola data upah untuk semua tukang di berbagai proyek.</p>
        </div>
        <a href="{{ route('tukang.create') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
            + Tambah Data Tukang
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Tabel Data Tukang --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
         <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">
                <thead class="bg-gray-100">
                    <tr class="text-gray-600 text-sm uppercase">
                        <th class="p-3 font-semibold">Nama Tukang</th>
                        <th class="p-3 font-semibold">Mandor</th>
                        <th class="p-3 font-semibold">Proyek</th>
                        <th class="p-3 font-semibold">Jumlah Upah</th>
                        <th class="p-3 font-semibold">Jatuh Tempo</th>
                        <th class="p-3 font-semibold">Status</th>
                        <th class="p-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($tukangs as $tukang)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-medium text-gray-800">{{ $tukang->nama_tukang }}</td>
                        <td class="p-3 text-gray-600">{{ $tukang->nama_mandor }}</td>
                        <td class="p-3 text-gray-600">{{ $tukang->proyek->nama_proyek ?? 'N/A' }}</td>
                        <td class="p-3 text-gray-800 font-semibold">Rp. {{ number_format($tukang->jumlah, 0, ',', '.') }}</td>
                        <td class="p-3 text-gray-600">{{ \Carbon\Carbon::parse($tukang->jatuh_tempo)->format('d M Y') }}</td>
                        <td class="p-3">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                @if($tukang->status == 'Lunas') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $tukang->status }}
                            </span>
                        </td>
                        <td class="p-3 flex items-center justify-center space-x-2">
                            @if($tukang->status !== 'Lunas')
                                <form action="{{ route('tukang.lunas', $tukang->id_tukang) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menandai upah ini lunas?');">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-xs">
                                        Lunas
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('tukang.edit', $tukang->id_tukang) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                            
                            <form action="{{ route('tukang.destroy', $tukang->id_tukang) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center p-6 text-gray-500">Tidak ada data tukang yang dapat ditampilkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $tukangs->links() }}
        </div>
    </div>
</div>
@endsection
