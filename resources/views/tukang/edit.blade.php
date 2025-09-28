@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Halaman --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Data Tukang</h1>
        <p class="text-gray-500 mt-1">Perbarui detail upah untuk <strong>{{ $tukang->nama_tukang }}</strong>.</p>
    </div>

    {{-- Form Edit --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <form action="{{ route('tukang.update', $tukang->id_tukang) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    <div>
                        <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-1">Pilih Proyek</label>
                        <select id="id_proyek" name="id_proyek" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            @foreach ($proyeks as $proyek)
                                <option value="{{ $proyek->id_proyek }}" {{ $tukang->id_proyek == $proyek->id_proyek ? 'selected' : '' }}>
                                    {{ $proyek->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nama_tukang" class="block text-sm font-medium text-gray-700 mb-1">Nama Tukang</label>
                        <input type="text" id="nama_tukang" name="nama_tukang" value="{{ $tukang->nama_tukang }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>

                     <div>
                        <label for="nama_mandor" class="block text-sm font-medium text-gray-700 mb-1">Nama Mandor</label>
                        <input type="text" id="nama_mandor" name="nama_mandor" value="{{ $tukang->nama_mandor }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-4">
                     <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Upah</label>
                        <input type="number" id="jumlah" name="jumlah" value="{{ $tukang->jumlah }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>

                    <div>
                        <label for="jatuh_tempo" class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo Pembayaran</label>
                        <input type="date" id="jatuh_tempo" name="jatuh_tempo" value="{{ $tukang->jatuh_tempo }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>

                     <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                        <select id="status" name="status" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="Belum Lunas" {{ $tukang->status == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="Lunas" {{ $tukang->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('tukang.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
