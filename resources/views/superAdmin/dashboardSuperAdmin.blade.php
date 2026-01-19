@extends('superAdmin.layout')

@section('content')

{{-- Welcome Section --}}
<div class="bg-white rounded-2xl shadow-md p-6 sm:p-8 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#2d5016] mb-2">
                Selamat Datang, {{ Auth::user()->name }}! 👋
            </h1>
            <p class="text-[#6d4c41] text-sm sm:text-base">
                Kelola platform agribisnis SalaKita dengan mudah
            </p>
        </div>
        <div class="hidden md:block">
            <div class="w-20 h-20 bg-[#f5f1e8] rounded-full flex items-center justify-center">
                <i data-lucide="users" class="w-10 h-10 text-[#4a7c2c]"></i>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

    {{-- Card 1: Total Transaksi --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-[#ff8f00] bg-opacity-10 rounded-lg flex items-center justify-center">
                <i data-lucide="shopping-cart" class="w-5 h-5 text-[#ff8f00]"></i>
            </div>
            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Selesai</span>
        </div>
        <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
        <h2 class="text-3xl font-bold text-[#2d5016]">{{ number_format($totalTransaksi) }}</h2>
        @if(isset($totalNilaiTransaksi))
        <p class="text-xs text-gray-500 mt-2">
            Nilai: Rp {{ number_format($totalNilaiTransaksi, 0, ',', '.') }}
        </p>
        @endif
    </div>

    {{-- Card 2: Petani Aktif --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-[#4a7c2c] bg-opacity-10 rounded-lg flex items-center justify-center">
                <i data-lucide="users" class="w-5 h-5 text-[#4a7c2c]"></i>
            </div>
            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Aktif</span>
        </div>
        <p class="text-sm text-gray-600 mb-1">Petani Aktif</p>
        <h2 class="text-3xl font-bold text-[#4a7c2c]">{{ $petaniAktif }}</h2>
    </div>

    {{-- Card 3: Pendapatan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-[#2d5016] bg-opacity-10 rounded-lg flex items-center justify-center">
                <i data-lucide="wallet" class="w-5 h-5 text-[#2d5016]"></i>
            </div>
            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Total</span>
        </div>
        <p class="text-sm text-gray-600 mb-1">Total Pendapatan</p>
        <h2 class="text-3xl font-bold text-[#2d5016]">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </h2>
    </div>
</div>

{{-- Chart Section --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Main Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-[#2d5016] mb-1">
                    Grafik Transaksi Petani
                </h2>
                <p class="text-sm text-[#6d4c41]">Performa transaksi 12 bulan terakhir</p>
            </div>
            {{--<button class="px-4 py-2 bg-[#f5f1e8] text-[#2d5016] rounded-lg text-sm font-medium hover:bg-[#4a7c2c] hover:text-white transition-all">
                <i data-lucide="download" class="w-4 h-4 inline mr-1"></i>
                Export
            </button>--}}
        </div>
        <canvas id="grafikTransaksi" class="w-full" style="max-height: 300px;"></canvas>
    </div>

    {{-- Quick Stats --}}
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h3 class="text-lg font-bold text-[#2d5016] mb-4">Statistik Cepat</h3>

        <div class="space-y-4">
            {{-- Stat Item 1 --}}
            <div class="flex items-center justify-between p-3 bg-[#f5f1e8] rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#ff8f00] bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i data-lucide="package" class="w-5 h-5 text-[#ff8f00]"></i>
                    </div>
                    <div>
                        <p class="text-xs text-[#6d4c41]">Total Produk Aktif</p>
                        <p class="text-lg font-bold text-[#2d5016]">{{ $totalProdukAktif }}</p>
                    </div>
                </div>
            </div>

            {{-- Stat Item 2 --}}
            <div class="flex items-center justify-between p-3 bg-[#f5f1e8] rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#4a7c2c] bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i data-lucide="clock" class="w-5 h-5 text-[#4a7c2c]"></i>
                    </div>
                    <div>
                        <p class="text-xs text-[#6d4c41]">Konfirmasi Pending</p>
                        <p class="text-lg font-bold text-[#2d5016]">{{ $petaniPending }}</p>
                    </div>
                </div>
            </div>

            {{-- Stat Item 4 --}}
            <div class="flex items-center justify-between p-3 bg-[#f5f1e8] rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-[#6d4c41]">Konfirmasi Ditolak</p>
                        <p class="text-lg font-bold text-[#2d5016]">{{ $petaniDitolak }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Activities --}}
<div class="bg-white rounded-2xl shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-[#2d5016]">Aktivitas Terbaru Petani</h2>
    </div>

    <div class="space-y-4">

        {{-- Petani baru mendaftar --}}
        @forelse($petaniBaru as $p)
        <div class="flex items-start gap-4 p-4 bg-[#f5f1e8] rounded-lg">

            <div class="w-10 h-10 bg-[#4a7c2c] rounded-full flex items-center justify-center">
                <i data-lucide="user-plus" class="w-5 h-5 text-white"></i>
            </div>

            <div class="flex-1">
                <p class="text-sm font-semibold text-[#2d5016]">
                    Petani baru mendaftar
                </p>

                <p class="text-xs mt-1 text-[#6d4c41]">
                    {{ $p->name }} mendaftar sebagai petani
                </p>

                <p class="text-xs mt-1 text-[#6d4c41]">
                    {{ $p->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        @empty

        {{-- Jika tidak ada petani baru --}}
        <div class="text-center py-6 text-sm text-[#6d4c41]">
            Tidak ada pendaftaran petani baru dalam 2×24 jam terakhir.
        </div>
        @endforelse
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('grafikTransaksi');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($bulan),
            datasets: [{
                label: 'Jumlah Transaksi',
                data: @json($jumlah),
                borderColor: '#4a7c2c',
                backgroundColor: 'rgba(74, 124, 44, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#4a7c2c',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#2d5016',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#2d5016',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    borderColor: '#4a7c2c',
                    borderWidth: 2
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(109, 76, 65, 0.1)'
                    },
                    ticks: {
                        color: '#6d4c41'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6d4c41'
                    }
                }
            }
        }
    });

    lucide.createIcons();
</script>

@endsection
