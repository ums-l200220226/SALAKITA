@extends('pembeli.layout')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#2d5016] mb-2">Checkout</h1>
        <p class="text-gray-600 text-sm">Lengkapi informasi pemesanan Anda</p>
    </div>

    <div class="mb-4">
        <a href="{{ route('pembeli.cart') }}"
            class="inline-flex items-center gap-2 text-[#4a7c2c] hover:text-[#2d5016] font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Keranjang
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="cart_ids" value="{{ $cartItems->pluck('id')->implode(',') }}">

        {{-- ✅ TAMBAHIN INI UNTUK DEBUG ERROR --}}
        @if ($errors->any())
            <div class="bg-red-100 border-2 border-red-500 text-red-800 px-6 py-4 rounded-xl mb-6">
                <h3 class="font-bold text-lg mb-2">⚠️ Error Validasi:</h3>
                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-2 border-red-500 text-red-800 px-6 py-4 rounded-xl mb-6">
                <h3 class="font-bold text-lg">❌ Error:</h3>
                <p class="text-sm mt-1">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Kiri -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Info Toko -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#7cb342] rounded-full flex items-center justify-center">
                            <i data-lucide="store" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#2d5016]">{{ $cartItems->first()->product->toko->nama_toko ?? 'Toko' }}</h3>
                            <p class="text-xs text-gray-500">{{ $cartItems->count() }} produk dipilih</p>
                        </div>
                    </div>

                    <!-- Daftar Produk -->
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                        <div class="flex gap-3 p-3 bg-[#f5f1e8] rounded-lg">
                            <img src="{{ $item->product->gambar ? asset('storage/' . $item->product->gambar) : '/img/placeholder.jpg' }}"
                                 alt="{{ $item->product->nama_produk }}"
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm text-[#2d5016]">{{ $item->product->nama_produk }}</h4>
                                <p class="text-xs text-gray-600">{{ $item->quantity }} {{ $item->product->satuan }} × Rp {{ number_format($item->product->harga, 0, ',', '.') }}</p>
                                <p class="text-sm font-bold text-[#4a7c2c] mt-1">
                                    Rp {{ number_format($item->quantity * $item->product->harga, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Metode Penerimaan -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
                    <h3 class="font-bold text-[#2d5016] mb-4 flex items-center gap-2">
                        <i data-lucide="truck" class="w-5 h-5"></i>
                        Metode Penerimaan
                    </h3>

                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="metode_penerimaan" value="diambil" class="peer sr-only" required onchange="toggleMetodePenerimaan()">
                            <div class="border-2 border-gray-200 rounded-lg p-4 transition peer-checked:border-[#4a7c2c] peer-checked:bg-[#f5f1e8]">
                                <div class="flex flex-col items-center text-center gap-2">
                                    <i data-lucide="package" class="w-8 h-8 text-[#4a7c2c]"></i>
                                    <span class="font-semibold text-sm">Diambil</span>
                                    <span class="text-xs text-gray-500">Ambil ke penjual</span>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="metode_penerimaan" value="dikirim" class="peer sr-only" required onchange="toggleMetodePenerimaan()">
                            <div class="border-2 border-gray-200 rounded-lg p-4 transition peer-checked:border-[#4a7c2c] peer-checked:bg-[#f5f1e8]">
                                <div class="flex flex-col items-center text-center gap-2">
                                    <i data-lucide="truck" class="w-8 h-8 text-[#4a7c2c]"></i>
                                    <span class="font-semibold text-sm">Dikirim</span>
                                    <span class="text-xs text-gray-500">Sesuai lokasi</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('metode_penerimaan')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Pengambilan (untuk opsi Diambil) -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100" id="pengambilanSection" style="display: none;">
                    <h3 class="font-bold text-[#2d5016] mb-4 flex items-center gap-2">
                        <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                        Jadwal Pengambilan
                    </h3>

                    <div class="space-y-4">
                        <!-- Tanggal Pengambilan -->
                        <div>
                            <label class="block text-sm font-medium text-[#5d4037] mb-2">Tanggal Pengambilan</label>
                            <input type="date"
                                name="tanggal_pengambilan"
                                id="tanggalPengambilan"
                                min="{{ date('Y-m-d') }}"
                                disabled
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                            @error('tanggal_pengambilan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Pengambilan -->
                        <div>
                            <label class="block text-sm font-medium text-[#5d4037] mb-2">Jam Pengambilan</label>
                            <select name="jam_pengambilan"
                                    id="jamPengambilan"
                                    disabled
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                                <option value="">Pilih Jam</option>
                                <option value="08:00">08:00 WIB</option>
                                <option value="09:00">09:00 WIB</option>
                                <option value="10:00">10:00 WIB</option>
                                <option value="11:00">11:00 WIB</option>
                                <option value="12:00">12:00 WIB</option>
                                <option value="13:00">13:00 WIB</option>
                                <option value="14:00">14:00 WIB</option>
                                <option value="15:00">15:00 WIB</option>
                                <option value="16:00">16:00 WIB</option>
                                <option value="17:00">17:00 WIB</option>
                            </select>
                            @error('jam_pengambilan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Pengambilan -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <div class="flex items-start gap-2">
                                <i data-lucide="info" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <div class="text-xs text-green-800">
                                    <strong>Informasi Pengambilan:</strong>
                                    <ul class="mt-1 space-y-1">
                                        <li>• Ambil pesanan di alamat toko penjual</li>
                                        <li>• Harap datang sesuai jadwal yang dipilih</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat Pengiriman (untuk opsi Dikirim) -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100" id="alamatSection" style="display: none;">
                    <h3 class="font-bold text-[#2d5016] mb-4 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        Alamat Pengiriman
                    </h3>

                    <div class="space-y-4">
                        <!-- Provinsi -->
                        <div>
                            <label class="block text-sm font-medium text-[#5d4037] mb-2">Provinsi</label>
                            <select name="province_code" id="provinceSelect" disabled class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $province)
                                <option value="{{ $province->code }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                            @error('province_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota -->
                        <div>
                            <label class="block text-sm font-medium text-[#5d4037] mb-2">Kota/Kabupaten</label>
                            <select name="city_id" id="citySelect" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent" disabled>
                                <option value="">Pilih provinsi terlebih dahulu</option>
                            </select>
                            @error('city_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="block text-sm font-medium text-[#5d4037] mb-2">Alamat Lengkap</label>
                            <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50">
                                <p class="text-sm text-gray-700">
                                    {{ Auth::user()->alamat ?? 'Belum mengisi alamat. Silakan update profil Anda.' }}
                                </p>
                            </div>
                            @if(!Auth::user()->alamat)
                            <p class="text-xs text-red-500 mt-1">
                                ⚠️ Anda belum mengisi alamat.
                                <a href="{{ route('pembeli.profil') }}" class="underline text-[#4a7c2c] font-semibold">Update Profil</a>
                            </p>
                            @endif
                        </div>

                        <!-- Info Ongkir -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-start gap-2">
                                <i data-lucide="info" class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5"></i>
                                <div class="text-xs text-blue-800">
                                    <strong>Estimasi Ongkir:</strong>
                                    <ul class="mt-1 space-y-1">
                                        <li>• Jawa Barat: Rp 10.000</li>
                                        <li>• Jawa Tengah/Timur: Rp 15.000</li>
                                        <li>• Jakarta/Banten: Rp 12.000</li>
                                        <li>• Luar Jawa: Rp 25.000</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
                    <h3 class="font-bold text-[#2d5016] mb-4 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        Metode Pembayaran
                    </h3>

                    <div class="space-y-3">
                        <!-- COD -->
                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-[#f5f1e8] transition has-[:checked]:border-[#4a7c2c] has-[:checked]:bg-[#f5f1e8]">
                            <input type="radio" name="metode_pembayaran" value="cod" class="w-5 h-5 text-[#4a7c2c]" required>
                            <div class="flex-1">
                                <p class="font-semibold text-[#2d5016]">COD (Cash on Delivery)</p>
                                <p class="text-xs text-gray-500">Bayar saat barang diterima</p>
                            </div>
                            <i data-lucide="wallet" class="w-5 h-5 text-[#4a7c2c]"></i>
                        </label>

                        <!-- QRIS - TAMBAHAN BARU INI! -->
                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-[#f5f1e8] transition has-[:checked]:border-[#4a7c2c] has-[:checked]:bg-[#f5f1e8]">
                            <input type="radio" name="metode_pembayaran" value="qris" class="w-5 h-5 text-[#4a7c2c]" required>
                            <div class="flex-1">
                                <p class="font-semibold text-[#2d5016]">QRIS</p>
                                <p class="text-xs text-gray-500">GoPay, OVO, Dana, Mobile Banking</p>
                            </div>
                            <i data-lucide="qr-code" class="w-5 h-5 text-[#4a7c2c]"></i>
                        </label>
                    </div>
                    @error('metode_pembayaran')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
                    <h3 class="font-bold text-[#2d5016] mb-4 flex items-center gap-2">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                        Catatan (Opsional)
                    </h3>
                    <textarea name="catatan" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent" placeholder="Tambahkan catatan untuk penjual..."></textarea>
                </div>

            </div>

            <!-- Ringkasan Pesanan (Kanan) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 border-2 border-[#7cb342] sticky top-24">
                    <h3 class="font-bold text-[#2d5016] mb-4">Ringkasan Pesanan</h3>

                    <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal ({{ $cartItems->count() }} item)</span>
                            <span class="font-semibold text-[#2d5016]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkir</span>
                            <span class="font-semibold text-[#4a7c2c]" id="ongkirDisplay">Rp 0</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-gray-700">Total Bayar</span>
                        <span class="font-bold text-2xl text-[#2d5016]" id="totalDisplay">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>

                    <button type="submit" class="w-full bg-[#4a7c2c] hover:bg-[#2d5016] text-white py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2 shadow-md">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        Buat Pesanan
                    </button>

                    <p class="text-xs text-center text-gray-500 mt-3">
                        Dengan melanjutkan, Anda menyetujui syarat dan ketentuan yang berlaku
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Toggle metode penerimaan (diambil/dikirim)
function toggleMetodePenerimaan() {
    const metodePenerimaan = document.querySelector('input[name="metode_penerimaan"]:checked');
    const pengambilanSection = document.getElementById('pengambilanSection');
    const alamatSection = document.getElementById('alamatSection');

    // Ambil semua input
    const tanggalInput = document.getElementById('tanggalPengambilan');
    const jamInput = document.getElementById('jamPengambilan');
    const provinceInput = document.getElementById('provinceSelect');
    const cityInput = document.getElementById('citySelect');
    const alamatInput = document.getElementById('alamatLengkap');

    if (metodePenerimaan && metodePenerimaan.value === 'diambil') {
        // Tampilkan form pengambilan, sembunyikan form alamat
        pengambilanSection.style.display = 'block';
        alamatSection.style.display = 'none';

        // ENABLE input pengambilan
        tanggalInput.disabled = false;
        jamInput.disabled = false;

        // DISABLE input alamat
        provinceInput.disabled = true;
        cityInput.disabled = true;

        // Reset value alamat
        provinceInput.value = '';
        cityInput.value = '';
        alamatInput.value = '';

        updateTotal(0);

    } else if (metodePenerimaan && metodePenerimaan.value === 'dikirim') {
        // Tampilkan form alamat, sembunyikan form pengambilan
        pengambilanSection.style.display = 'none';
        alamatSection.style.display = 'block';

        // DISABLE input pengambilan
        tanggalInput.disabled = true;
        jamInput.disabled = true;

        // Reset value pengambilan
        tanggalInput.value = '';
        jamInput.value = '';

        // ENABLE input alamat
        provinceInput.disabled = false;
        cityInput.disabled = false;
    }
}

// Load cities based on province
document.getElementById('provinceSelect').addEventListener('change', function() {
    const provinceCode = this.value;
    const citySelect = document.getElementById('citySelect');

    if (!provinceCode) {
        citySelect.innerHTML = '<option value="">Pilih provinsi terlebih dahulu</option>';
        citySelect.disabled = true;
        return;
    }

    citySelect.innerHTML = '<option value="">Loading...</option>';
    citySelect.disabled = true;

    fetch(`{{ url('/api/cities') }}/${provinceCode}`)
        .then(response => response.json())
        .then(cities => {
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            cities.forEach(city => {
                citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
            });
            citySelect.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            citySelect.innerHTML = '<option value="">Error loading cities</option>';
        });
});

// Calculate ongkir when city selected
document.getElementById('citySelect').addEventListener('change', function() {
    const provinceSelect = document.getElementById('provinceSelect');
    const provinceName = provinceSelect.options[provinceSelect.selectedIndex].text.toUpperCase();

    let ongkir = 0;
    if (provinceName.includes('JAWA BARAT')) {
        ongkir = 10000;
    } else if (provinceName.includes('JAWA TENGAH') || provinceName.includes('JAWA TIMUR')) {
        ongkir = 15000;
    } else if (provinceName.includes('JAKARTA') || provinceName.includes('BANTEN')) {
        ongkir = 12000;
    } else {
        ongkir = 25000;
    }

    updateTotal(ongkir);
});

// Update total
function updateTotal(ongkir) {
    const subtotal = {{ $subtotal }};
    const total = subtotal + ongkir;

    document.getElementById('ongkirDisplay').textContent = 'Rp ' + ongkir.toLocaleString('id-ID');
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

lucide.createIcons();
</script>
@endsection
