@extends('pembeli.layout')

@section('content')

<!-- Header Toko - Simpel -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 py-6 mb-6 mx-4">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 sm:gap-6">

            <!-- Foto Toko -->
            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden border-4 border-white shadow-xl flex-shrink-0 bg-white">
                @if($toko->logo_toko)
                    <img src="{{ asset('storage/' . $toko->logo_toko) }}"
                         alt="{{ $toko->nama_toko }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-[#f5f1e8]">
                        <i data-lucide="store" class="w-12 h-12 sm:w-16 sm:h-16 text-[#4a7c2c]"></i>
                    </div>
                @endif
            </div>

            <!-- Info Toko -->
            <div class="flex-1 w-full">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 mb-2">
                    <a href="{{ route('toko.index') }}" class="hover:text-[#4a7c2c] transition">Semua Toko</a>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    <span class="text-[#2d5016] font-medium">{{ $toko->nama_toko }}</span>
                </div>

                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-[#2d5016] mb-3">{{ $toko->nama_toko }}</h1>

                <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm">
                    <!-- Pemilik -->
                    <div class="flex items-center gap-2 text-gray-600">
                        <i data-lucide="user" class="w-4 h-4 sm:w-5 sm:h-5 text-[#6d4c41]"></i>
                        <span>{{ $toko->user->name ?? 'Pemilik' }}</span>
                    </div>

                    <!-- Jumlah Produk -->
                    <div class="flex items-center gap-2 text-[#4a7c2c] font-semibold">
                        <i data-lucide="package" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        <span>{{ $products->total() }} Produk</span>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if($toko->deskripsi_toko)
                <p class="text-sm sm:text-base text-gray-600 mt-3 line-clamp-2">{{ $toko->deskripsi_toko }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 pb-12">

    <!-- Search & Filter -->
    <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                <input type="text"
                       id="searchProduct"
                       placeholder="Cari produk..."
                       class="w-full pl-9 sm:pl-10 pr-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
            </div>

            <!-- Filter Kategori -->
            <select id="filterKategori"
                    class="px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                <option value="">Semua Kategori</option>
                <option value="sayuran">Sayuran</option>
                <option value="buah">Buah</option>
                <option value="beras">Beras</option>
                <option value="rempah">Rempah</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
    </div>

    <!-- Product Grid -->
    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 mb-8">
        @forelse($products as $product)
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 overflow-hidden group product-card flex flex-col"
            data-kategori="{{ $product->kategori }}">

            <!-- Image Container -->
            <div class="relative overflow-hidden">
                <img src="{{ $product->gambar ? asset('storage/' . $product->gambar) : '/img/placeholder.jpg' }}"
                    class="h-32 sm:h-40 md:h-48 w-full object-cover transition-transform duration-500 group-hover:scale-110"
                    alt="{{ $product->nama_produk }}"
                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f5f1e8%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2218%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E'">

                <!-- Badge Status -->
                @if($product->stok > 0)
                <div class="absolute top-2 left-2 bg-[#7cb342] text-white px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold">
                    Tersedia
                </div>
                @else
                <div class="absolute top-2 left-2 bg-gray-500 text-white px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold">
                    Stok Habis
                </div>
                @endif
            </div>

            <!-- Content - Menggunakan flex-1 untuk mengisi ruang yang tersisa -->
            <div class="p-2.5 sm:p-4 flex flex-col flex-1">
                <!-- Bagian atas yang flexible (bisa beda tinggi) -->
                <div class="flex-1">
                    <!-- Kategori -->
                    <span class="inline-block px-2 py-0.5 bg-[#f5f1e8] text-[#6d4c41] text-[10px] sm:text-xs font-medium rounded mb-2">
                        {{ ucfirst($product->kategori) }}
                    </span>

                    <!----- Nama Produk ----->
                    <h4 class="font-bold text-sm sm:text-base md:text-lg text-[#2d5016] mb-1 line-clamp-1">
                    {{ $product->nama_produk }}
                    </h4>

                    <!----- ✅ DESKRIPSI PRODUK ----->
                    @if($product->deskripsi)
                    <p class="text-[9px] sm:text-xs text-gray-600 mb-2 line-clamp-3">
                        {{ $product->deskripsi }}
                    </p>
                    @else
                    <!-- Spacer untuk card tanpa deskripsi supaya tetap sejajar -->
                    <div class="h-8 sm:h-9 mb-2"></div>
                    @endif
                </div>

                <!-- Bagian bawah yang selalu sejajar -->
                <div class="mt-auto">
                    <!----- Rating Dinamis ----->
                    <div class="flex items-center gap-0.5 mb-2 sm:mb-3">
                        @php
                            $avgRating = round($product->avg_rating, 1);
                            $reviewCount = $product->review_count;
                            $fullStars = floor($avgRating);
                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                        @endphp

                        @if($reviewCount > 0)
                            {{-- Bintang penuh --}}
                            @for ($i = 0; $i < $fullStars; $i++)
                            <i data-lucide="star" class="w-3 h-3 sm:w-4 sm:h-4 fill-[#ff8f00] text-[#ff8f00]"></i>
                        @endfor

                        {{-- Setengah bintang --}}
                        @if($hasHalfStar)
                        <i data-lucide="star-half" class="w-3 h-3 sm:w-4 sm:h-4 fill-[#ff8f00] text-[#ff8f00]"></i>
                        @endif

                        {{-- Bintang kosong --}}
                        @for ($i = 0; $i < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $i++)
                        <i data-lucide="star" class="w-3 h-3 sm:w-4 sm:h-4 text-gray-300"></i>
                        @endfor

                        <span class="text-[10px] sm:text-sm text-gray-600 ml-1">
                            ({{ number_format($avgRating, 1) }}) · {{ $reviewCount }} ulasan
                        </span>
                    @else
                        {{-- Jika belum ada review --}}
                        @for ($i = 0; $i < 5; $i++)
                        <i data-lucide="star" class="w-3 h-3 sm:w-4 sm:h-4 text-gray-300"></i>
                        @endfor
                        <span class="text-[10px] sm:text-sm text-gray-500 ml-1">Belum ada ulasan</span>
                        @endif
                    </div>

                    <!----- Harga ----->
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div>
                            <p class="text-base sm:text-xl md:text-2xl font-bold text-[#2d5016]">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </p>
                            <p class="text-[9px] sm:text-xs text-gray-500">per {{ $product->satuan }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] sm:text-sm text-gray-600">Stok:</p>
                            <p class="text-[10px] sm:text-sm font-semibold {{ $product->stok > 0 ? 'text-[#4a7c2c]' : 'text-red-500' }}">
                                {{ $product->stok }}
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('pembeli.cartAdd', $product->id) }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-[#4a7c2c] hover:bg-[#2d5016] text-white py-1.5 sm:py-2.5 rounded-lg text-[10px] sm:text-sm md:text-base font-semibold transition-all duration-300 flex items-center justify-center gap-1 sm:gap-2 group-hover:scale-105 {{ $product->stok <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $product->stok <= 0 ? 'disabled' : '' }}>
                            <i data-lucide="shopping-cart" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                            <span class="hidden sm:inline">Tambah</span>
                            <span class="sm:hidden">Tambah ke Keranjang</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-2 sm:col-span-3 lg:col-span-4 xl:grid-cols-5">
            <div class="bg-white p-12 rounded-xl shadow-sm border border-gray-100 text-center">
                <div class="w-20 h-20 bg-[#f5f1e8] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="package-open" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Produk</h3>
                <p class="text-gray-500">Toko ini belum memiliki produk yang tersedia</p>
            </div>
        </div>
        @endforelse
    </div>
    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-8">
        {{ $products->links() }}
    </div>
    @endif

</div>

<script>
    lucide.createIcons();

    // Search & Filter functionality
    const searchInput = document.getElementById('searchProduct');
    const filterKategori = document.getElementById('filterKategori');
    const productCards = document.querySelectorAll('.product-card');

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedKategori = filterKategori.value.toLowerCase();

        productCards.forEach(card => {
            const productName = card.querySelector('h4').textContent.toLowerCase();
            const productKategori = card.dataset.kategori.toLowerCase();

            const matchSearch = productName.includes(searchTerm);
            const matchKategori = selectedKategori === '' || productKategori === selectedKategori;

            if (matchSearch && matchKategori) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterProducts);
    filterKategori.addEventListener('change', filterProducts);
</script>

@endsection
