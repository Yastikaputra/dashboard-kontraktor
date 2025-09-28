@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Data Tagihan / Vendor</h1>

    {{-- [FIXED] Menggunakan route 'tagihan.store' --}}
    <form action="{{ route('tagihan.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Kolom 1: Info Vendor --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Vendor</h3>
                <div>
                    <label for="nama_vendor" class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor/Toko</label>
                    <input type="text" id="nama_vendor" name="nama_vendor" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="2" class="w-full border-gray-300 rounded-lg"></textarea>
                </div>
                <div>
                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" class="w-full border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="jenis_toko" class="block text-sm font-medium text-gray-700 mb-1">Jenis Toko</label>
                    <input type="text" id="jenis_toko" name="jenis_toko" class="w-full border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="daerah" class="block text-sm font-medium text-gray-700 mb-1">Daerah</label>
                    <input type="text" id="daerah" name="daerah" class="w-full border-gray-300 rounded-lg">
                </div>
            </div>

            {{-- Kolom 2: Info Bank --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Bank</h3>
                <div>
                    <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                    <input type="text" id="nama_bank" name="nama_bank" class="w-full border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                    <input type="text" id="nomor_rekening" name="nomor_rekening" class="w-full border-gray-300 rounded-lg">
                </div>
            </div>

            {{-- Kolom 3: Info Tagihan --}}
            <div class="space-y-4">
                 <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Tagihan</h3>
                 <div>
                    <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-1">Proyek Terkait</label>
                    <select id="id_proyek" name="id_proyek" class="w-full border-gray-300 rounded-lg" required>
                        <option value="">-- Pilih Proyek --</option>
                        @foreach ($proyeks as $proyek)
                            <option value="{{ $proyek->id_proyek }}">{{ $proyek->nama_proyek }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="nilai_tagihan" class="block text-sm font-medium text-gray-700 mb-1">Nilai Tagihan</label>
                    <input type="number" id="nilai_tagihan" name="nilai_tagihan" class="w-full border-gray-300 rounded-lg" required>
                </div>
                 <div>
                    <label for="jatuh_tempo" class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo</label>
                    <input type="date" id="jatuh_tempo" name="jatuh_tempo" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tagihan</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full border-gray-300 rounded-lg" required></textarea>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4 border-t pt-6">
            {{-- [FIXED] Menggunakan route 'tagihan.index' --}}
            <a href="{{ route('tagihan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection

