@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Proyek</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left">Nama Proyek</th>
                        <th class="py-3 px-4 text-left">Klien</th>
                        <th class="py-3 px-4 text-left">Nilai Kontrak</th>
                        <th class="py-3 px-4 text-left">Tanggal Mulai</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proyeks as $proyek)
                    <tr class="border-b">
                        <td class="py-3 px-4">{{ $proyek->nama_proyek }}</td>
                        <td class="py-3 px-4">{{ $proyek->klien }}</td>
                        <td class="py-3 px-4">Rp. {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d M Y') }}</td>
                        <td class="py-3 px-4">
                             <span class="{{ $proyek->status === 'Selesai' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }} text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $proyek->status }}</span>
                        </td>
                        <td class="py-3 px-4">
                            {{-- **FITUR BARU:** Tombol untuk update status proyek --}}
                            @if($proyek->status !== 'Selesai')
                                <form action="{{ route('proyek.selesai', $proyek->id_proyek) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menandai proyek ini sebagai selesai?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                        Tandai Selesai
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada data proyek.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $proyeks->links() }}
        </div>
    </div>
</div>
@endsection

