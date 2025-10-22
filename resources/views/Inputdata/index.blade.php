@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Input Data Awal</h1>
        <p class="text-gray-500 mt-1">Impor data pengeluaran dan upah tukang yang sudah berjalan untuk Proyek yang ada.</p>
    </div>

    {{-- Menampilkan Notifikasi Sukses atau Gagal --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
            <p class="font-bold">Sukses</p>
            <p>{!! nl2br(e(session('success'))) !!}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if(session('csv_errors'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-lg" role="alert">
            <p class="font-bold">Peringatan (Beberapa baris gagal diimpor):</p>
            <ul class="list-disc list-inside text-sm mt-2">
                @foreach(session('csv_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Langkah 1: Unduh Template --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Langkah 1: Unduh Template CSV</h2>
        <p class="text-gray-600 mb-4">
            Unduh template untuk memastikan format data Anda benar.
            <strong class="font-bold">Anda akan memilih Proyek Induk dan Vendor</strong> pada Langkah 2.
        </p>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('inputdata.downloadTemplate', ['template' => 'pengeluaran']) }}" 
               class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300">
               Unduh Template Pengeluaran
            </a>
            <a href="{{ route('inputdata.downloadTemplate', ['template' => 'tukang']) }}" 
               class="bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-yellow-700 transition duration-300">
               Unduh Template Tukang
            </a>
        </div>
    </div>

    {{-- Langkah 2: Unggah File CSV --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Langkah 2: Pilih Proyek dan Unggah File CSV</h2>
        <p class="text-gray-600 mb-6">
            Pilih Proyek Induk. Jika mengunggah pengeluaran, pilih juga Vendor-nya. Anda dapat mengunggah file pengeluaran, file tukang, atau keduanya sekaligus.
        </p>
        
        {{-- [DIUBAH] Form action menunjuk ke 'inputdata.parse' --}}
        <form action="{{ route('inputdata.parse') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Pilihan Proyek (Wajib) --}}
            <div>
                <label for="id_proyek" class="block text-sm font-medium text-gray-700 mb-1">
                    1. Pilih Proyek Induk <span class="text-red-600 font-bold">*Wajib</span>
                </label>
                <select name="id_proyek" id="id_proyek" required
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Pilih Proyek --</option>
                    @foreach($proyeks as $proyek)
                        <option value="{{ $proyek->id_proyek }}">
                            {{ $proyek->nama_proyek }} (Klien: {{ $proyek->klien }})
                        </option>
                    @endforeach
                </select>
                @error('id_proyek') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Pilihan Vendor (Baru) --}}
            <div>
                <label for="nama_vendor" class="block text-sm font-medium text-gray-700 mb-1">
                    2. Pilih Vendor/Toko <span class="text-gray-500">(Wajib jika upload pengeluaran)</span>
                </label>
                <select name="nama_vendor" id="nama_vendor"
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Pilih Vendor --</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->nama_vendor }}">
                            {{ $vendor->nama_vendor }}
                        </option>
                    @endforeach
                </select>
                @error('nama_vendor') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Upload Box Pengeluaran (Opsional) --}}
            <div>
                <label for="file_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                    3. File Pengeluaran Vendor (.csv) <span class="text-gray-500">(Opsional)</span>
                </label>
                <input type="file" name="file_pengeluaran" id="file_pengeluaran" 
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0 file:text-sm file:font-semibold
                              file:bg-green-50 file:text-green-700 hover:file:bg-green-100
                              border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                @error('file_pengeluaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Upload Box Tukang (Opsional) --}}
            <div>
                <label for="file_tukang" class="block text-sm font-medium text-gray-700 mb-1">
                    4. File Upah Tukang (.csv) <span class="text-gray-500">(Opsional)</span>
                </label>
                <input type="file" name="file_tukang" id="file_tukang" 
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0 file:text-sm file:font-semibold
                              file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100
                              border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                @error('file_tukang') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-4">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-300 text-base">
                    {{-- [DIUBAH] Teks tombol --}}
                    Lanjut ke Penyesuaian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
