<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->hero_title }} - Platform Agribisnis Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fadeInUp-delay-1 {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .animate-fadeInUp-delay-2 {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Product card hover effect */
        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
        }
    </style>
</head>
<body class="overflow-x-hidden">
    <!-- Navigation Bar -->
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                @if($settings->logo_image)
                    <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="{{ $settings->hero_title }} Logo" class="h-10 sm:h-13 w-auto">
                @else
                    <img src="{{ asset('img/SalaKita.png') }}" alt="SalaKita Logo" class="h-10 sm:h-13 w-auto">
                @endif
            </div>
            <div class="flex gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="px-4 sm:px-6 py-2 sm:py-2.5 border-2 border-[#2d5016] text-[#2d5016] font-semibold rounded-lg hover:bg-[#2d5016] hover:text-white transition-all text-sm sm:text-base">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-4 sm:px-6 py-2 sm:py-2.5 bg-[#2d5016] text-white font-semibold rounded-lg hover:bg-[#4a7c2c] hover:-translate-y-0.5 hover:shadow-lg transition-all text-sm sm:text-base">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    <!-- Section 1: Hero / Intro -->
    <section class="mt-16 sm:mt-20 min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center z-0"
            style="background-image: url('{{ $settings->hero_image ? asset('storage/' . $settings->hero_image) : asset('img/LandingPage2.jpeg') }}');">
        </div>
        <div class="absolute inset-0 bg-black/40 z-10"></div>

        <!-- Content -->
        <div class="text-center max-w-3xl px-6 sm:px-8 py-20 animate-fadeInUp relative z-20 text-white">
            <div class="mb-4">
                @if($settings->logo_image)
                    <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="Logo" class="w-24 sm:w-30 h-auto mx-auto">
                @else
                    <img src="/img/SalaKitaLandingPage1.png" alt="Salak" class="w-24 sm:w-30 h-auto mx-auto">
                @endif
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 drop-shadow-lg">{{ $settings->hero_title }}</h1>

            <p class="text-lg sm:text-xl md:text-2xl mb-8 leading-relaxed drop-shadow-md">
                {{ $settings->hero_description }}
            </p>

            <!-- Scroll Down Indicator -->
            <div class="mt-12 animate-bounce">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
                <p class="text-sm mt-2 opacity-90">Scroll ke bawah</p>
            </div>
        </div>
    </section>

    <!-- Section 2: Features + Sejarah -->
    <section class="min-h-screen flex flex-col items-center justify-start bg-[#f5f1e8] py-12 sm:py-16 md:py-20">
        <div class="max-w-6xl w-full px-4 sm:px-6 md:px-10 text-center">

            <!-- Bagian Fitur -->
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#2d5016] mb-8 sm:mb-10 animate-fadeInUp">
                {{ $settings->features_title }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 md:gap-8 mb-12 sm:mb-14">

                @foreach($settings->features as $index => $feature)
                <!-- Fitur {{ $index + 1 }} -->
                <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-lg hover:-translate-y-2 hover:shadow-xl transition-all animate-fadeInUp{{ $index > 0 ? '-delay-' . $index : '' }}">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#4a7c2c] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            {!! $feature->icon_svg !!}
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-[#2d5016] mb-2">{{ $feature->title }}</h3>
                    <p class="text-sm sm:text-base text-[#6d4c41]">{{ $feature->description }}</p>
                </div>
                @endforeach
            </div>

            <!-- Bagian Sejarah -->
            <div class="bg-white rounded-2xl shadow-md p-6 sm:p-8 md:p-10 animate-fadeInUp">
                <h3 class="text-2xl sm:text-3xl font-bold text-[#2d5016] mb-4">
                    {{ $settings->history_title }}
                </h3>

                <div class="text-sm sm:text-base md:text-lg text-[#6d4c41] leading-relaxed text-justify whitespace-pre-line">
                    {{ $settings->history_content }}
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Produk Terlaris -->
    @if($settings->show_products_section)
    <section class="min-h-screen bg-[#2d5016] py-12 sm:py-16 md:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-10">

            <!-- Header Section -->
            <div class="text-center text-white mb-10 sm:mb-12">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">{{ $settings->products_title }}</h2>
                <p class="text-base sm:text-lg md:text-xl opacity-90">
                    {{ $settings->products_description }}
                </p>
            </div>

            <!-- Product Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-12 sm:mb-16">

                @forelse ($produkTerlaris as $produk)
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-xl">

                    {{-- Gambar / Placeholder --}}
                    <div class="h-48 sm:h-56 bg-amber-100 flex items-center justify-center">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}"
                                alt="{{ $produk->nama_produk }}"
                                class="w-full h-full object-cover">
                        @else
                            <i data-lucide="package" class="w-16 h-16 text-[#2d5016]"></i>
                        @endif
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl sm:text-2xl font-bold text-[#2d5016]">
                                {{ $produk->nama_produk }}
                            </h3>

                            @if($loop->first)
                                <span class="bg-[#ff8f00] text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Best Seller
                                </span>
                            @endif
                        </div>

                        {{-- ✅ TAMBAHKAN RATING DI SINI --}}
                        <div class="flex items-center gap-1 mb-3">
                            @php
                                $rating = $produk->average_rating ?? 0;
                                $fullStars = floor($rating);
                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                            @endphp

                            @for ($j = 0; $j < $fullStars; $j++)
                            <i data-lucide="star" class="w-4 h-4 fill-[#ff8f00] text-[#ff8f00]"></i>
                            @endfor

                            @if($hasHalfStar)
                            <i data-lucide="star-half" class="w-4 h-4 fill-[#ff8f00] text-[#ff8f00]"></i>
                            @endif

                            @for ($j = 0; $j < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $j++)
                            <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                            @endfor

                            @if($rating > 0)
                                <span class="text-xs text-gray-600 ml-1">
                                    ({{ number_format($rating, 1) }})
                                </span>
                            @else
                                <span class="text-xs text-gray-400 ml-1">
                                    Belum ada rating
                                </span>
                            @endif
                        </div>

                        <p class="text-sm sm:text-base text-[#6d4c41] mb-4">
                            {{ Str::limit($produk->deskripsi, 80) }}
                        </p>

                        <div>

                        {{-- Harga --}}
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <span class="text-xl sm:text-2xl font-bold text-[#2d5016]">
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                </span>
                                <span class="text-sm text-[#6d4c41]">/kg</span>
                            </div>

                            {{-- ✅ UPDATE: Tampilkan jumlah terjual dari database --}}
                            <span class="text-xs text-[#6d4c41]">
                                Terjual {{ $produk->jumlah_terjual ?? 0 }} kg
                            </span>
                        </div>

                        {{-- Tombol --}}
                        <a href="{{ route('login') }}"
                            class="block w-full text-center
                                bg-[#2d5016] text-white
                                text-sm font-semibold
                                py-2 rounded-lg
                                hover:bg-[#1f3a0f] transition">
                            Beli Sekarang
                        </a>
                    </div>
                </div>
            </div>

            @empty
            <p class="text-center text-[#6d4c41] col-span-3">
                Belum ada produk tersedia
            </p>
            @endforelse

        </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 text-white text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 sm:p-8">
                    <h3 class="text-4xl sm:text-5xl font-bold mb-2">{{ $stats['transaksi'] }}+</h3>
                    <p class="text-base sm:text-lg opacity-90">Transaksi Sukses</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 sm:p-8">
                    <h3 class="text-4xl sm:text-5xl font-bold mb-2">{{ $stats['produk'] }}+</h3>
                    <p class="text-base sm:text-lg opacity-90">Produk Tersedia</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 sm:p-8">
                    <h3 class="text-4xl sm:text-5xl font-bold mb-2">{{ $stats['rating'] }}★</h3>
                    <p class="text-base sm:text-lg opacity-90">Rating Pengguna</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Section 4: Lokasi -->
    <section class="min-h-screen bg-[#f5f1e8] py-12 sm:py-16 md:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-10">

            <div class="text-center mb-10 sm:mb-12">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#2d5016] mb-4">{{ $settings->location_title }}</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

                <!-- Map -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden h-80 sm:h-96">
                    @if($settings->location_map_url)
                        <iframe
                            src="{{ $settings->location_map_url }}"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    @else
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31879.549706285943!2d106.16970028890152!3d-2.8325338172269414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3d7290f7ae124b%3A0xd04568f245e393f5!2sPanca%20Tunggal%2C%20Kec.%20Pulaubesar%2C%20Kabupaten%20Bangka%20Selatan%2C%20Kepulauan%20Bangka%20Belitung!5e0!3m2!1sid!2sid!4v1767449343134!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    @endif
                </div>

                <!-- Info Kontak -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#4a7c2c] rounded-full flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-[#2d5016] mb-2">Alamat</h3>
                                <p class="text-[#6d4c41] whitespace-pre-line">{{ $settings->location_address }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#4a7c2c] rounded-full flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-[#2d5016] mb-2">Kontak</h3>
                                <p class="text-[#6d4c41] mb-2">
                                    <strong>WhatsApp:</strong> {{ $settings->location_phone }}
                                </p>
                                <p class="text-[#6d4c41]">
                                    <strong>Email:</strong> {{ $settings->location_email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#4a7c2c] rounded-full flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-[#2d5016] mb-2">Jam Operasional</h3>
                                <p class="text-[#6d4c41] whitespace-pre-line">{{ $settings->location_hours }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-[#4a7c2c] rounded-3xl shadow-2xl p-8 sm:p-12 text-center text-white">
                <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">{{ $settings->cta_title }}</h3>
                <p class="text-base sm:text-lg md:text-xl mb-8 opacity-95">
                    {{ $settings->cta_description }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 sm:px-7 py-3 sm:py-3 bg-[#ff8f00] text-white text-lg sm:text-xl font-semibold rounded-xl hover:bg-orange-600 hover:-translate-y-1 hover:shadow-xl transition-all">
                        Belanja Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#2d5016] text-white py-3 text-center">
        <div class="flex justify-center gap-6 sm:gap-8 mb-4 flex-wrap px-4">
            <a href="{{ route('tentang') }}" class="hover:text-green-300 transition-colors">Tentang</a>
            <a href="{{ route('bantuan') }}" class="hover:text-green-300 transition-colors">Bantuan</a>
            <a href="{{ route('kontak') }}" class="hover:text-green-300 transition-colors">Kontak</a>
        </div>
        <p class="text-sm sm:text-base">&copy; {{ $settings->footer_copyright }}</p>
    </footer>

    <!-- JS UNTUK MENAMPILKAN BINTANG -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
