@extends('layouts.app')

@section('content')
<div class="space-y-8">
    {{-- Header Halaman dengan Tombol Kembali --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Proyek: {{ $proyek->nama_proyek }}</h1>
            <p class="text-gray-500 mt-1">Klien: {{ $proyek->klien }}</p>
        </div>
        <a href="{{ route('proyek.index') }}" class="px-4 py-2 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-700">
            &larr; Kembali ke Daftar
        </a>
    </div>

    {{-- Detail Utama Proyek --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Kolom Info Keuangan --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-lg border-b pb-2">Informasi Keuangan</h3>
                <div>
                    <p class="text-sm text-gray-500">Anggaran</p>
                    <p class="font-bold text-xl">Rp. {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Pengeluaran</p>
                    <p class="font-bold text-xl text-red-600">Rp. {{ number_format($proyek->pengeluarans->sum('total'), 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sisa Anggaran</p>
                    <p class="font-bold text-xl text-green-600">Rp. {{ number_format($proyek->nilai_kontrak - $proyek->pengeluarans->sum('total'), 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Kolom Info Waktu & Status --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-lg border-b pb-2">Jadwal & Status</h3>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Mulai</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Target Selesai</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($proyek->target_selesai)->translatedFormat('d F Y') }}</p>
                </div>
                 <div>
                    <p class="text-sm text-gray-500">Status Proyek</p>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                        @if($proyek->status == 'Selesai') bg-green-100 text-green-800
                        @elseif($proyek->status == 'Ditunda') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ $proyek->status }}
                    </span>
                </div>
            </div>

            {{-- Kolom Info PIC --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-lg border-b pb-2">Penanggung Jawab (PIC)</h3>
                <div>
                    <p class="text-sm text-gray-500">Nama PIC</p>
                    <p class="font-semibold">{{ $proyek->pic }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nomor Telepon</p>
                    <p class="font-semibold">{{ $proyek->no_pic }}</p>
                </div>
            </div>
        </div>

        {{-- Deskripsi Proyek --}}
        <div class="mt-6 border-t pt-6">
            <h3 class="font-semibold text-lg mb-2">Deskripsi Proyek</h3>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($proyek->deskripsi)) ?: '<p class="text-gray-500">Tidak ada deskripsi untuk proyek ini.</p>' !!}
            </div>
        </div>
    </div>
</div>
@endsection

