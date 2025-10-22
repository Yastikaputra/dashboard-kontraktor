@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Vendor Baru</h1>

    <form action="{{ route('tagihan.store') }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- Column 1: Vendor Information --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Vendor</h3>
                <div>
                    <label for="nama_vendor" class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor/Toko</label>
                    <input type="text" id="nama_vendor" name="nama_vendor" class="w-full p-2 border border-gray-300 rounded-lg" required placeholder="Contoh: Toko Bangunan Jaya">
                </div>
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Jl. Raya Kuta No. 123"></textarea>
                </div>
                <div>
                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="081234567890">
                </div>
                <div>
                    <label for="jenis_toko" class="block text-sm font-medium text-gray-700 mb-1">Jenis Toko</label>
                    <input type="text" id="jenis_toko" name="jenis_toko" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Material, Jasa, dll.">
                </div>
                <div>
                    <label for="daerah" class="block text-sm font-medium text-gray-700 mb-1">Daerah</label>
                    <input type="text" id="daerah" name="daerah" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Denpasar, Badung, dll.">
                </div>
            </div>

            {{-- Column 2: Bank Information --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Bank</h3>
                <div>
                    <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                    <input type="text" id="nama_bank" name="nama_bank" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="BCA, Mandiri, dll.">
                </div>
                <div>
                    <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                    <input type="text" id="nomor_rekening" name="nomor_rekening" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="1234567890">
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4 border-t pt-6">
            <a href="{{ route('tagihan.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg hover:bg-gray-300 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">Simpan Vendor</button>
        </div>
    </form>
</div>
@endsection
