@extends('layouts.app')

@section('content')
<div class="space-y-8">
    {{-- Page Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Vendor</h1>
            <p class="text-gray-500 mt-1">Kelola semua data vendor Anda.</p>
        </div>
        <a href="{{ route('tagihan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Vendor Baru
        </a>
    </div>

    {{-- Notification --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Data Table --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
         <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">
                <thead class="bg-gray-100">
                    <tr class="text-gray-600 text-sm uppercase">
                        <th class="p-4 font-semibold">Nama Vendor</th>
                        <th class="p-4 font-semibold">Alamat</th>
                        <th class="p-4 font-semibold">Nomor Telepon</th>
                        <th class="p-4 font-semibold">Jenis & Daerah</th>
                        <th class="p-4 font-semibold">Informasi Bank</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($tagihans as $vendor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 font-medium text-gray-800">
                            {{ $vendor->nama_vendor }}
                        </td>
                        <td class="p-4 text-gray-600">{{ $vendor->alamat ?? '-' }}</td>
                        <td class="p-4 text-gray-600">{{ $vendor->nomor_telepon ?? '-' }}</td>
                        <td class="p-4 text-gray-600">
                            <span class="block font-medium">{{ $vendor->jenis_toko ?? '-' }}</span>
                            <span class="text-xs text-gray-500">{{ $vendor->daerah ?? '-' }}</span>
                        </td>
                        <td class="p-4 text-gray-600">
                            <span class="block font-medium">{{ $vendor->nama_bank ?? '-' }}</span>
                            <span class="text-xs text-gray-500">{{ $vendor->nomor_rekening ?? '-' }}</span>
                        </td>
                        <td class="p-4 flex items-center justify-center space-x-2">
                            <a href="{{ route('tagihan.edit', $vendor) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors">Edit</a>
                            <form action="{{ route('tagihan.destroy', $vendor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data vendor ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-500">
                            <p class="font-bold">Belum ada data vendor.</p>
                            <p class="text-sm">Silakan tambahkan vendor baru.</p>
                        </td>
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
