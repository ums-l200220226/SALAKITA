@extends('pembeli.layout')

@section('content')

<!-- Welcome Section -->
<div class="mb-8 px-4">
    <h1 class="text-2xl sm:text-3xl font-bold text-[#2d5016] mb-2">
        Selamat Datang, {{ Auth::user()->name }}! 👋
    </h1>
    <p class="text-sm sm:text-base text-gray-600">Temukan salak segar langsung dari petani lokal Desa Panca Tunggal</p>
</div>

<!-- Hero Banner -->
<div class="w-full h-48 sm:h-56 md:h-64 rounded-2xl overflow-hidden shadow-lg mb-10 relative group bg-gradient-to-br from-gray-900 via-gray-400 to-gray-300">

    <!-- Content -->
    <div class="absolute inset-0 flex items-center px-4 sm:px-8 md:px-12">
        <div class="text-white max-w-xl">
            <div class="inline-block bg-[#ff8f00] text-white px-3 py-1 rounded-full text-xs sm:text-sm font-semibold mb-2 sm:mb-3">
                Musim Panen {{ now()->year }}
            </div>

            <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold leading-tight mb-2 sm:mb-3 drop-shadow-lg">
                Dukung Petani Lokal,<br>
                Nikmati Segarnya Buah
            </h2>

            <p class="text-xs sm:text-sm md:text-base mb-3 sm:mb-4 text-gray-100">
                Langsung dari kebun. Kualitas terbaik, harga terjangkau.
            </p>

            <a href="{{ route('toko.index') }}" class="bg-[#4a7c2c] hover:bg-[#2d5016] px-4 sm:px-6 md:px-8 py-2 sm:py-3 rounded-lg text-xs sm:text-sm md:text-base font-semibold shadow-lg transition-all duration-300 hover:scale-105 flex inline-flex items-center gap-2">
                <i data-lucide="shopping-bag" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                Belanja Sekarang
            </a>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-[#4a7c2c]/10 flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6 text-[#4a7c2c]"></i>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Transaksi</p>
                <p class="text-2xl font-bold text-[#2d5016]">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-[#ff8f00]/10 flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6 text-[#ff8f00]"></i>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Produk Tersedia</p>
                <p class="text-2xl font-bold text-[#2d5016]">{{ $jumlahProduk }}+</p>
            </div>
        </div>
    </div>
</div>

<!-- Section: Produk Unggulan -->
<div class="mb-10 px-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-[#2d5016] flex items-center gap-2">
                <i data-lucide="star" class="w-5 h-5 sm:w-6 sm:h-6 text-[#ff8f00]"></i>
                Produk Unggulan
            </h2>
            <p class="text-gray-600 text-xs sm:text-sm mt-1">Pilihan terbaik dari petani lokal</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        @forelse ($featuredProducts as $product)
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 overflow-hidden group">
            <!-- Image Container -->
            <div class="relative overflow-hidden">
                <img src="{{ $product->gambar ? asset('storage/' . $product->gambar) : '/img/salak.jpg' }}"
                     class="h-32 sm:h-40 md:h-48 w-full object-cover transition-transform duration-500 group-hover:scale-110"
                     alt="{{ $product->nama_produk }}">

                <!-- Badge -->
                @if($product->total_terjual > 0)
                <div class="absolute top-2 left-2 bg-[#ff8f00] text-white px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold">
                    Terlaris
                </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-2.5 sm:p-4">
                <div class="flex items-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                    <i data-lucide="store" class="w-3 h-3 sm:w-4 sm:h-4 text-[#6d4c41]"></i>
                    <p class="text-gray-600 text-[10px] sm:text-sm truncate">
                        {{ $product->toko->nama_toko ?? $product->user->name }}
                    </p>
                </div>

                <h4 class="font-bold text-sm sm:text-base md:text-lg text-[#2d5016] mb-1 line-clamp-1">
                    {{ $product->nama_produk }}
                </h4>

                <div class="flex items-center gap-0.5 mb-2 sm:mb-3">
                    @php
                        $rating = $product->average_rating ?? 0;
                        $fullStars = floor($rating);
                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                    @endphp

                    @if($rating > 0)
                        {{-- Bintang penuh --}}
                        @for ($j = 0; $j < $fullStars; $j++)
                            <i data-lucide="star" class="w-3 h-3 sm:w-4 sm:h-4 fill-[#ff8f00] text-[#ff8f00]"></i>
                        @endfor

                        {{-- Bintang setengah --}}
                        @if($hasHalfStar)
                            <i data-lucide="star-half" class="w-3 h-3 sm:w-4 sm:h-4 fill-[#ff8f00] text-[#ff8f00]"></i>
                        @endif

                        {{-- Bintang kosong --}}
                        @for ($j = 0; $j < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $j++)
                            <i data-lucide="star" class="w-3 h-3 sm:w-4 sm:h-4 text-gray-300"></i>
                        @endfor

                        <span class="text-[10px] sm:text-sm text-gray-600 ml-1">
                            ({{ number_format($rating, 1) }})
                        </span>

                        {{-- BONUS: Tampilkan jumlah review (opsional) --}}
                        @if($product->total_reviews > 0)
                            <span class="text-[9px] sm:text-xs text-gray-400 ml-0.5">
                                • {{ $product->total_reviews }} ulasan
                            </span>
                        @endif
                    @else
                        <span class="text-[10px] sm:text-sm text-gray-400">
                            Belum ada rating
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <div>
                        <p class="text-base sm:text-xl md:text-2xl font-bold text-[#2d5016]">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                        <p class="text-[9px] sm:text-xs text-gray-500">per {{ $product->satuan }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] sm:text-sm text-gray-600">Stok:</p>
                        <p class="text-[10px] sm:text-sm font-semibold text-[#4a7c2c]">{{ $product->stok }} {{ $product->satuan }}</p>
                    </div>
                </div>

                <form action="{{ route('pembeli.cartAdd', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full bg-[#4a7c2c] hover:bg-[#2d5016] text-white py-1.5 sm:py-2.5 rounded-lg text-[10px] sm:text-sm md:text-base font-semibold transition-all duration-300 flex items-center justify-center gap-1 sm:gap-2 group-hover:scale-105">
                        <i data-lucide="shopping-cart" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                        <span class="hidden sm:inline">Tambah ke Keranjang</span>
                        <span class="sm:hidden">Tambah ke Keranjang</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-2 lg:col-span-4 text-center py-8">
            <p class="text-gray-500">Belum ada produk tersedia</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Section: Testimoni -->
<div class="mb-10">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-[#2d5016] mb-2">
            Kata Mereka Tentang Kami
        </h2>
        <p class="text-gray-600">Testimoni dari para pembeli setia SalaKita</p>
    </div>

    @if($testimonials->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($testimonials as $testimonial)
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-[#4a7c2c] flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($testimonial->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-semibold text-[#2d5016]">
                        {{ $testimonial->user->name ?? 'Pembeli' }}
                    </h3>
                    <div class="flex items-center gap-1">
                        @for ($i = 0; $i < $testimonial->rating; $i++)
                        <i data-lucide="star" class="w-3 h-3 fill-[#ff8f00] text-[#ff8f00]"></i>
                        @endfor
                    </div>
                </div>
            </div>
            <p class="text-gray-600 text-sm italic">"{{ $testimonial->review }}"</p>
            <p class="text-xs text-gray-400 mt-3">
                {{ $testimonial->reviewed_at->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
            </p>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8">
        <p class="text-gray-500">Belum ada testimoni tersedia</p>
    </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>

@endsection
