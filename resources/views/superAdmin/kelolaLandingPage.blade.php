@extends('superAdmin.layout')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-[#2d5016] text-white px-6 py-4">
            <h2 class="text-2xl font-bold">Kelola Landing Page</h2>
            <p class="text-sm text-gray-200 mt-1">Edit konten yang tampil di halaman utama website</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span><strong>Berhasil!</strong> {{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-green-700 hover:text-green-900">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.landingPage.update') }}" method="POST" enctype="multipart/form-data" id="landingPageForm">
                @csrf

                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <div class="flex flex-wrap gap-2" id="tabs">
                        <button type="button" onclick="switchTab('hero')"
                            class="tab-btn px-4 py-2 font-medium text-sm rounded-t-lg transition-all border-b-2 border-transparent hover:border-[#2d5016] hover:text-[#2d5016]"
                            data-tab="hero">Hero Section</button>
                        <button type="button" onclick="switchTab('features')"
                            class="tab-btn px-4 py-2 font-medium text-sm rounded-t-lg transition-all border-b-2 border-transparent hover:border-[#2d5016] hover:text-[#2d5016]"
                            data-tab="features">Fitur</button>
                        <button type="button" onclick="switchTab('history')"
                            class="tab-btn px-4 py-2 font-medium text-sm rounded-t-lg transition-all border-b-2 border-transparent hover:border-[#2d5016] hover:text-[#2d5016]"
                            data-tab="history">Sejarah</button>
                        <button type="button" onclick="switchTab('products')"
                            class="tab-btn px-4 py-2 font-medium text-sm rounded-t-lg transition-all border-b-2 border-transparent hover:border-[#2d5016] hover:text-[#2d5016]"
                            data-tab="products">Produk</button>
                        <button type="button" onclick="switchTab('location')"
                            class="tab-btn px-4 py-2 font-medium text-sm rounded-t-lg transition-all border-b-2 border-transparent hover:border-[#2d5016] hover:text-[#2d5016]"
                            data-tab="location">Lokasi</button>
                        <button type="button" onclick="switchTab('cta')"
                            class="tab-btn px-4 py-2 font-medium text-sm rounded-t-lg transition-all border-b-2 border-transparent hover:border-[#2d5016] hover:text-[#2d5016]"
                            data-tab="cta">Call to Action</button>
                        <button type="button" onclick="switchTab('footer')"
                            class="tab-btn px-4 py-2 font-medium text-sm rounded-t-lg transition-all border-b-2 border-transparent hover:border-[#2d5016] hover:text-[#2d5016]"
                            data-tab="footer">Footer</button>
                    </div>
                </div>

                <!-- Tab Contents -->
                <div id="tab-contents">

                    <!-- HERO SECTION -->
                    <div id="hero-content" class="tab-content">
                        <h3 class="text-lg font-bold text-[#2d5016] mb-4">Hero Section</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Utama</label>
                                <input type="text" name="hero_title"
                                    value="{{ old('hero_title', $settings->hero_title) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>
                                @error('hero_title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="hero_description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>{{ old('hero_description', $settings->hero_description) }}</textarea>
                                @error('hero_description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                                @if($settings->hero_image)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $settings->hero_image) }}"
                                            alt="Hero Image" class="h-40 object-cover rounded-lg border border-gray-300">
                                    </div>
                                @endif
                                <input type="file" name="hero_image" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent">
                                <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG. Max: 2MB</p>
                                @error('hero_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                                @if($settings->logo_image)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $settings->logo_image) }}"
                                            alt="Logo" class="h-20 object-contain rounded-lg border border-gray-300 p-2 bg-white">
                                    </div>
                                @endif
                                <input type="file" name="logo_image" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent">
                                <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG. Max: 2MB</p>
                                @error('logo_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- FEATURES SECTION -->
                    <div id="features-content" class="tab-content hidden">
                        <h3 class="text-lg font-bold text-[#2d5016] mb-4">Section Fitur</h3>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Section</label>
                            <input type="text" name="features_title"
                                value="{{ old('features_title', $settings->features_title) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                required>
                            @error('features_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="my-6">

                        <h4 class="font-semibold text-gray-700 mb-2">Daftar Fitur</h4>
                        <p class="text-sm text-gray-500 mb-4">Untuk mengubah icon SVG atau menambah fitur baru, silakan hubungi developer.</p>

                        <div class="space-y-4">
                            @foreach($settings->features as $index => $feature)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-[#2d5016] mb-3">Fitur {{ $index + 1 }}</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600 mb-1">Judul Fitur</label>
                                            <input type="text" value="{{ $feature->title }}" readonly
                                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600">
                                            <p class="text-xs text-gray-500 mt-1">Edit manual via form terpisah</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi</label>
                                            <input type="text" value="{{ $feature->description }}" readonly
                                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- HISTORY SECTION -->
                    <div id="history-content" class="tab-content hidden">
                        <h3 class="text-lg font-bold text-[#2d5016] mb-4">Section Sejarah</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                                <input type="text" name="history_title"
                                    value="{{ old('history_title', $settings->history_title) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>
                                @error('history_title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Konten Sejarah</label>
                                <textarea name="history_content" rows="8"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>{{ old('history_content', $settings->history_content) }}</textarea>
                                @error('history_content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- PRODUCTS SECTION -->
                    <div id="products-content" class="tab-content hidden">
                        <h3 class="text-lg font-bold text-[#2d5016] mb-4">Section Produk</h3>

                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="show_products_section" id="showProducts"
                                    {{ old('show_products_section', $settings->show_products_section) ? 'checked' : '' }}
                                    class="w-5 h-5 text-[#2d5016] border-gray-300 rounded focus:ring-[#2d5016]">
                                <label for="showProducts" class="text-sm font-medium text-gray-700">
                                    Tampilkan Section Produk
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Section</label>
                                <input type="text" name="products_title"
                                    value="{{ old('products_title', $settings->products_title) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>
                                @error('products_title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="products_description" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>{{ old('products_description', $settings->products_description) }}</textarea>
                                @error('products_description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <hr class="my-6">

                            <h4 class="font-semibold text-gray-700 mb-4">Statistik</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaksi Sukses</label>
                                    <input type="number" name="stats_transactions"
                                        value="{{ old('stats_transactions', $settings->stats_transactions) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                        required min="0">
                                    @error('stats_transactions')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Produk Tersedia</label>
                                    <input type="number" name="stats_products"
                                        value="{{ old('stats_products', $settings->stats_products) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                        required min="0">
                                    @error('stats_products')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating (0-5)</label>
                                    <input type="number" name="stats_rating"
                                        value="{{ old('stats_rating', $settings->stats_rating) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                        required min="0" max="5" step="0.1">
                                    @error('stats_rating')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LOCATION SECTION -->
                    <div id="location-content" class="tab-content hidden">
                        <h3 class="text-lg font-bold text-[#2d5016] mb-4">Section Lokasi</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Section</label>
                                <input type="text" name="location_title"
                                    value="{{ old('location_title', $settings->location_title) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>
                                @error('location_title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea name="location_address" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>{{ old('location_address', $settings->location_address) }}</textarea>
                                @error('location_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon/WhatsApp</label>
                                    <input type="text" name="location_phone"
                                        value="{{ old('location_phone', $settings->location_phone) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                        required>
                                    @error('location_phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="location_email"
                                        value="{{ old('location_email', $settings->location_email) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                        required>
                                    @error('location_email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Operasional</label>
                                <textarea name="location_hours" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>{{ old('location_hours', $settings->location_hours) }}</textarea>
                                @error('location_hours')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps Embed URL</label>
                                <input type="url" name="location_map_url"
                                    value="{{ old('location_map_url', $settings->location_map_url) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent">
                                <p class="text-gray-500 text-sm mt-1">Copy URL dari Google Maps → Share → Embed a map</p>
                                @error('location_map_url')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- CTA SECTION -->
                    <div id="cta-content" class="tab-content hidden">
                        <h3 class="text-lg font-bold text-[#2d5016] mb-4">Call to Action Section</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul CTA</label>
                                <input type="text" name="cta_title"
                                    value="{{ old('cta_title', $settings->cta_title) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>
                                @error('cta_title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi CTA</label>
                                <textarea name="cta_description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                    required>{{ old('cta_description', $settings->cta_description) }}</textarea>
                                @error('cta_description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER SECTION -->
                    <div id="footer-content" class="tab-content hidden">
                        <h3 class="text-lg font-bold text-[#2d5016] mb-4">Footer</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Text</label>
                            <input type="text" name="footer_copyright"
                                value="{{ old('footer_copyright', $settings->footer_copyright) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d5016] focus:border-transparent"
                                required>
                            @error('footer_copyright')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex gap-3">
                    <button type="submit" class="px-6 py-3 bg-[#2d5016] text-white font-semibold rounded-lg hover:bg-[#4a7c2c] transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <span>Save</span>
                    </button>

                    <a href="{{ route('superAdmin.dashboardSuperAdmin') }}"
                        class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-all flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Tab switching functionality
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-[#2d5016]', 'border-[#2d5016]');
        btn.classList.add('text-gray-600');
    });

    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');

    // Add active class to selected tab button
    const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
    if (activeBtn) {
        activeBtn.classList.add('text-[#2d5016]', 'border-[#2d5016]');
        activeBtn.classList.remove('text-gray-600');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tab
    switchTab('hero');

    // Initialize lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Get form and button
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');

    console.log('Form found:', !!form);
    console.log('Button found:', !!submitBtn);

    if (form) {
        console.log('Form action:', form.action);

        // Add submit event listener
        form.addEventListener('submit', function(e) {
            console.log('Form submitting to:', this.action);
        });
    }
});
</script>
@endsection
