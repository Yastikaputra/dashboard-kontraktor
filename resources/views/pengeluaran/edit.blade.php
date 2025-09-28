@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Data Pengeluaran</h1>
        <p class="text-gray-500 mt-1">Perbarui detail pengeluaran untuk: <span class="font-semibold">{{ $pengeluaran->toko }}</span></p>
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

        <form action="{{ route('pengeluaran.update', $pengeluaran->id_pengeluaran) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Proyek Terkait --}}
                <div>
                    <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-1">Proyek Terkait</label>
                    <select name="id_proyek" id="id_proyek" class="w-full p-2 border border-gray-300 rounded-lg" required>
                        <option value="">Pilih Proyek</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id_proyek }}" {{ old('id_proyek', $pengeluaran->id_proyek) == $proyek->id_proyek ? 'selected' : '' }}>
                                {{ $proyek->nama_proyek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Toko / Supplier --}}
                <div>
                    <label for="toko" class="block text-sm font-medium text-gray-700 mb-1">Toko / Supplier</label>
                    <input type="text" name="toko" id="toko" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('toko', $pengeluaran->toko) }}" required>
                </div>

                {{-- Total --}}
                <div>
                    <label for="total" class="block text-sm font-medium text-gray-700 mb-1">Total (Rp)</label>
                    <input type="number" name="total" id="total" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('total', $pengeluaran->total) }}" required>
                </div>

                {{-- Tanggal Struk --}}
                <div>
                    <label for="tanggal_struk" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Struk</label>
                    <input type="date" name="tanggal_struk" id="tanggal_struk" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('tanggal_struk', $pengeluaran->tanggal_struk) }}" required>
                </div>

                {{-- Waktu Input --}}
                <div>
                    <label for="waktu_input" class="block text-sm font-medium text-gray-700 mb-1">Waktu Input</label>
                    <input type="time" name="waktu_input" id="waktu_input" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('waktu_input', $pengeluaran->waktu_input) }}" required>
                </div>

                {{-- Rincian Item --}}
                <div class="md:col-span-2">
                    <label for="items" class="block text-sm font-medium text-gray-700 mb-1">Rincian Item</label>
                    <textarea name="items" id="items" rows="3" class="w-full p-2 border border-gray-300 rounded-lg" required>{{ old('items', $pengeluaran->items) }}</textarea>
                </div>

                {{-- Upload Bukti Struk --}}
                <div class="md:col-span-2">
                    <label for="bukti_struk" class="block text-sm font-medium text-gray-700 mb-1">Ganti Bukti Struk (Opsional)</label>
                    <input type="file" name="bukti_struk" id="bukti_struk" class="w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
                    @if ($pengeluaran->bukti_struk)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $pengeluaran->bukti_struk) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat bukti struk saat ini</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200 mt-6">
                <a href="{{ route('pengeluaran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Perbarui Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
@endsection

