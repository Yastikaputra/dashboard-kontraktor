@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Catat Pengeluaran Baru</h1>
        <p class="text-gray-500 mt-1">Isi detail pengeluaran untuk mencatat transaksi baru.</p>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-200">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-lg" role="alert">
                <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ status: '{{ old('status_bayar', 'Sudah Bayar') }}' }">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Proyek Terkait --}}
                <div>
                    <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-1">Proyek Terkait</label>
                    <select name="id_proyek" id="id_proyek" class="w-full p-2 border border-gray-300 rounded-lg" required>
                        <option value="">Pilih Proyek</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id_proyek }}" {{ old('id_proyek') == $proyek->id_proyek ? 'selected' : '' }}>
                                {{ $proyek->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Toko / Vendor --}}
                <div>
                    <label for="toko" class="block text-sm font-medium text-gray-700 mb-1">Toko / Vendor</label>
                    <select name="toko" id="toko" class="w-full p-2 border border-gray-300 rounded-lg" required>
                        <option value="">Pilih Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor }}" {{ old('toko') == $vendor ? 'selected' : '' }}>{{ $vendor }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Total --}}
                <div>
                    <label for="total" class="block text-sm font-medium text-gray-700 mb-1">Total (Rp)</label>
                    <input type="number" name="total" id="total" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('total') }}" placeholder="Contoh: 500000" required>
                </div>

                {{-- Status Bayar --}}
                <div>
                    <label for="status_bayar" class="block text-sm font-medium text-gray-700 mb-1">Status Bayar</label>
                    <select name="status_bayar" id="status_bayar" class="w-full p-2 border border-gray-300 rounded-lg" required x-model="status">
                        <option value="Sudah Bayar">Sudah Bayar / Lunas</option>
                        <option value="Belum Bayar">Belum Bayar</option>
                    </select>
                </div>

                {{-- Tanggal Struk --}}
                <div>
                    <label for="tanggal_struk" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Struk</label>
                    <input type="date" name="tanggal_struk" id="tanggal_struk" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('tanggal_struk', date('Y-m-d')) }}" required>
                </div>
                
                {{-- Input Tanggal Bayar (Kondisional) --}}
                <div x-show="status === 'Belum Bayar'">
                    <label for="tanggal_bayar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar (Jatuh Tempo)</label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('tanggal_bayar') }}">
                </div>

                {{-- Rincian Item --}}
                <div class="md:col-span-2">
                    <label for="items" class="block text-sm font-medium text-gray-700 mb-1">Rincian Item</label>
                    <textarea name="items" id="items" rows="4" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Produk | Satuan | Harga&#10;Contoh: Besi | 2 kg | 500000" required>{{ old('items') }}</textarea>
                </div>

                {{-- Upload Bukti Struk --}}
                <div class="md:col-span-2">
                    <label for="bukti_struk" class="block text-sm font-medium text-gray-700 mb-1">Bukti Struk (Opsional)</label>
                    <input type="file" name="bukti_struk" id="bukti_struk" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200 mt-6">
                <a href="{{ route('pengeluaran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
@endsection