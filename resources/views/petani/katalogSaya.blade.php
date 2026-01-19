@extends('petani.layout')

@section('content')

<!-- Header Section dengan Tombol Toko -->
<div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-[27px] font-bold text-[#2d5016] mb-2">Toko dan Katalog Produk Saya</h1>
        <p class="text-gray-600">Kelola toko dan produk yang Anda jual</p>
    </div>
    <button onclick="openTokoModal()"
            class="inline-flex items-center justify-center gap-2 bg-[#ff8f00] hover:bg-[#f57c00] text-white px-6 py-3 rounded-lg font-medium transition shadow-sm hover:shadow-md">
        <i data-lucide="store" class="w-5 h-5"></i>
        <span>Kelola Toko</span>
    </button>
</div>

<!-- Modal Kelola Toko -->
<div id="tokoModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 invisible transition-all duration-200">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="store" class="w-6 h-6 text-[#ff8f00]"></i>
                <h2 class="text-xl font-bold text-[#4a7c2c]">Kelola Toko Saya</h2>
            </div>
            <button onclick="closeTokoModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- BODY -->
        <form id="tokoForm" action="{{ route('petani.tokoUpdate') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <!-- FOTO TOKO -->
                <div class="text-center">
                    <div class="relative inline-block">
                        <div class="w-32 h-32 rounded-full bg-[#f5f1e8] flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                            @if(isset($toko) && $toko->logo_toko)
                                <img src="{{ asset('storage/'.$toko->logo_toko) }}" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="store" class="w-16 h-16 text-gray-400"></i>
                            @endif
                        </div>
                        <label for="logo_toko" class="absolute bottom-0 right-0 w-10 h-10 bg-[#ff8f00] text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg">
                            <i data-lucide="camera" class="w-5 h-5"></i>
                        </label>
                    </div>
                    <input type="file" name="logo_toko" id="logo_toko" class="hidden">
                </div>

                <!-- NAMA TOKO -->
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Toko</label>
                    <input type="text" name="nama_toko" required
                        value="{{ $toko->nama_toko ?? '' }}"
                        class="w-full px-4 py-3 border rounded-lg">
                </div>

                <!-- ALAMAT -->
                <div>
                    <label class="block text-sm font-medium mb-2">Alamat Toko</label>
                    <textarea name="alamat_toko" required rows="3"
                        class="w-full px-4 py-3 border rounded-lg">{{ $toko->alamat_toko ?? '' }}</textarea>
                </div>

                <!-- TELEPON -->
                <div>
                    <label class="block text-sm font-medium mb-2">No. Telepon</label>
                    <input type="text" name="no_telp_toko" required
                        value="{{ $toko->no_telp_toko ?? '' }}"
                        class="w-full px-4 py-3 border rounded-lg">
                </div>

                <!-- DESKRIPSI -->
                <div>
                    <label class="block text-sm font-medium mb-2">Deskripsi Toko</label>
                    <textarea name="deskripsi_toko" rows="4"
                        class="w-full px-4 py-3 border rounded-lg">{{ $toko->deskripsi_toko ?? '' }}</textarea>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="flex gap-3 mt-6 pt-6 border-t">
                <button type="button" onclick="closeTokoModal()" class="flex-1 border px-4 py-3 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-[#ff8f00] text-white px-4 py-3 rounded-lg">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Search Only -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="relative">
        <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
        <input type="text"
               id="searchInput"
               placeholder="Cari produk..."
               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
    </div>
</div>

<!-- Stats Summary -->
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-sm text-gray-600 mb-1">Total Produk</p>
        <p class="text-2xl font-bold text-[#7cb342]">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-sm text-gray-600 mb-1">Produk Aktif</p>
        <p class="text-2xl font-bold text-[#7cb342]">{{ $stats['aktif'] }}</p>
    </div>
</div>

<!-- Tambah Produk Button -->
<div class="mb-6">
    <button onclick="openModal('add')"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#4a7c2c] hover:bg-[#2d5016] text-white px-6 py-3 rounded-lg font-medium transition shadow-sm hover:shadow-md">
        <i data-lucide="plus" class="w-5 h-5"></i>
        Tambah Produk
    </button>
</div>

<!-- Product Grid -->
<div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

    @forelse($products as $product)
    <!-- Product Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
        <!-- Image -->
        <div class="relative h-48 bg-[#f5f1e8] overflow-hidden">
            @if($product->gambar && file_exists(public_path('storage/' . $product->gambar)))
                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f5f1e8%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2218%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E';">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                    <i data-lucide="image" class="w-16 h-16 mb-2"></i>
                    <span class="text-sm">Tidak ada gambar</span>
                </div>
            @endif

            <!-- Status Badge -->
            <span class="absolute top-3 right-3 px-3 py-1 {{ $product->status === 'aktif' ? 'bg-[#7cb342]' : 'bg-gray-400' }} text-white text-xs font-semibold rounded-full">
                {{ ucfirst($product->status) }}
            </span>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Category -->
            <span class="inline-block px-2 py-1 bg-[#f5f1e8] text-[#6d4c41] text-xs font-medium rounded mb-2">
                {{ ucfirst($product->kategori) }}
            </span>

            <!-- Product Name -->
            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-[#4a7c2c] transition">
                {{ $product->nama_produk }}
            </h3>

            <!-- Price & Stock -->
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500">Harga</p>
                    <p class="text-xl font-bold text-[#4a7c2c]">
                        {{ $product->formatted_harga }}
                        <span class="text-sm font-normal text-gray-500">/{{ $product->satuan }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Stok</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $product->stok }} {{ $product->satuan }}</p>
                </div>
            </div>

            <!-- Sales Info -->
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                <span>Terjual: <strong class="text-[#4a7c2c]">{{ $product->total_terjual }} {{ $product->satuan }}</strong></span>
            </div>

            <!-- Deskripsi -->
            @if($product->deskripsi)
            <div class="mb-4 pb-4 border-b border-gray-100">
                <p class="text-sm text-gray-600 line-clamp-2">{{ $product->deskripsi }}</p>
            </div>
            @else
            <div class="mb-4 pb-4 border-b border-gray-100">
                <p class="text-sm text-gray-400 italic">Tidak ada deskripsi</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="grid grid-cols-2 gap-2">
                <button onclick="editProduct({{ $product->id }})"
                        class="flex items-center justify-center gap-1 px-3 py-2 bg-[#f5f1e8] hover:bg-[#ff8f00] text-[#ff8f00] hover:text-white rounded-lg transition text-sm font-medium">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    <span>Edit</span>
                </button>
                <button onclick="confirmDelete({{ $product->id }}, '{{ $product->nama_produk }}')"
                        class="flex items-center justify-center gap-1 px-3 py-2 bg-[#f5f1e8] hover:bg-red-500 text-red-500 hover:text-white rounded-lg transition text-sm font-medium">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="col-span-full bg-white p-12 rounded-xl shadow-sm border border-gray-100 text-center">
        <div class="w-20 h-20 bg-[#f5f1e8] rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="package-open" class="w-10 h-10 text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Produk</h3>
        <p class="text-gray-500 mb-6">Mulai tambahkan produk pertama Anda untuk dijual</p>
        <button onclick="openModal('add')"
                class="inline-flex items-center gap-2 bg-[#4a7c2c] hover:bg-[#2d5016] text-white px-6 py-3 rounded-lg font-medium transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Produk Sekarang
        </button>
    </div>
    @endforelse

</div>

<!-- Pagination -->
@if($products->hasPages())
<div class="mt-8">
    {{ $products->links() }}
</div>
@endif

<!-- Modal Tambah/Edit Produk -->
<div id="productModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 invisible transition-all duration-200">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h2 id="modalTitle" class="text-xl font-bold text-[#4a7c2c]">Tambah Produk</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="productForm" action="{{ route('petani.katalogStore') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" id="formMethod" name="_method" value="">

            <div class="space-y-4">
                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_produk" id="nama_produk" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                           placeholder="Contoh: Tomat Segar Organik">
                    <p class="text-xs text-gray-500 mt-1">Wajib diisi</p>
                </div>

                <!-- Kategori & Satuan -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori" id="kategori" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                            <option value="">Pilih Kategori</option>
                            <option value="sayuran">Sayuran</option>
                            <option value="buah">Buah</option>
                            <option value="beras">Beras</option>
                            <option value="rempah">Rempah</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="satuan" id="satuan" required value="kg"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                               placeholder="kg, ikat, pcs">
                    </div>
                </div>

                <!-- Harga & Stok -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga *</label>
                        <input type="number" name="harga" id="harga" required min="0" step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                               placeholder="15000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stok *</label>
                        <input type="number" name="stok" id="stok" required min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                               placeholder="100">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                        <option value="aktif">Aktif (Dijual)</option>
                        <option value="nonaktif">Tidak Aktif</option>
                    </select>
                </div>

                <!-- Gambar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                    <input type="file" name="gambar" id="gambar" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                              placeholder="Deskripsi produk, kualitas, cara penyimpanan, dll"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-[#4a7c2c] hover:bg-[#2d5016] text-white rounded-lg transition">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 invisible transition-all duration-200">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <!-- Icon -->
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="trash-2" class="w-8 h-8 text-red-500"></i>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Hapus Produk?</h3>

            <!-- Message -->
            <p class="text-gray-600 text-center mb-6">
                Apakah Anda yakin ingin menghapus produk "<span id="deleteProductName" class="font-semibold text-gray-800"></span>"?
                <span class="block mt-1 text-sm text-red-600">Tindakan ini tidak dapat dibatalkan.</span>
            </p>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="button" onclick="executeDelete()"
                        class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    lucide.createIcons();

    // Toko Modal Functions
    function openTokoModal() {
        const modal = document.getElementById('tokoModal');
        modal.classList.remove('opacity-0', 'invisible');
        lucide.createIcons();
    }

    function closeTokoModal() {
        const modal = document.getElementById('tokoModal');
        modal.classList.add('opacity-0', 'invisible');
    }

    function previewTokoImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('tokoImagePreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Open Modal
    function openModal(mode, productId = null) {
        const modal = document.getElementById('productModal');
        const form = document.getElementById('productForm');
        const title = document.getElementById('modalTitle');
        const methodInput = document.getElementById('formMethod');

        if (mode === 'add') {
            title.textContent = 'Tambah Produk';
            form.action = "{{ route('petani.katalogStore') }}";
            methodInput.value = '';
            form.reset();
        } else if (mode === 'edit' && productId) {
            title.textContent = 'Edit Produk';
            form.action = `/katalog-saya/${productId}`;
            methodInput.value = 'PUT';
        }

        modal.classList.remove('opacity-0', 'invisible');
        lucide.createIcons();
    }

    // Close Modal
    function closeModal() {
        const modal = document.getElementById('productModal');
        modal.classList.add('opacity-0', 'invisible');
    }
    function editProduct(id) {
        console.log('Edit product ID:', id);

        // Show loading state
        openModal('edit', id);

        // Fetch product data
        const url = `/katalog-saya/${id}/edit`;
        console.log('Fetching from:', url);

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(product => {
                console.log('Product data received:', product);

                // Fill form with product data
                document.getElementById('nama_produk').value = product.nama_produk || '';
                document.getElementById('kategori').value = product.kategori || '';
                document.getElementById('satuan').value = product.satuan || '';
                document.getElementById('harga').value = product.harga || '';
                document.getElementById('stok').value = product.stok || '';
                document.getElementById('status').value = product.status || 'aktif';
                document.getElementById('deskripsi').value = product.deskripsi || '';

                console.log('Form filled successfully');
            })
            .catch(error => {
                console.error('Full error:', error);
                closeModal();
                alert('Gagal memuat data produk. Silakan coba lagi. Error: ' + error.message);
            });
    }

    // Delete Modal Variables
    let deleteProductId = null;

    // Confirm Delete
    function confirmDelete(id, productName) {
        deleteProductId = id;
        document.getElementById('deleteProductName').textContent = productName;
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('opacity-0', 'invisible');
        lucide.createIcons();
    }

    // Close Delete Modal
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('opacity-0', 'invisible');
        deleteProductId = null;
    }

    // Execute Delete
    function executeDelete() {
        if (deleteProductId) {
            const form = document.getElementById('deleteForm');
            form.action = `/katalog-saya/${deleteProductId}`;
            form.submit();
        }
    }

    // Search Filter
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('#productGrid > div');

        cards.forEach(card => {
            const productName = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const productCategory = card.querySelector('span.inline-block')?.textContent.toLowerCase() || '';

            // Cari di nama produk ATAU kategori
            if (productName.includes(searchTerm) || productCategory.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Close modal when clicking outside
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>

@endsection
