@extends('petani.layout')

@section('content')

<!-- Header -->
<div class="mb-3">
    <h1 class="text-[27px] font-bold text-[#2d5016] mb-2">Dashboard Penjualan</h1>
    <p class="text-gray-600">Pantau performa penjualan dan produk Anda</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1: Jumlah Penjualan -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#7cb342] bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                <i data-lucide="trending-up" class="w-5 h-5 text-[#7cb342]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-600 mb-0.5">Jumlah Penjualan</p>
                <h2 class="text-xl font-bold text-[#4a7c2c]">
                    {{ number_format($jumlahPenjualan ?? 0, 0, ',', '.') }}
                    <span class="text-sm text-gray-500">kg</span>
                </h2>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Pendapatan -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#ff8f00] bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                <i data-lucide="wallet" class="w-5 h-5 text-[#ff8f00]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-600 mb-0.5">Total Pendapatan</p>
                <h2 class="text-xl font-bold text-[#4a7c2c]">
                    Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </h2>
            </div>
        </div>
    </div>

    <!-- Card 3: Produk Aktif -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#6d4c41] bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                <i data-lucide="package" class="w-5 h-5 text-[#6d4c41]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-600 mb-0.5">Produk Aktif</p>
                <h2 class="text-xl font-bold text-[#4a7c2c]">{{ $stats['aktif'] ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <!-- Card 4: Pesanan Selesai -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#4a7c2c] bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                <i data-lucide="check-circle" class="w-5 h-5 text-[#4a7c2c]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-600 mb-0.5">Pesanan Selesai</p>
                <h2 class="text-xl font-bold text-[#4a7c2c]">{{ $pesananSelesai ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Filter Produk -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <label class="text-sm font-semibold text-[#4a7c2c] whitespace-nowrap">Filter Grafik:</label>
        <select id="productFilter"
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
            <option value="">Semua Produk</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Grafik Penjualan -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-[#4a7c2c] mb-1">Penjualan Per Bulan</h3>
                <p class="text-sm text-gray-500">Tren penjualan dalam 12 bulan terakhir</p>
            </div>
            <div class="w-10 h-10 bg-[#7cb342] bg-opacity-10 rounded-lg flex items-center justify-center">
                <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#7cb342]"></i>
            </div>
        </div>
        {{-- Tambahkan container dengan height fixed --}}
        <div style="height: 300px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Grafik Pendapatan -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-[#4a7c2c] mb-1">Pendapatan</h3>
                <p class="text-sm text-gray-500">Total pendapatan per bulan</p>
            </div>
            <div class="w-10 h-10 bg-[#ff8f00] bg-opacity-10 rounded-lg flex items-center justify-center">
                <i data-lucide="trending-up" class="w-5 h-5 text-[#ff8f00]"></i>
            </div>
        </div>
        {{-- Tambahkan container dengan height fixed --}}
        <div style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

{{-- Script langsung di sini, bukan @push --}}
<script>
(function() {
    console.log('Script started');

    let salesChart = null;
    let revenueChart = null;

    // Data dari backend
    const initialChartData = @json($chartData);
    console.log('Chart Data:', initialChartData);

    // Inisialisasi chart
    function initCharts(data) {
        console.log('initCharts called', data);

        if (!data) {
            console.error('No data');
            return;
        }

        const ctx1 = document.getElementById('salesChart');
        const ctx2 = document.getElementById('revenueChart');

        console.log('Canvas elements:', ctx1, ctx2);

        if (!ctx1 || !ctx2) {
            console.error('Canvas not found');
            return;
        }

        // Check if Chart is defined
        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded!');
            return;
        }

        // Destroy existing charts
        if (salesChart) salesChart.destroy();
        if (revenueChart) revenueChart.destroy();

        // Chart Penjualan (Bar Chart)
        salesChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: data.months,
                datasets: [{
                    label: 'Penjualan (kg)',
                    data: data.penjualan,
                    backgroundColor: 'rgba(124, 179, 66, 0.8)',
                    borderColor: 'rgba(124, 179, 66, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' kg';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Math.floor(value) + ' kg';
                            }
                        }
                    }
                }
            }
        });

        // Chart Pendapatan (Line Chart)
        revenueChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: data.months,
                datasets: [{
                    label: 'Pendapatan',
                    data: data.pendapatan,
                    borderColor: 'rgba(255, 143, 0, 1)',
                    backgroundColor: 'rgba(255, 143, 0, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(255, 143, 0, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000) + 'k';
                            }
                        }
                    }
                }
            }
        });

        console.log('Charts created successfully');
    }

    // Wait for DOM and Chart.js
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            setTimeout(() => initCharts(initialChartData), 100);
        });
    } else {
        console.log('DOM already loaded');
        setTimeout(() => initCharts(initialChartData), 100);
    }

    // Filter handler
    setTimeout(function() {
        const filterEl = document.getElementById('productFilter');
        if (filterEl) {
            filterEl.addEventListener('change', function() {
                const productId = this.value;
                fetch(`/petani/chart-data?product_id=${productId}`)
                    .then(response => response.json())
                    .then(data => initCharts(data))
                    .catch(error => console.error('Error:', error));
            });
        }
    }, 500);
})();

// Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>

@endsection
