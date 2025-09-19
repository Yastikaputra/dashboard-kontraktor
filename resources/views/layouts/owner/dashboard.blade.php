@extends('layouts.owner')

@section('content')
<div class="space-y-8">
    
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Keuangan</h1>
            <p class="text-gray-500 mt-1">Welcome, {{ Auth::user()->name }}. Here is your financial summary.</p>
        </div>
        <div class="text-sm text-gray-500 bg-white p-3 rounded-lg shadow-sm border">
            <span>{{ \Carbon\Carbon::now()->format('l, F j, Y | H:i:s') }}</span>
        </div>
    </div>

    <!-- Grid Utama untuk Laporan Keuangan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri (Kartu Statistik) -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Card Total Pemasukan -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex items-center space-x-4">
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path></svg>
                </div>
                <div>
                    <h2 class="text-md font-medium text-gray-500">Total Pemasukan</h2>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <!-- Card Total Pengeluaran -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 flex items-center space-x-4">
                <div class="bg-red-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5 6.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-md font-medium text-gray-500">Total Pengeluaran</h2>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <!-- Card Profit/Loss -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                 <h3 class="font-bold text-lg text-gray-700">Profit / Loss</h3>
                 <p class="text-4xl font-bold {{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                    Rp. {{ number_format($profitLoss, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Kolom Kanan (Grafik Pie) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-gray-200 flex flex-col justify-center">
            <h3 class="font-bold text-lg text-gray-700 mb-4 text-center">Komposisi Keuangan</h3>
            <div class="w-full max-w-md mx-auto">
                @if($pieChartData['values'][0] > 0 || $pieChartData['values'][1] > 0)
                    <canvas id="financialPieChart"></canvas>
                @else
                    <p class="text-center text-gray-500 py-20">Belum ada data keuangan untuk ditampilkan.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Data dari Controller
    const pieChartData = @json($pieChartData);

    // Inisialisasi Grafik Pie
    const financialPieChartCtx = document.getElementById('financialPieChart');
    if (financialPieChartCtx && (pieChartData.values[0] > 0 || pieChartData.values[1] > 0)) {
        new Chart(financialPieChartCtx, {
            type: 'doughnut',
            data: {
                labels: pieChartData.labels,
                datasets: [{
                    label: 'Ringkasan Keuangan',
                    data: pieChartData.values,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)', // Biru untuk Pemasukan
                        'rgba(239, 68, 68, 0.7)',  // Merah untuk Pengeluaran
                    ],
                    borderColor: [
                        '#FFFFFF',
                        '#FFFFFF',
                    ],
                    borderWidth: 4
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += 'Rp. ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush

