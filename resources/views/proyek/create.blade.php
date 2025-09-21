@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Tambah Proyek Baru</h1>
        <p class="text-gray-500 mt-1">Isi detail lengkap untuk proyek yang akan dibuat.</p>
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

        <form action="{{ route('proyek.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nama Proyek --}}
                <div>
                    <label for="nama_proyek" class="block text-sm font-medium text-gray-700 mb-1">Nama Proyek</label>
                    <input type="text" name="nama_proyek" id="nama_proyek" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('nama_proyek') }}" required>
                </div>

                {{-- Klien --}}
                <div>
                    <label for="klien" class="block text-sm font-medium text-gray-700 mb-1">Klien</label>
                    <input type="text" name="klien" id="klien" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('klien') }}" required>
                </div>

                {{-- Nilai Kontrak --}}
                <div>
                    <label for="nilai_kontrak" class="block text-sm font-medium text-gray-700 mb-1">Nilai Kontrak (Rp)</label>
                    <input type="number" name="nilai_kontrak" id="nilai_kontrak" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('nilai_kontrak') }}" required>
                </div>

                {{-- Tanggal Mulai --}}
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('tanggal_mulai') }}" required>
                </div>

                {{-- Target Selesai --}}
                <div>
                    <label for="target_selesai" class="block text-sm font-medium text-gray-700 mb-1">Target Selesai</label>
                    <input type="date" name="target_selesai" id="target_selesai" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('target_selesai') }}" required>
                </div>

                {{-- PIC --}}
                 <div>
                    <label for="pic" class="block text-sm font-medium text-gray-700 mb-1">PIC (Person in Charge)</label>
                    <input type="text" name="pic" id="pic" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('pic') }}" required>
                </div>
                
                {{-- Nomer PIC --}}
                <div>
                    <label for="no_pic" class="block text-sm font-medium text-gray-700 mb-1">Nomer PIC</label>
                     <input type="text" name="no_pic" id="no_pic" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('no_pic') }}" required>
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('proyek.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Proyek</button>
            </div>
        </form>
    </div>
</div>
@endsection