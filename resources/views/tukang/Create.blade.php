@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Halaman --}}
    <h1 class="text-3xl font-bold text-gray-800">Tambah Data Tukang</h1>
    <p class="text-gray-500 mt-1">Isi formulir di bawah untuk menambahkan data upah tukang baru.</p>

    {{-- Form Tambah Tukang --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        {{-- Menggunakan route 'tukang.store' untuk menyimpan data baru --}}
        <form action="{{ route('tukang.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    <div>
                        <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-1">Pilih Proyek</label>
                        <select id="id_proyek" name="id_proyek" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="">-- Pilih Proyek Terkait --</option>
                            @foreach ($proyeks as $proyek)
                                <option value="{{ $proyek->id_proyek }}">{{ $proyek->nama_proyek }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nama_tukang" class="block text-sm font-medium text-gray-700 mb-1">Nama Tukang</label>
                        <input type="text" id="nama_tukang" name="nama_tukang" class="w-full border-gray-300 rounded-lg shadow-sm" required placeholder="Masukkan nama lengkap tukang">
                    </div>

                     <div>
                        <label for="nama_mandor" class="block text-sm font-medium text-gray-700 mb-1">Nama Mandor</label>
                        <input type="text" id="nama_mandor" name="nama_mandor" class="w-full border-gray-300 rounded-lg shadow-sm" required placeholder="Masukkan nama mandor">
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-4">
                     <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Upah</label>
                        <input type="number" id="jumlah" name="jumlah" class="w-full border-gray-300 rounded-lg shadow-sm" required placeholder="Contoh: 150000">
                    </div>

                    <div>
                        <label for="jatuh_tempo" class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo Pembayaran</label>
                        <input type="date" id="jatuh_tempo" name="jatuh_tempo" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>

                     <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                        <select id="status" name="status" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="Belum Lunas" selected>Belum Lunas</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex justify-end space-x-4 border-t pt-6">
                {{-- Menggunakan route 'tukang.index' untuk tombol batal --}}
                <a href="{{ route('tukang.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection