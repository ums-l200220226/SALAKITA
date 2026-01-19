@extends('petani.layout')

@section('content')

<div class="container mx-auto px-4 py-1">
<!-- Header Section -->
<div class="mb-3">
    <h1 class="text-[27px] font-bold text-[#2d5016] mb-2">Cek Harga Pasar</h1>
    <p class="text-gray-600">Pantau harga produk dari petani lain untuk referensi pricing</p>
</div>

<!-- Search & Filter Section -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
            <div class="relative">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                <input type="text"
                       id="searchInput"
                       placeholder="Cari nama produk... (contoh: tomat, cabai)"
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
            </div>
        </div>

        <!-- Filter Kategori -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
            <select id="filterKategori"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                <option value="">Semua Kategori</option>
                <option value="sayuran">Sayuran</option>
                <option value="buah">Buah</option>
                <option value="beras">Beras</option>
                <option value="rempah">Rempah</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>

        <!-- Filter Satuan -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
            <select id="filterSatuan"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                <option value="">Semua Satuan</option>
                <option value="kg">Kilogram (kg)</option>
                <option value="ikat">Ikat</option>
                <option value="pcs">Pieces (pcs)</option>
                <option value="liter">Liter</option>
                <option value="gram">Gram</option>
            </select>
        </div>

        <!-- Sort -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
            <select id="sortBy"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                <option value="terbaru">Terbaru</option>
                <option value="termurah">Harga Termurah</option>
                <option value="termahal">Harga Termahal</option>
            </select>
        </div>
    </div>

    <!-- Reset Filter Button -->
    <div class="mt-4">
        <button onclick="resetFilters()"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-[#4a7c2c] hover:bg-[#f5f1e8] rounded-lg transition">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
            Reset Filter
        </button>
    </div>
</div>

<!-- Result Info -->
<div class="mb-4">
    <p class="text-gray-600">
        Menampilkan <strong id="resultCount">{{ $products->count() }}</strong> produk
    </p>
</div>

<!-- Product Grid -->
<div id="productGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">

    @forelse($products as $product)
    <!-- Product Card - Compact Version -->
    <div class="product-card bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group"
         data-kategori="{{ $product->kategori }}"
         data-satuan="{{ strtolower($product->satuan) }}"
         data-harga="{{ $product->harga }}"
         data-nama="{{ strtolower($product->nama_produk) }}">

        <!-- Image -->
        <div class="relative h-32 bg-[#f5f1e8] overflow-hidden">
            @if($product->gambar && file_exists(public_path('storage/' . $product->gambar)))
                <img src="{{ asset('storage/' . $product->gambar) }}"
                     alt="{{ $product->nama_produk }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f5f1e8%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2218%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E';">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <i data-lucide="image" class="w-10 h-10"></i>
                </div>
            @endif

            <!-- Category Badge -->
            <span class="absolute top-2 left-2 px-2 py-0.5 bg-white/90 text-[#6d4c41] text-xs font-medium rounded">
                {{ ucfirst($product->kategori) }}
            </span>
        </div>

        <!-- Content -->
        <div class="p-3">
            <!-- Product Name -->
            <h3 class="font-semibold text-sm text-gray-800 mb-1 line-clamp-2 group-hover:text-[#4a7c2c] transition min-h-[2.5rem]">
                {{ $product->nama_produk }}
            </h3>

            <!-- Price -->
            <p class="text-lg font-bold text-[#4a7c2c] mb-2">
                {{ $product->formatted_harga }}
                <span class="text-xs font-normal text-gray-500">/{{ $product->satuan }}</span>
            </p>

            <!-- Store Name -->
            <div class="flex items-center gap-1 text-xs text-gray-600 pt-2 border-t border-gray-100">
                <i data-lucide="store" class="w-3 h-3"></i>
                <span class="truncate">{{ $product->user->name ?? 'Toko Petani' }}</span>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="col-span-full bg-white p-12 rounded-xl shadow-sm border border-gray-100 text-center">
        <div class="w-20 h-20 bg-[#f5f1e8] rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="search-x" class="w-10 h-10 text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Produk</h3>
        <p class="text-gray-500">Belum ada produk yang sesuai dengan pencarian Anda</p>
    </div>
    @endforelse

</div>

<!-- Pagination -->
@if($products->hasPages())
<div class="mt-8">
    {{ $products->links() }}
</div>
@endif

<script>
    lucide.createIcons();

    const searchInput = document.getElementById('searchInput');
    const filterKategori = document.getElementById('filterKategori');
    const filterSatuan = document.getElementById('filterSatuan');
    const sortBy = document.getElementById('sortBy');
    const productGrid = document.getElementById('productGrid');

    // Real-time Filter & Search
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const kategori = filterKategori.value.toLowerCase();
        const satuan = filterSatuan.value.toLowerCase();
        const sort = sortBy.value;

        let cards = Array.from(document.querySelectorAll('.product-card'));
        let visibleCount = 0;

        // Filter cards
        cards.forEach(card => {
            const cardName = card.dataset.nama;
            const cardKategori = card.dataset.kategori;
            const cardSatuan = card.dataset.satuan;

            const matchSearch = !searchTerm || cardName.includes(searchTerm);
            const matchKategori = !kategori || cardKategori === kategori;
            const matchSatuan = !satuan || cardSatuan === satuan;

            if (matchSearch && matchKategori && matchSatuan) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sort visible cards
        const visibleCards = cards.filter(card => card.style.display !== 'none');

        if (sort === 'termurah') {
            visibleCards.sort((a, b) => parseFloat(a.dataset.harga) - parseFloat(b.dataset.harga));
        } else if (sort === 'termahal') {
            visibleCards.sort((a, b) => parseFloat(b.dataset.harga) - parseFloat(a.dataset.harga));
        }

        // Reorder cards in DOM
        visibleCards.forEach(card => productGrid.appendChild(card));

        // Update result count
        document.getElementById('resultCount').textContent = visibleCount;
    }

    // Event Listeners
    searchInput.addEventListener('input', applyFilters);
    filterKategori.addEventListener('change', applyFilters);
    filterSatuan.addEventListener('change', applyFilters);
    sortBy.addEventListener('change', applyFilters);

    // Reset Filters
    function resetFilters() {
        searchInput.value = '';
        filterKategori.value = '';
        filterSatuan.value = '';
        sortBy.value = 'terbaru';
        applyFilters();
    }
</script>

@endsection
