@extends('pembeli.layout')

@section('content')
<div class="container mx-auto px-4 py-1">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-[#2d5016] mb-2">Riwayat Pesanan</h1>
        <p class="text-gray-600">Lihat dan kelola pesanan Anda</p>
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

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm mb-6 p-4 flex gap-3 overflow-x-auto">
        <a href="{{ route('pembeli.riwayatPesanan') }}"
           class="px-4 py-2 rounded-lg {{ !request('status') || request('status') == 'semua' ? 'bg-[#4a7c2c] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} font-semibold whitespace-nowrap transition">
            Semua
        </a>
        <a href="{{ route('pembeli.riwayatPesanan', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-lg {{ request('status') == 'pending' ? 'bg-[#4a7c2c] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} font-semibold whitespace-nowrap transition">
            Belum Bayar
        </a>
        <a href="{{ route('pembeli.riwayatPesanan', ['status' => 'diproses']) }}"
           class="px-4 py-2 rounded-lg {{ request('status') == 'diproses' ? 'bg-[#4a7c2c] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} font-semibold whitespace-nowrap transition">
            Diproses
        </a>
        <a href="{{ route('pembeli.riwayatPesanan', ['status' => 'dikirim']) }}"
           class="px-4 py-2 rounded-lg {{ request('status') == 'dikirim' ? 'bg-[#4a7c2c] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} font-semibold whitespace-nowrap transition">
            Dikirim
        </a>
        <a href="{{ route('pembeli.riwayatPesanan', ['status' => 'selesai']) }}"
           class="px-4 py-2 rounded-lg {{ request('status') == 'selesai' ? 'bg-[#4a7c2c] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} font-semibold whitespace-nowrap transition">
            Selesai
        </a>
    </div>

    <!-- Orders Grid -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition overflow-hidden">
                <!-- Order Header -->
                <div class="bg-[#f5f1e8] px-4 py-3 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="store" class="w-4 h-4 text-[#4a7c2c]"></i>
                                <span class="font-semibold text-[#2d5016] text-sm sm:text-base">{{ $order->toko->nama_toko }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="hidden sm:inline text-sm text-gray-600">|</span>
                                <span class="text-xs sm:text-sm text-gray-600">{{ $order->order_number }}</span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center gap-3">
                            @if($order->status_pembayaran === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full whitespace-nowrap">
                                    Menunggu Pembayaran
                                </span>
                            @elseif($order->status_pembayaran === 'paid')
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full whitespace-nowrap">
                                    Sudah Dibayar
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full whitespace-nowrap">
                                    Dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Body (Summary) -->
                <div class="p-4">
                    <!-- Products Summary -->
                    <div class="space-y-3 mb-4">
                        @foreach($order->items as $item)
                        <div class="flex gap-3">
                            <img src="{{ $item->product && $item->product->gambar ? asset('storage/' . $item->product->gambar) : '/img/placeholder.jpg' }}"
                                 alt="{{ $item->product_name }}"
                                 class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-[#2d5016] text-sm truncate">{{ $item->product_name }}</h4>
                                <p class="text-xs text-gray-600 mt-1">{{ $item->quantity }} {{ $item->satuan }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="font-bold text-[#4a7c2c] text-sm sm:text-base">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Total & Toggle Button -->
                    <div class="border-t border-gray-200 pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="text-sm text-gray-600">
                            <p class="mb-1">
                                <i data-lucide="calendar" class="w-4 h-4 inline"></i>
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>

                            {{-- Status Proses Pesanan --}}
                            @if($order->status === 'dikonfirmasi')
                                <p class="mt-2 font-semibold flex items-center gap-1 text-xs sm:text-sm" style="color:#5d4037;">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                    Pesanan sedang dikonfirmasi
                                </p>

                            @elseif($order->status === 'diproses')
                                <p class="mt-2 font-semibold flex items-center gap-1 text-xs sm:text-sm text-blue-700">
                                    <i data-lucide="loader" class="w-4 h-4"></i>
                                    Pesanan sedang diproses
                                </p>

                            @elseif($order->status === 'dikirim')
                                <p class="mt-2 font-semibold flex items-center gap-1 text-xs sm:text-sm text-purple-700">
                                    <i data-lucide="truck" class="w-4 h-4"></i>
                                    Pesanan sedang dikirim
                                </p>

                            @elseif($order->status === 'selesai')
                                <p class="mt-2 font-semibold flex items-center gap-1 text-xs sm:text-sm text-green-700">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Pesanan selesai
                                </p>

                            @elseif($order->status === 'dibatalkan')
                                <p class="mt-2 font-semibold flex items-center gap-1 text-xs sm:text-sm text-red-700">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    Pesanan dibatalkan
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-4">
                            <div class="text-left sm:text-right">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Pembayaran</p>
                                <p class="text-lg sm:text-xl font-bold text-[#2d5016]">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Toggle Detail Button -->
                            <button onclick="toggleDetail({{ $order->id }})"
                                    class="p-2 bg-[#f5f1e8] hover:bg-[#e8dfc8] rounded-lg transition flex-shrink-0"
                                    id="toggle-btn-{{ $order->id }}">
                                <i data-lucide="chevron-down" class="w-5 h-5 text-[#4a7c2c]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Collapsible Detail Section -->
                    <div id="detail-{{ $order->id }}" class="hidden mt-4 pt-4 border-t border-gray-200">
                        <!-- Informasi Pengiriman -->
                        <div class="mb-4">
                            <h3 class="font-semibold text-[#2d5016] mb-3 flex items-center gap-2">
                                <i data-lucide="truck" class="w-4 h-4"></i>
                                Informasi Pengiriman
                            </h3>
                            <div class="bg-[#f5f1e8] rounded-lg p-3 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Metode Penerimaan:</span>
                                    <span class="font-semibold text-[#2d5016]">{{ ucfirst($order->metode_penerimaan) }}</span>
                                </div>

                                @if($order->metode_penerimaan === 'dikirim')
                                    <div>
                                        <p class="text-gray-600 mb-1">Alamat Pengiriman:</p>
                                        <p class="font-semibold text-[#2d5016]">{{ $order->alamat_lengkap }}</p>
                                        <p class="text-gray-600 text-xs">
                                            {{ $order->city ? $order->city->name : '' }},
                                            {{ $order->province ? $order->province->name : '' }}
                                        </p>
                                    </div>
                                @elseif($order->metode_penerimaan === 'diambil')
                                    <div>
                                        <p class="text-gray-600 mb-1">Jadwal Pengambilan:</p>
                                        <p class="font-semibold text-[#2d5016]">
                                            {{ \Carbon\Carbon::parse($order->tanggal_pengambilan)->translatedFormat('d F Y') }}
                                        </p>
                                        <p class="text-gray-600 text-xs">
                                            Jam {{ $order->jam_pengambilan }} WIB
                                        </p>
                                    </div>
                                @endif

                                @if($order->catatan)
                                    <div>
                                        <p class="text-gray-600 mb-1">Catatan:</p>
                                        <p class="text-[#2d5016]">{{ $order->catatan }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Rincian Pembayaran -->
                        <div class="mb-4">
                            <h3 class="font-semibold text-[#2d5016] mb-3 flex items-center gap-2">
                                <i data-lucide="wallet" class="w-4 h-4"></i>
                                Rincian Pembayaran
                            </h3>
                            <div class="bg-[#f5f1e8] rounded-lg p-3 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal Produk:</span>
                                    <span class="font-semibold text-[#2d5016]">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>

                                @if($order->metode_penerimaan === 'dikirim')
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Ongkir:</span>
                                        <span class="font-semibold text-[#2d5016]">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between border-t border-gray-300 pt-2">
                                    <span class="font-bold text-[#2d5016]">Total:</span>
                                    <span class="font-bold text-[#4a7c2c] text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600">Metode Pembayaran:</span>
                                    <span class="font-semibold text-[#2d5016]">{{ strtoupper($order->metode_pembayaran) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Review Section (jika sudah selesai) -->
                        @if($order->status === 'selesai')
                            @if($order->rating)
                                <!-- Review sudah diberikan -->
                                <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-green-800 mb-3 flex items-center gap-2">
                                        <i data-lucide="star" class="w-4 h-4"></i>
                                        Review Anda
                                    </h3>
                                    <div class="flex items-center gap-2 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="w-5 h-5 {{ $i <= $order->rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="text-sm font-semibold text-gray-700">{{ $order->rating }}/5</span>
                                    </div>
                                    @if($order->review)
                                        <p class="text-sm text-gray-700 italic">"{{ $order->review }}"</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i data-lucide="calendar" class="w-3 h-3 inline"></i>
                                        {{ $order->reviewed_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            @else
                                <!-- Form review -->
                                <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-yellow-800 mb-3 flex items-center gap-2">
                                        <i data-lucide="star" class="w-4 h-4"></i>
                                        Berikan Rating
                                    </h3>
                                    <form action="{{ route('pembeli.storeReview', $order->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                            <div class="flex gap-2" id="rating-{{ $order->id }}">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden rating-input" required>
                                                        <svg class="rating-star w-8 h-8 text-gray-300 transition" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.965a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.382 2.456a1 1 0 00-.364 1.118l1.286 3.965c.3.921-.755 1.688-1.54 1.118l-3.382-2.456a1 1 0 00-1.176 0l-3.382 2.456c-.784.57-1.838-.197-1.539-1.118l1.286-3.965a1 1 0 00-.364-1.118L2.07 9.392c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.965z"/>
                                                        </svg>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Review (Opsional)</label>
                                            <textarea name="review" rows="3"
                                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                                                      placeholder="Bagikan pengalaman Anda..."></textarea>
                                        </div>
                                        <button type="submit"
                                                class="w-full bg-[#4a7c2c] hover:bg-[#2d5016] text-white px-4 py-2 rounded-lg font-semibold transition">
                                            Kirim Review
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif

                        <!-- Action Buttons in Detail -->
                        <div class="flex flex-col sm:flex-row gap-2 justify-end">
                            @if($order->status_pembayaran === 'pending' && $order->metode_pembayaran === 'qris')
                                <a href="{{ route('payment.qris', $order->id) }}"
                                   class="px-4 py-2 bg-[#ff8f00] hover:bg-[#f57c00] text-white rounded-lg font-semibold text-sm transition text-center">
                                    Bayar Sekarang
                                </a>
                            @endif

                            <!-- Tombol Pesanan Diterima (Hanya untuk metode dikirim dan status dikirim) -->
                            @if($order->metode_penerimaan === 'dikirim' && $order->status === 'dikirim')
                                <button type="button"
                                        onclick="openConfirmModal({{ $order->id }})"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold text-sm transition">
                                    <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i>
                                    Pesanan Diterima
                                </button>

                                <!-- Form tersembunyi untuk submit -->
                                <form id="confirm-form-{{ $order->id }}"
                                      action="{{ route('pembeli.selesaikanPesanan', $order->id) }}"
                                      method="POST" class="hidden">
                                    @csrf
                                </form>
                            @endif

                            @if($order->status_pembayaran === 'paid' && $order->status === 'selesai')
                                <form action="{{ route('pembeli.beliLagi', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 bg-[#7cb342] hover:bg-[#689f38] text-white rounded-lg font-semibold text-sm transition flex items-center gap-2">
                                        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                        Beli Lagi
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="w-32 h-32 mx-auto mb-6 bg-[#f5f1e8] rounded-full flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-16 h-16 text-[#7cb342]"></i>
            </div>
            <h3 class="text-xl font-bold text-[#2d5016] mb-2">Belum Ada Pesanan</h3>
            <p class="text-gray-600 mb-6">Yuk mulai belanja produk agribisnis lokal!</p>
            <a href="{{ route('toko.index') }}"
               class="inline-block px-6 py-3 bg-[#4a7c2c] hover:bg-[#2d5016] text-white rounded-lg font-semibold transition">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>

<!-- Modal Konfirmasi Pesanan Diterima -->
<div id="confirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl transform transition-all">
            <!-- Icon -->
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="package-check" class="w-8 h-8 text-green-600"></i>
            </div>

            <!-- Header -->
            <h3 class="text-xl font-bold text-[#2d5016] text-center mb-2">Konfirmasi Penerimaan</h3>
            <p class="text-gray-600 text-center mb-6">Apakah Anda yakin pesanan sudah diterima dengan baik?</p>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmModal()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-bold transition-all">
                    Belum
                </button>
                <button type="button" onclick="submitConfirm()"
                        class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-3 rounded-xl font-bold transition-all shadow-lg hover:shadow-xl">
                    Ya, Sudah Diterima
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;

// Open confirm modal
function openConfirmModal(orderId) {
    currentOrderId = orderId;
    document.getElementById('confirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    lucide.createIcons();
}

// Close confirm modal
function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentOrderId = null;
}

// Submit confirmation
function submitConfirm() {
    if (currentOrderId) {
        document.getElementById('confirm-form-' + currentOrderId).submit();
    }
}

// Close modal when clicking backdrop
document.getElementById('confirmModal')?.addEventListener('click', function(e) {
    if (e.target === this || e.target.classList.contains('backdrop-blur-sm')) {
        closeConfirmModal();
    }
});

// Close with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmModal();
    }
});

// Toggle Detail Accordion
function toggleDetail(orderId) {
    const detailDiv = document.getElementById('detail-' + orderId);
    const toggleBtn = document.getElementById('toggle-btn-' + orderId);
    const icon = toggleBtn.querySelector('[data-lucide]');

    if (detailDiv.classList.contains('hidden')) {
        detailDiv.classList.remove('hidden');
        icon.setAttribute('data-lucide', 'chevron-up');
    } else {
        detailDiv.classList.add('hidden');
        icon.setAttribute('data-lucide', 'chevron-down');
    }

    lucide.createIcons();
}

// Rating Star Interaction
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[id^="rating-"]').forEach(container => {
        const stars = container.querySelectorAll('.rating-star');
        const inputs = container.querySelectorAll('.rating-input');
        let selectedRating = 0;

        stars.forEach((star, index) => {

            star.addEventListener('click', () => {
                selectedRating = index + 1;
                inputs[index].checked = true;
                updateStars(selectedRating);
            });

            star.addEventListener('mouseenter', () => {
                updateStars(index + 1);
            });
        });

        container.addEventListener('mouseleave', () => {
            updateStars(selectedRating);
        });

        function updateStars(rating) {
            stars.forEach((star, i) => {
                star.classList.toggle('text-yellow-400', i < rating);
                star.classList.toggle('text-gray-300', i >= rating);
            });
        }
    });

});
</script>
@endsection
