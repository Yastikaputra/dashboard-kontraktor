@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Pengaturan Umum</h1>
        <p class="text-gray-500 mt-1">Kelola informasi umum dan branding untuk perusahaan kontraktor Anda.</p>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-200">
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-lg" role="alert">
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

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

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Kolom Kiri: Input Form --}}
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kontraktor</label>
                        <input type="text" name="company_name" id="company_name" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('company_name', $settings['company_name'] ?? '') }}" placeholder="Contoh: CV. Jaya Konstruksi">
                    </div>
                    <div>
                        <label for="company_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Perusahaan</label>
                        <textarea name="company_address" id="company_address" rows="3" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Masukkan alamat lengkap">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="company_phone" id="company_phone" class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}" placeholder="Contoh: 081234567890">
                    </div>
                </div>

                {{-- Kolom Kanan: Logo Upload --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Logo Perusahaan</label>
                    <div class="mt-1 flex justify-center items-center p-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            @if(isset($settings['company_logo']))
                                {{-- [DIPERBAIKI] Menambahkan max-h-24 dan object-contain agar gambar tidak pecah --}}
                                <img src="{{ asset('storage/' . $settings['company_logo']) }}" alt="Logo saat ini" class="mx-auto max-h-24 w-auto object-contain mb-4">
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                            <label for="company_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>{{ isset($settings['company_logo']) ? 'Ganti Logo' : 'Unggah Logo' }}</span>
                                <input id="company_logo" name="company_logo" type="file" class="sr-only">
                            </label>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 1MB</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end pt-4 border-t border-gray-200 mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
@endsection

