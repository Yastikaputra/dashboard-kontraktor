@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800">Edit Vendor: {{ $tagihan->nama_vendor }}</h1>

    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-400 text-red-700 p-4 rounded-lg" role="alert">
                <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tagihan.update', $tagihan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Column 1: Vendor Information --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Vendor</h3>
                    <div>
                        <label for="nama_vendor" class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor/Toko</label>
                        <input type="text" name="nama_vendor" id="nama_vendor" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('nama_vendor', $tagihan->nama_vendor) }}" required>
                    </div>
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3" class="w-full p-2 border border-gray-300 rounded-lg">{{ old('alamat', $tagihan->alamat) }}</textarea>
                    </div>
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('nomor_telepon', $tagihan->nomor_telepon) }}">
                    </div>
                    <div>
                        <label for="jenis_toko" class="block text-sm font-medium text-gray-700 mb-1">Jenis Toko</label>
                        <input type="text" id="jenis_toko" name="jenis_toko" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('jenis_toko', $tagihan->jenis_toko) }}">
                    </div>
                    <div>
                        <label for="daerah" class="block text-sm font-medium text-gray-700 mb-1">Daerah</label>
                        <input type="text" id="daerah" name="daerah" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('daerah', $tagihan->daerah) }}">
                    </div>
                </div>

                {{-- Column 2: Bank Information --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Bank</h3>
                    <div>
                        <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                        <input type="text" id="nama_bank" name="nama_bank" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('nama_bank', $tagihan->nama_bank) }}">
                    </div>
                    <div>
                        <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                        <input type="text" id="nomor_rekening" name="nomor_rekening" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('nomor_rekening', $tagihan->nomor_rekening) }}">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 mt-8 border-t">
                <a href="{{ route('tagihan.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg font-bold hover:bg-gray-300 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-colors">Perbarui Vendor</button>
            </div>
        </form>
    </div>
</div>
@endsection
