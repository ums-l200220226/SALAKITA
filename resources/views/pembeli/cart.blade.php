@extends('pembeli.layout')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#2d5016] mb-2">Keranjang Belanja</h1>
        <p class="text-gray-600 text-sm">Kelola produk yang ingin Anda beli</p>
    </div>
    <div class="mb-4">
        <button onclick="goBackSmart()" type="button" class="inline-flex items-center gap-2 text-[#4a7c2c] hover:text-[#2d5016] font-semibold transition bg-transparent border-0 cursor-pointer">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    @if($cartItems->isEmpty())
    <!-- Keranjang Kosong -->
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <i data-lucide="shopping-cart" class="w-24 h-24 text-gray-300 mx-auto mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Keranjang Anda Kosong</h3>
        <p class="text-gray-500 mb-6">Mulai belanja dan tambahkan produk ke keranjang Anda</p>
        <a href="{{ route('toko.index') }}" class="inline-flex items-center gap-2 bg-[#4a7c2c] hover:bg-[#2d5016] text-white px-6 py-3 rounded-lg font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Mulai Belanja
        </a>
    </div>
    @else
    <!-- Keranjang Ada Isi - Grouped by Toko -->
    <div class="space-y-6">
        @php
            // Group cart items by toko
            $groupedByToko = $cartItems->groupBy(function($item) {
                return $item->product->toko->id ?? $item->product->user_id;
            });
        @endphp

        @foreach($groupedByToko as $tokoId => $items)
        @php
            $firstItem = $items->first();
            $tokoName = $firstItem->product->toko->nama_toko ?? $firstItem->product->user->name;
            $itemCount = $items->count();
            $tokoTotal = $items->sum(function($item) {
                return $item->quantity * $item->product->harga;
            });
        @endphp

        @if($itemCount > 1)
        <!-- Multiple Products from Same Store - With Colored Background -->
        <div class="bg-gradient-to-br from-[#f5f1e8] to-[#e8f5e9] rounded-xl shadow-md border-2 border-[#7cb342] overflow-hidden">
            <!-- Header Toko -->
            <div class="bg-[#4a7c2c] px-4 sm:px-6 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="store" class="w-5 h-5 text-white"></i>
                        <h2 class="font-bold text-lg text-white">{{ $tokoName }}</h2>
                    </div>
                    <span class="bg-white text-[#4a7c2c] px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $itemCount }} Produk
                    </span>
                </div>
            </div>

            <!-- Produk-produk dari Toko ini -->
            <div class="p-4 space-y-4">
                @foreach($items as $item)
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <div class="flex gap-4">
                        <!-- Gambar Produk -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0">
                            <img src="{{ $item->product->gambar ? asset('storage/' . $item->product->gambar) : '/img/salak.jpg' }}"
                                 alt="{{ $item->product->nama_produk }}"
                                 class="w-full h-full object-cover rounded-lg">
                        </div>

                        <!-- Info Produk -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1 min-w-0 pr-2">
                                    <h3 class="font-semibold text-[#2d5016] text-base sm:text-lg mb-1">
                                        {{ $item->product->nama_produk }}
                                    </h3>
                                    @if($item->product->deskripsi)
                                    <p class="text-xs text-gray-500 line-clamp-1 mb-2">
                                        {{ $item->product->deskripsi }}
                                    </p>
                                    @endif
                                </div>
                                <form action="{{ route('pembeli.cartRemove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Harga -->
                            <div class="mb-3">
                                <p class="text-xl sm:text-2xl font-bold text-[#2d5016]">
                                    Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500">per {{ $item->product->satuan }}</p>
                            </div>

                            <!-- Quantity Control -->
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-sm text-gray-600">Jumlah:</span>
                                <form action="{{ route('pembeli.cartUpdate', $item->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button type="button" onclick="decreaseQty(this)"
                                                class="px-3 py-2 hover:bg-gray-100 transition">
                                            <i data-lucide="minus" class="w-4 h-4"></i>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                               max="{{ $item->product->stok }}"
                                               class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none"
                                               onchange="this.form.submit()">
                                        <button type="button" onclick="increaseQty(this)"
                                                class="px-3 py-2 hover:bg-gray-100 transition">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </form>
                                <span class="text-sm text-gray-600">{{ $item->product->satuan }}</span>
                            </div>

                            <!-- Stok Warning -->
                            @if($item->quantity > $item->product->stok)
                            <div class="mb-2 text-xs text-red-600 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                Stok tidak mencukupi! Tersedia: {{ $item->product->stok }} {{ $item->product->satuan }}
                            </div>
                            @endif

                            <!-- Subtotal -->
                            <div class="pt-3 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Subtotal:</span>
                                    <span class="font-bold text-lg text-[#2d5016]">
                                        Rp {{ number_format($item->quantity * $item->product->harga, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Footer: Total & Checkout Semua -->
            <div class="bg-white mx-4 mb-4 rounded-lg shadow-sm p-4 border-2 border-[#7cb342]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-600">Total Belanja dari {{ $tokoName }}</p>
                        <p class="font-bold text-2xl text-[#2d5016]">
                            Rp {{ number_format($tokoTotal, 0, ',', '.') }}
                        </p>
                    </div>
                    <a href="{{ route('pembeli.checkout', ['items' => $items->pluck('id')->implode(',')]) }}"
                       class="bg-[#4a7c2c] hover:bg-[#2d5016] text-white px-6 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2 shadow-md">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        Checkout Semua ({{ $itemCount }} Item)
                    </a>
                </div>
            </div>
        </div>

        @else
        <!-- Single Product - No Special Background -->
        @foreach($items as $item)
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
            <div class="flex gap-4">
                <!-- Gambar Produk -->
                <div class="w-24 h-24 sm:w-32 sm:h-32 flex-shrink-0">
                    <img src="{{ $item->product->gambar ? asset('storage/' . $item->product->gambar) : '/img/salak.jpg' }}"
                         alt="{{ $item->product->nama_produk }}"
                         class="w-full h-full object-cover rounded-lg">
                </div>

                <!-- Info Produk -->
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 min-w-0 pr-2">
                            <h3 class="font-semibold text-[#2d5016] text-base sm:text-lg mb-1">
                                {{ $item->product->nama_produk }}
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-600 flex items-center gap-1">
                                <i data-lucide="store" class="w-3 h-3"></i>
                                {{ $tokoName }}
                            </p>
                        </div>
                        <form action="{{ route('pembeli.cartRemove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Harga -->
                    <div class="mb-3">
                        <p class="text-xl sm:text-2xl font-bold text-[#2d5016]">
                            Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">per {{ $item->product->satuan }}</p>
                    </div>

                    <!-- Quantity Control -->
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-sm text-gray-600">Jumlah:</span>
                        <form action="{{ route('pembeli.cartUpdate', $item->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button type="button" onclick="decreaseQty(this)"
                                        class="px-3 py-2 hover:bg-gray-100 transition">
                                    <i data-lucide="minus" class="w-4 h-4"></i>
                                </button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                       max="{{ $item->product->stok }}"
                                       class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none"
                                       onchange="this.form.submit()">
                                <button type="button" onclick="increaseQty(this)"
                                        class="px-3 py-2 hover:bg-gray-100 transition">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </form>
                        <span class="text-sm text-gray-600">{{ $item->product->satuan }}</span>
                    </div>

                    <!-- Stok Warning -->
                    @if($item->quantity > $item->product->stok)
                    <div class="mb-3 text-xs text-red-600 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                        Stok tidak mencukupi! Tersedia: {{ $item->product->stok }} {{ $item->product->satuan }}
                    </div>
                    @endif

                    <!-- Subtotal & Checkout -->
                    <div class="pt-3 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm text-gray-600">Subtotal:</span>
                            <span class="font-bold text-lg text-[#2d5016]">
                                Rp {{ number_format($item->quantity * $item->product->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('pembeli.checkout', ['items' => $item->id]) }}"
                           class="w-full bg-[#4a7c2c] hover:bg-[#2d5016] text-white py-2.5 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                            Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @endforeach
    </div>
    @endif
</div>

<script>
function goBackSmart() {
    const referrer = document.referrer || '';

    // Kalau dari checkout, jangan balik ke sana! Langsung ke dashboard
    if (referrer.includes('/checkout')) {
        window.location.href = "{{ route('pembeli.dashboardPembeli') }}";
        return;
    }

    // Kalau bukan dari checkout dan ada history, baru boleh back
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback ke dashboard
        window.location.href = "{{ route('pembeli.dashboardPembeli') }}";
    }
}

function increaseQty(btn) {
    const input = btn.parentElement.querySelector('input[type="number"]');
    const max = parseInt(input.max);
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
        input.form.submit();
    }
}

function decreaseQty(btn) {
    const input = btn.parentElement.querySelector('input[type="number"]');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        input.form.submit();
    }
}

lucide.createIcons();
</script>
@endsection
