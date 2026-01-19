@extends('pembeli.layout')

@section('content')

<!-- Hero Section - LEBIH SIMPEL -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 py-4 sm:py-6 mb-4 sm:mb-6 mx-4">
    <div class="container mx-auto px-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-[#2d5016] mb-1">Daftar Toko Petani</h1>
                <p class="text-xs sm:text-sm md:text-base text-gray-600">Produk segar langsung dari petani lokal</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-2 bg-[#f5f1e8] rounded-lg self-start">
                <i data-lucide="store" class="w-4 h-4 sm:w-5 sm:h-5 text-[#4a7c2c]"></i>
                <span class="text-sm sm:text-base font-semibold text-[#2d5016]">{{ $tokos->total() }} Toko</span>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 pb-12">

    <!-- Search Bar -->
    <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
        <div class="relative">
            <i data-lucide="search" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
            <input type="text"
                   id="searchToko"
                   placeholder="Cari nama toko atau lokasi..."
                   class="w-full pl-9 sm:pl-10 pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
        </div>
    </div>

    <!-- Toko Grid - RESPONSIVE FIX -->
    <div id="tokoGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
        @forelse($tokos as $toko)
        <a href="{{ route('toko.show', $toko->id) }}" class="block group">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 h-full flex flex-col">

                <!-- Header dengan Foto & Badge -->
                <div class="relative h-36 sm:h-40 bg-gradient-to-br from-[#7cb342] to-[#4a7c2c] overflow-hidden flex-shrink-0">
                    @if($toko->logo_toko)
                        <img src="{{ asset('storage/' . $toko->logo_toko) }}"
                             alt="{{ $toko->nama_toko }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="store" class="w-12 h-12 sm:w-16 sm:h-16 text-white/40"></i>
                        </div>
                    @endif

                    <!-- Badge Produk -->
                    <div class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-white/95 backdrop-blur-sm px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-lg">
                        <div class="flex items-center gap-1 sm:gap-1.5">
                            <i data-lucide="package" class="w-3 h-3 sm:w-4 sm:h-4 text-[#4a7c2c]"></i>
                            <span class="text-xs sm:text-sm font-semibold text-[#2d5016]">{{ $toko->products_count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-3 sm:p-4 flex flex-col flex-1">
                    <!-- Nama Toko -->
                    <h3 class="font-bold text-base sm:text-lg text-[#2d5016] mb-2 sm:mb-3 group-hover:text-[#4a7c2c] transition line-clamp-1">
                        {{ $toko->nama_toko }}
                    </h3>

                    <!-- Info Grid -->
                    <div class="space-y-1.5 sm:space-y-2 mb-3 sm:mb-4 flex-1">
                        <!-- Pemilik -->
                        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600">
                            <i data-lucide="user" class="w-3 h-3 sm:w-4 sm:h-4 text-[#6d4c41] flex-shrink-0"></i>
                            <span class="line-clamp-1">{{ $toko->user->name ?? 'Pemilik' }}</span>
                        </div>

                        <!-- Alamat -->
                        <div class="flex items-start gap-2 text-xs sm:text-sm text-gray-600">
                            <i data-lucide="map-pin" class="w-3 h-3 sm:w-4 sm:h-4 text-[#ff8f00] mt-0.5 flex-shrink-0"></i>
                            <span class="line-clamp-2">{{ $toko->alamat_toko ?? 'Alamat belum diisi' }}</span>
                        </div>

                        <!-- Deskripsi -->
                        @if($toko->deskripsi_toko)
                        <div class="flex items-start gap-2 text-xs sm:text-sm text-gray-500 italic">
                            <i data-lucide="info" class="w-3 h-3 sm:w-4 sm:h-4 text-[#4a7c2c] mt-0.5 flex-shrink-0"></i>
                            <span class="line-clamp-2">{{ $toko->deskripsi_toko }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- CTA -->
                    <div class="pt-2 sm:pt-3 border-t border-gray-100 mt-auto">
                        <div class="flex items-center justify-center gap-2 text-[#4a7c2c] group-hover:text-[#2d5016] font-semibold text-xs sm:text-sm transition">
                            <span>Lihat Produk</span>
                            <i data-lucide="arrow-right" class="w-3 h-3 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        @empty
        <!-- Empty State -->
        <div class="col-span-full">
            <div class="bg-white p-12 rounded-xl shadow-sm border border-gray-100 text-center">
                <div class="w-20 h-20 bg-[#f5f1e8] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="store" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Toko</h3>
                <p class="text-gray-500">Belum ada toko yang terdaftar saat ini</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tokos->hasPages())
    <div class="mt-8">
        {{ $tokos->links() }}
    </div>
    @endif

</div>

<script>
    lucide.createIcons();

    // Search
    document.getElementById('searchToko').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const tokoCards = document.querySelectorAll('#tokoGrid > a');

        tokoCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });
</script>

@endsection
