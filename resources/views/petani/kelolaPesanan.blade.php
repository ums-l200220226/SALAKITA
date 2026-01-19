@extends('petani.layout')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="container mx-auto px-4 py-1">
    <!-- Header -->
    <div class="mb-3">
        <h1 class="text-[27px] font-bold text-[#2d5016] mb-2">Kelola Pesanan</h1>
        <p class="text-gray-600">Kelola pesanan yang masuk ke toko Anda</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Filter Status - Mobile: Dropdown, Desktop: Pills -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <!-- Mobile Dropdown -->
    <div class="sm:hidden" x-data="{ open: false }" @click.away="open = false">
        <label class="block text-sm font-semibold text-[#5d4037] mb-2">Filter Status</label>

        <!-- Button Trigger -->
        <button @click="open = !open" type="button"
            class="w-full bg-white border border-gray-300 rounded-lg
                px-3 py-2 text-left text-sm font-medium text-gray-800
                shadow-md hover:shadow-lg hover:border-[#ff8f00]/30
                transition-all duration-200 flex items-center justify-between group">

            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#ff8f00]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span class="font-semibold">
                    {{ request('status') == 'pending' ? 'Menunggu Konfirmasi' :
                        (request('status') == 'dikonfirmasi' ? 'Dikonfirmasi' :
                        (request('status') == 'diproses' ? 'Sedang Diproses' :
                        (request('status') == 'dikirim' ? 'Dalam Pengiriman' :
                        (request('status') == 'selesai' ? 'Selesai' :
                        (request('status') == 'dibatalkan' ? 'Dibatalkan' : 'Semua Pesanan'))))) }}
                </span>
            </span>

            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 group-hover:text-[#ff8f00]"
                :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="absolute z-50 mt-1 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden"
            style="display: none; min-width: max-content;">

            <div class="py-1 max-h-80 overflow-y-auto">
                <!-- Semua Pesanan -->
                <a href="{{ route('petani.kelolaPesanan') }}"
                    class="flex items-center gap-3 px-2 py-2 hover:bg-gray-50
                        transition-all duration-150 {{ request('status') == '' ? 'bg-[#ff8f00]/5 border-l-2 border-[#ff8f00]' : '' }}">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-800">Semua Pesanan</span>
                    </div>
                    <span class="text-xs font-semibold text-[#ff8f00] bg-[#ff8f00]/10 px-2 py-0.5 rounded-full">
                        {{ $totalPesanan }}
                    </span>
                </a>

                <!-- Menunggu Konfirmasi -->
                <a href="{{ route('petani.kelolaPesanan', ['status' => 'pending']) }}"
                    class="flex items-center gap-3 px-2 py-2 hover:bg-gray-50
                        transition-all duration-150 {{ request('status') == 'pending' ? 'bg-yellow-50 border-l-2 border-yellow-500' : '' }}">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-800">Menunggu Konfirmasi</span>
                    </div>
                    <span class="text-xs font-semibold text-[#ff8f00] bg-[#ff8f00]/10 px-2 py-0.5 rounded-full">
                        {{ $statusCounts['pending'] ?? 0 }}
                    </span>
                </a>

                <!-- Dikonfirmasi -->
                <a href="{{ route('petani.kelolaPesanan', ['status' => 'dikonfirmasi']) }}"
                    class="flex items-center gap-3 px-2 py-2 hover:bg-gray-50
                        transition-all duration-150 {{ request('status') == 'dikonfirmasi' ? 'bg-blue-50 border-l-2 border-blue-500' : '' }}">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-800">Dikonfirmasi</span>
                    </div>
                    <span class="text-xs font-semibold text-[#ff8f00] bg-[#ff8f00]/10 px-2 py-0.5 rounded-full">
                        {{ $statusCounts['dikonfirmasi'] ?? 0 }}
                    </span>
                </a>

                <!-- Sedang Diproses -->
                <a href="{{ route('petani.kelolaPesanan', ['status' => 'diproses']) }}"
                    class="flex items-center gap-3 px-2 py-2 hover:bg-gray-50
                        transition-all duration-150 {{ request('status') == 'diproses' ? 'bg-purple-50 border-l-2 border-purple-500' : '' }}">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-800">Sedang Diproses</span>
                    </div>
                    <span class="text-xs font-semibold text-[#ff8f00] bg-[#ff8f00]/10 px-2 py-0.5 rounded-full">
                        {{ $statusCounts['diproses'] ?? 0 }}
                    </span>
                </a>

                <!-- Dalam Pengiriman -->
                <a href="{{ route('petani.kelolaPesanan', ['status' => 'dikirim']) }}"
                    class="flex items-center gap-3 px-2 py-2 hover:bg-gray-50
                        transition-all duration-150 {{ request('status') == 'dikirim' ? 'bg-indigo-50 border-l-2 border-indigo-500' : '' }}">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-800">Dalam Pengiriman</span>
                    </div>
                    <span class="text-xs font-semibold text-[#ff8f00] bg-[#ff8f00]/10 px-2 py-0.5 rounded-full">
                        {{ $statusCounts['dikirim'] ?? 0 }}
                    </span>
                </a>

                <!-- Selesai -->
                <a href="{{ route('petani.kelolaPesanan', ['status' => 'selesai']) }}"
                    class="flex items-center gap-3 px-2 py-2 hover:bg-gray-50
                        transition-all duration-150 {{ request('status') == 'selesai' ? 'bg-green-50 border-l-2 border-green-500' : '' }}">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-800">Selesai</span>
                    </div>
                    <span class="text-xs font-semibold text-[#ff8f00] bg-[#ff8f00]/10 px-2 py-0.5 rounded-full">
                        {{ $statusCounts['selesai'] ?? 0 }}
                    </span>
                </a>

                <!-- Dibatalkan -->
                <a href="{{ route('petani.kelolaPesanan', ['status' => 'dibatalkan']) }}"
                    class="flex items-center gap-3 px-2 py-2 hover:bg-gray-50
                        transition-all duration-150 {{ request('status') == 'dibatalkan' ? 'bg-red-50 border-l-2 border-red-500' : '' }}">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-800">Dibatalkan</span>
                    </div>
                    <span class="text-xs font-semibold text-[#ff8f00] bg-[#ff8f00]/10 px-2 py-0.5 rounded-full">
                        {{ $statusCounts['dibatalkan'] ?? 0 }}
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Desktop Pills -->
    <div class="hidden sm:block">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('petani.kelolaPesanan') }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('status') == '' ? 'bg-[#2d5016] text-white shadow-lg' : 'bg-[#f5f1e8] text-[#5d4037] hover:bg-[#8d6e63] hover:text-white' }}">
                <span class="inline-flex items-center gap-1">
                    Semua
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $totalPesanan }}</span>
                </span>
            </a>

            <a href="{{ route('petani.kelolaPesanan', ['status' => 'dikonfirmasi']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('status') == 'dikonfirmasi' ? 'bg-[#2d5016] text-white shadow-lg' : 'bg-[#f5f1e8] text-[#5d4037] hover:bg-[#8d6e63] hover:text-white' }}">
                <span class="inline-flex items-center gap-1">
                    Dikonfirmasi
                    @if(isset($statusCounts['dikonfirmasi']) && $statusCounts['dikonfirmasi'] > 0)
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $statusCounts['dikonfirmasi'] }}</span>
                    @endif
                </span>
            </a>

            <a href="{{ route('petani.kelolaPesanan', ['status' => 'diproses']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('status') == 'diproses' ? 'bg-[#2d5016] text-white shadow-lg' : 'bg-[#f5f1e8] text-[#5d4037] hover:bg-[#8d6e63] hover:text-white' }}">
                <span class="inline-flex items-center gap-1">
                    Diproses
                    @if(isset($statusCounts['diproses']) && $statusCounts['diproses'] > 0)
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $statusCounts['diproses'] }}</span>
                    @endif
                </span>
            </a>

            <a href="{{ route('petani.kelolaPesanan', ['status' => 'dikirim']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('status') == 'dikirim' ? 'bg-[#2d5016] text-white shadow-lg' : 'bg-[#f5f1e8] text-[#5d4037] hover:bg-[#8d6e63] hover:text-white' }}">
                <span class="inline-flex items-center gap-1">
                    Dikirim
                    @if(isset($statusCounts['dikirim']) && $statusCounts['dikirim'] > 0)
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $statusCounts['dikirim'] }}</span>
                    @endif
                </span>
            </a>

            <a href="{{ route('petani.kelolaPesanan', ['status' => 'selesai']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('status') == 'selesai' ? 'bg-[#2d5016] text-white shadow-lg' : 'bg-[#f5f1e8] text-[#5d4037] hover:bg-[#8d6e63] hover:text-white' }}">
                <span class="inline-flex items-center gap-1">
                    Selesai
                    @if(isset($statusCounts['selesai']) && $statusCounts['selesai'] > 0)
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $statusCounts['selesai'] }}</span>
                    @endif
                </span>
            </a>

            <a href="{{ route('petani.kelolaPesanan', ['status' => 'dibatalkan']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('status') == 'dibatalkan' ? 'bg-[#2d5016] text-white shadow-lg' : 'bg-[#f5f1e8] text-[#5d4037] hover:bg-[#8d6e63] hover:text-white' }}">
                <span class="inline-flex items-center gap-1">
                    Dibatalkan
                    @if(isset($statusCounts['dibatalkan']) && $statusCounts['dibatalkan'] > 0)
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $statusCounts['dibatalkan'] }}</span>
                    @endif
                </span>
            </a>
        </div>
    </div>
