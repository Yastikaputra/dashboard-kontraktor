@extends('layouts.owner')

@section('content')
<div class="space-y-8">
    
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
        <p class="text-gray-500 mt-1">Ringkasan keuangan dari semua proyek.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-lg border">
            <h2 class="text-sm font-medium text-gray-500">Total Pemasukan</h2>
            <p class="text-3xl font-bold text-green-600 mt-2">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg border">
            <h2 class="text-sm font-medium text-gray-500">Total Pengeluaran</h2>
            <p class="text-3xl font-bold text-red-600 mt-2">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg border">
            <h2 class="text-sm font-medium text-gray-500">Profit / Loss Global</h2>
            <p class="text-3xl font-bold {{ $profitLoss >= 0 ? 'text-blue-600' : 'text-orange-500' }} mt-2">Rp. {{ number_format($profitLoss, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg border">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Visualisasi Keuangan</h3>
            <canvas id="financialPieChart"></canvas>
        </div>
        <div class="lg:col-span-3 bg-white p-6 rounded-xl shadow-lg border">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Rincian per Proyek</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-2 px-4 font-semibold text-gray-600 uppercase">Nama Proyek</th>
                            <th class="text-right py-2 px-4 font-semibold text-gray-600 uppercase">Pemasukan</th>
                            <th class="text-right py-2 px-4 font-semibold text-gray-600 uppercase">Pengeluaran</th>
                            <th class="text-right py-2 px-4 font-semibold text-gray-600 uppercase">Profit/Loss</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($laporanProyek as $proyek)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-800">{{ $proyek->nama_proyek }}</div>
                                    <div class="text-xs text-gray-500">{{ $proyek->status }}</div>
                                </td>
                                <td class="py-3 px-4 text-right text-green-600 font-medium">Rp. {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right text-red-600 font-medium">Rp. {{ number_format($proyek->total_pengeluaran, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right font-bold {{ $proyek->profit_loss >= 0 ? 'text-blue-600' : 'text-orange-500' }}">Rp. {{ number_format($proyek->profit_loss, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data proyek.</td>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('financialPieChart');
        if (ctx) {
            const pieData = @json($pieChartData);
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: pieData.labels,
                    datasets: [{
                        label: 'Jumlah',
                        data: pieData.values,
                        backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                        borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush