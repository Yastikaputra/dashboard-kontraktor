@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Tagihan: {{ $tagihan->nama_vendor }}</h1>

    <div class="bg-white p-8 rounded-xl shadow-md">
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

        {{-- PERBAIKAN: Menggunakan variabel $tagihan dan primary key yang benar --}}
        <form action="{{ route('tagihan.update', ['tagihan' => $tagihan->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nama Vendor --}}
                <div>
                    <label for="nama_vendor" class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor</label>
                    <input type="text" name="nama_vendor" id="nama_vendor" class="w-full mt-1 p-2 border rounded-lg" value="{{ old('nama_vendor', $tagihan->nama_vendor) }}" required>
                </div>

                {{-- Proyek Terkait --}}
                <div>
                    <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-1">Proyek Terkait</label>
                    <select name="id_proyek" id="id_proyek" class="w-full mt-1 p-2 border rounded-lg" required>
                        <option value="">Pilih Proyek</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id_proyek }}" {{ old('id_proyek', $tagihan->id_proyek) == $proyek->id_proyek ? 'selected' : '' }}>
                                {{ $proyek->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nomor Invoice --}}
                 <div>
                    <label for="no_invoice" class="block text-sm font-medium text-gray-700 mb-1">Nomor Invoice</label>
                    <input type="text" name="no_invoice" id="no_invoice" class="w-full mt-1 p-2 border rounded-lg" value="{{ old('no_invoice', $tagihan->no_invoice) }}">
                </div>

                {{-- Nilai Tagihan --}}
                <div>
                    <label for="nilai_tagihan" class="block text-sm font-medium text-gray-700 mb-1">Nilai Tagihan (Rp)</label>
                    <input type="number" name="nilai_tagihan" id="nilai_tagihan" class="w-full mt-1 p-2 border rounded-lg" value="{{ old('nilai_tagihan', $tagihan->nilai_tagihan) }}" required>
                </div>

                {{-- Tanggal Tagihan --}}
                <div>
                    <label for="tanggal_tagihan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Tagihan</label>
                    <input type="date" name="tanggal_tagihan" id="tanggal_tagihan" class="w-full mt-1 p-2 border rounded-lg" value="{{ old('tanggal_tagihan', $tagihan->tanggal_tagihan) }}">
                </div>

                {{-- Jatuh Tempo --}}
                <div>
                    <label for="jatuh_tempo" class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="w-full mt-1 p-2 border rounded-lg" value="{{ old('jatuh_tempo', $tagihan->jatuh_tempo) }}">
                </div>

                {{-- Status Bayar --}}
                <div>
                    <label for="status_bayar" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                    <select name="status_bayar" id="status_bayar" class="w-full mt-1 p-2 border rounded-lg" required>
                        <option value="Belum Dibayar" {{ old('status_bayar', $tagihan->status_bayar) == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                        <option value="Lunas" {{ old('status_bayar', $tagihan->status_bayar) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full mt-1 p-2 border rounded-lg">{{ old('deskripsi', $tagihan->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 mt-6 border-t">
                <a href="{{ route('tagihan.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Perbarui Tagihan</button>
            </div>
        </form>
    </div>
</div>
@endsection