</div>

    <!-- Grid Pesanan -->
    @if($pesanan->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($pesanan as $order)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all overflow-hidden">
            <!-- Header Card with Gradient -->
            <div class="bg-gradient-to-br from-[#4a7c2c] via-[#7cb342] to-[#4a7c2c] p-4 text-white">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <p class="text-xs opacity-90 mb-1">Order #{{ $order->order_number }}</p>
                        <p class="font-bold text-lg">{{ $order->user->name }}</p>
                    </div>
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold shadow-md
                        @if($order->status == 'pending') bg-[#ff8f00]
                        @elseif($order->status == 'dikonfirmasi') bg-[#4a7c2c]
                        @elseif($order->status == 'diproses') bg-[#7cb342]
                        @elseif($order->status == 'dikirim') bg-[#6d4c41]
                        @elseif($order->status == 'selesai') bg-[#2d5016]
                        @else bg-[#8d6e63]
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <!-- Quick Info -->
                <div class="flex items-center justify-between text-xs opacity-90">
                    <span class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i data-lucide="credit-card" class="w-3 h-3"></i>
                        {{ strtoupper($order->metode_pembayaran) }}
                        @if($order->metode_pembayaran == 'qris' && $order->status_pembayaran == 'paid')
                        <span class="px-1.5 py-0.5 bg-green-500 rounded text-white ml-1">✓Lunas</span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Body Card - Product List -->
            <div class="p-4">
                <!-- Products -->
                <div class="space-y-2 mb-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-3 bg-[#f5f1e8] rounded-lg p-2">
                        <!-- Gambar Produk -->
                        @if($item->product && $item->product->gambar)
                        <img src="{{ asset('storage/' . $item->product->gambar) }}"
                             alt="{{ $item->product_name }}"
                             class="w-12 h-12 object-cover rounded">
                        @else
                        <div class="w-12 h-12 bg-[#8d6e63] rounded flex items-center justify-center">
                            <i data-lucide="package" class="w-6 h-6 text-white"></i>
                        </div>
                        @endif
                        <!-- Info Produk -->
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm text-[#2d5016] truncate">{{ $item->product->product_name }}</p>
                            <p class="text-xs text-[#5d4037]">{{ $item->quantity }} {{ $item->satuan }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <!-- Harga -->
                        <div class="text-right">
                            <p class="font-bold text-sm text-[#ff8f00]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Metode Penerimaan & Info pengiriman (Alamat kalau dikirim) (Tanggal dan jam kalau diambil) -->
                <div class="mb-3 p-3 bg-[#f5f1e8] rounded-lg space-y-2">
                    <!-- Header Metode -->
                    <div class="flex items-center gap-2 text-sm font-bold text-[#2d5016]">
                        <i data-lucide="truck" class="w-4 h-4 text-[#6d4c41]"></i>
                        <span>{{ ucfirst($order->metode_penerimaan) }}</span>
                    </div>

                    <!-- Info Dikirim -->
                    @if($order->metode_penerimaan == 'dikirim')
                        <div class="text-xs text-[#5d4037] space-y-1">
                            <p class="line-clamp-2">📍 {{ $order->alamat_lengkap }}</p>
                            <p>{{ $order->city?->name }}, {{ $order->province?->name }}</p>
                            @if($order->ongkir > 0)
                            <div class="flex items-center justify-between bg-white rounded px-2 py-1 mt-1">
                                <span class="font-medium">Ongkir:</span>
                                <span class="font-bold text-[#ff8f00]">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    @endif

                    <!-- Info Diambil -->
                    @if($order->metode_penerimaan == 'diambil' && $order->tanggal_pengambilan)
                        <div class="bg-white rounded px-2 py-2 text-xs text-[#5d4037]">
                            <div class="flex items-center gap-2 mb-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#6d4c41]"></i>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($order->tanggal_pengambilan)->format('d M Y') }}</span>
                            </div>
                            @if($order->jam_pengambilan)
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-[#6d4c41]"></i>
                                <span class="font-medium">{{ $order->jam_pengambilan }}</span>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Catatan Pembeli (Compact Version) -->
                @if($order->catatan)
                <div class="mb-3 p-2 bg-[#fffbf0] rounded-lg border-l-4 border-[#ff8f00]">
                    <p class="text-xs text-[#5d4037]">
                        <i data-lucide="message-square" class="w-3 h-3 inline mr-1 text-[#ff8f00]"></i>
                        <span class="font-medium">Catatan:</span> <span class="italic">"{{ $order->catatan }}"</span>
                    </p>
                </div>
                @endif

                <!-- Total -->
                <div class="border-t border-[#8d6e63]/20 pt-3 mb-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#5d4037] font-medium">Total Pesanan</span>
                        <span class="text-xl font-bold text-[#2d5016]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- ✅ RATING SECTION - TAMBAHKAN INI -->
                @if($order->status == 'selesai' && $order->rating)
                <div class="border-t border-[#8d6e63]/20 pt-3 mb-3">
                    <div class="bg-gradient-to-r from-[#fff3e0] to-[#ffe0b2] rounded-lg p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-[#5d4037]">Rating Pembeli</span>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $order->rating)
                                        <i data-lucide="star" class="w-4 h-4 fill-[#ff8f00] text-[#ff8f00]"></i>
                                    @else
                                        <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                                    @endif
                                @endfor
                                <span class="ml-1 text-sm font-bold text-[#ff8f00]">{{ $order->rating }}/5</span>
                            </div>
                        </div>
                        @if($order->review)
                        <p class="text-xs text-[#5d4037] italic line-clamp-2">"{{ $order->review }}"</p>
                        @endif
                        @if($order->reviewed_at)
                        <p class="text-xs text-[#8d6e63] mt-1">{{ $order->reviewed_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Button -->
                @if($order->status != 'selesai' && $order->status != 'dibatalkan')
                <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}')"
                        class="w-full bg-gradient-to-r from-[#ff8f00] to-[#ff6f00] hover:from-[#ff6f00] hover:to-[#ff8f00] text-white px-4 py-2.5 rounded-lg text-sm font-bold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Update Status
                </button>
                @else
                <div class="w-full bg-[#f5f1e8] text-[#5d4037] px-4 py-2.5 rounded-lg text-sm font-medium text-center">
                    <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i>
                    Pesanan {{ ucfirst($order->status) }}
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $pesanan->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-20 h-20 bg-[#f5f1e8] rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="inbox" class="w-10 h-10 text-[#8d6e63]"></i>
        </div>
        <h3 class="text-xl font-bold text-[#2d5016] mb-2">Belum Ada Pesanan</h3>
        <p class="text-[#5d4037]">Pesanan yang masuk akan muncul di sini</p>
    </div>
    @endif
</div>

<!-- Modal Update Status with Backdrop -->
<div id="statusModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl transform transition-all">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#ff8f00] to-[#ff6f00] rounded-full flex items-center justify-center">
                        <i data-lucide="refresh-cw" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#2d5016]">Update Status</h3>
                </div>
                <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="statusForm" method="POST" action="">
                @csrf
                @method('PATCH')

                <div class="mb-6">
                    <label class="block text-sm font-bold text-[#5d4037] mb-3">Pilih Status Baru</label>
                    <select name="status" id="statusSelect"
                            class="w-full border-2 border-[#8d6e63]/30 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#ff8f00] focus:border-[#ff8f00] transition-all text-[#2d5016] font-medium">
                        <option value="">-- Pilih Status --</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeStatusModal()"
                            class="flex-1 bg-[#f5f1e8] hover:bg-[#8d6e63] hover:text-white text-[#5d4037] px-4 py-3 rounded-xl font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-[#4a7c2c] to-[#2d5016] hover:from-[#2d5016] hover:to-[#4a7c2c] text-white px-4 py-3 rounded-xl font-bold transition-all shadow-lg hover:shadow-xl">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Status transitions mapping
const statusTransitions = {
    'pending': ['dikonfirmasi', 'dibatalkan'],
    'dikonfirmasi': ['diproses', 'dibatalkan'],
    'diproses': ['dikirim', 'selesai','dibatalkan'],
    'dikirim': ['selesai']
};

const statusLabels = {
    'dikonfirmasi': 'Dikonfirmasi',
    'diproses': 'Diproses',
    'dikirim': 'Dikirim',
    'selesai': 'Selesai',
    'dibatalkan': 'Dibatalkan'
};

function openStatusModal(orderId, currentStatus) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const select = document.getElementById('statusSelect');

    // Set form action
    form.action = `/pesanan-petani/${orderId}/update-status`;

    // Populate options based on current status
    select.innerHTML = '<option value="">-- Pilih Status --</option>';
    const allowedStatuses = statusTransitions[currentStatus] || [];

    allowedStatuses.forEach(status => {
        const option = document.createElement('option');
        option.value = status;
        option.textContent = statusLabels[status];
        select.appendChild(option);
    });

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // Re-enable scrolling
}

// Close modal when clicking backdrop
document.getElementById('statusModal')?.addEventListener('click', function(e) {
    if (e.target === this || e.target.classList.contains('backdrop-blur-sm')) {
        closeStatusModal();
    }
});

// Close with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeStatusModal();
    }
});

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endsection
