@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Kontraktor</h1>
        <p class="text-gray-500 mt-1">Ringkasan aktivitas proyek Anda.</p>
    </div>

    {{-- Bagian Statistik Utama (Tidak ada perubahan) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center space-x-4 transition-transform transform hover:scale-105">
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h2 class="text-sm font-medium text-gray-500">Proyek Berjalan</h2>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $proyekBerjalan }}</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center space-x-4 transition-transform transform hover:scale-105">
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-sm font-medium text-gray-500">Proyek Selesai</h2>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $proyekSelesai }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center space-x-4 transition-transform transform hover:scale-105">
             <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0 1H9m3 0h3m-3 1h.01M12 18h.01M12 18v-1m0 1H9m3 0h3m-3-1h.01M12 16v-1m0 1H9m3 0h3m-6 5H9m3 0h3m-3 0v-1m0 1v-1m0 1H6m6 0h6"></path></svg>
            </div>
            <div>
                <h2 class="text-sm font-medium text-gray-500">Pengeluaran Bulan Ini</h2>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp. {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 flex items-center space-x-4 transition-transform transform hover:scale-105">
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-sm font-medium text-gray-500">Jumlah Vendor</h2>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalVendors }}</p>
            </div>
        </div>
    </div>
    
    {{-- Bagian Laporan Keuangan & Grafik (Tidak ada perubahan) --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Laporan Keuangan</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-green-50 p-4 rounded-lg">
                    <span class="font-medium text-green-800">Total Pemasukan</span>
                    <span class="font-bold text-green-800">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center bg-red-50 p-4 rounded-lg">
                    <span class="font-medium text-red-800">Total Pengeluaran</span>
                    <span class="font-bold text-red-800">Rp. {{ number_format($totalPengeluaran + $totalUpahTukang, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center bg-blue-50 p-4 rounded-lg">
                    <span class="font-medium text-blue-800">Profit/Loss</span>
                    <span class="font-bold text-blue-800">Rp. {{ number_format($profitLoss, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-3 bg-white p-6 rounded-xl shadow-lg border border-gray-200">
             <h3 class="font-bold text-lg mb-4 text-gray-700">Grafik Pengeluaran per Proyek</h3>
             @if($chartLabels->isNotEmpty())
                 <canvas id="projectExpenseChart"></canvas>
             @else
                 <div class="flex items-center justify-center h-full text-center">
                     <p class="text-gray-500">Belum ada data pengeluaran yang terhubung ke proyek.</p>
                 </div>
             @endif
        </div>
    </div>

    {{-- Bagian Vendor Baru (Tidak ada perubahan) --}}
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Vendor Baru Ditambahkan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Nama Vendor</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Telepon</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Daerah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentVendors as $vendor)
                            <tr>
                                <td class="py-3 px-4">{{ $vendor->nama_vendor }}</td>
                                <td class="py-3 px-4">{{ $vendor->nomor_telepon ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $vendor->daerah ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada data vendor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- [DIUBAH] Baris Baru untuk Tagihan Jatuh Tempo --}}
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Tagihan Jatuh Tempo</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Keterangan / Toko</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Proyek</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Total</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tagihanJatuhTempo as $tagihan)
                            @php
                                $dueDate = \Carbon\Carbon::parse($tagihan->tanggal_bayar);
                                $diff = now()->startOfDay()->diffInDays($dueDate, false);
                            @endphp
                            <tr>
                                <td class="py-3 px-4">{{ $tagihan->toko }}</td>
                                <td class="py-3 px-4">{{ $tagihan->proyek->nama_proyek ?? 'N/A' }}</td>
                                <td class="py-3 px-4">Rp. {{ number_format($tagihan->total, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    @if ($diff < 0)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Terlambat {{ abs($diff) }} hari
                                        </span>
                                    @elseif ($diff == 0)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Hari Ini
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $diff }} hari lagi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada tagihan yang akan jatuh tempo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- [DIUBAH] Baris Baru untuk Upah Tukang Belum Lunas --}}
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Upah Tukang Belum Lunas</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Nama Tukang</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Proyek</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Jumlah</th>
                            <th class="text-left py-2 px-4 text-sm font-semibold text-gray-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tukangBelumLunas as $tukang)
                            <tr>
                                <td class="py-3 px-4">{{ $tukang->nama_tukang }}</td>
                                <td class="py-3 px-4">{{ $tukang->proyek->nama_proyek ?? 'N/A' }}</td>
                                <td class="py-3 px-4">Rp. {{ number_format($tukang->jumlah, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $tukang->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Semua upah tukang sudah lunas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Bagian script Chart.js (Tidak ada perubahan) --}}
<script>
    const ctx = document.getElementById('projectExpenseChart');
    if (ctx) {
        const chartData = {!! json_encode($chartValues) !!};
        if (chartData.length > 0) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Pengeluaran',
                        data: chartData,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp. ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }
</script>
@endpush
