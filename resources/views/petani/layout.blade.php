<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petani - Salakita</title>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex bg-[#f5f1e8]">

<!-- SIDEBAR -->
<aside id="sidebar"
       class="w-64 bg-white p-6 flex flex-col fixed inset-y-0 left-0
              -translate-x-full lg:translate-x-0 transition-transform duration-200 z-50 shadow-lg border-r border-gray-100">

    <!-- Logo -->
    <div class="mb-1 pb-1 border-b border-gray-100">
        <div class="flex justify-center mb-1">
            <img src="/img/SalaKita.png" alt="Logo Salakita" class="h-10 w-auto object-contain">
        </div>
        <p class="text-xs text-center text-gray-500">Agribisnis Digital</p>
    </div>

    <nav class="flex-1 space-y-1 mt-2">
        <a href="{{ route('petani.dashboardPetani') }}"
            class="flex items-center gap-3 py-3 px-4 rounded-lg transition
                {{ request()->routeIs('petani.dashboardPetani') ? 'bg-[#4a7c2c] text-white' : 'text-gray-700 hover:bg-[#f5f1e8]' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('petani.katalogSaya') }}"
            class="flex items-center gap-3 py-3 px-4 rounded-lg transition
                {{ request()->routeIs('petani.katalogSaya') ? 'bg-[#4a7c2c] text-white' : 'text-gray-700 hover:bg-[#f5f1e8]' }}">
            <i data-lucide="package" class="w-5 h-5"></i>
            <span class="font-medium">Toko dan Katalog</span>
        </a>

        <a href="{{ route('petani.kelolaPesanan') }}"
            class="flex items-center gap-3 py-3 px-4 rounded-lg transition
                {{ request()->routeIs('petani.kelolaPesanan') ? 'bg-[#4a7c2c] text-white' : 'text-gray-700 hover:bg-[#f5f1e8]' }}">
            <i data-lucide="file-text" class="w-5 h-5"></i>
            <span class="font-medium">Kelola Pesanan</span>
        </a>

        <a href="{{ route('petani.cekHargaPasar') }}"
            class="flex items-center gap-3 py-3 px-4 rounded-lg transition
                {{ request()->routeIs('petani.cekHargaPasar') ? 'bg-[#4a7c2c] text-white' : 'text-gray-700 hover:bg-[#f5f1e8]' }}">
            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            <span class="font-medium">Cek Harga Pasar</span>
        </a>

        <a href="{{ route('petani.profil') }}"
            class="flex items-center gap-3 py-3 px-4 rounded-lg transition
                {{ request()->routeIs('petani.profil') ? 'bg-[#4a7c2c] text-white' : 'text-gray-700 hover:bg-[#f5f1e8]' }}">
            <i data-lucide="user" class="w-5 h-5"></i>
            <span class="font-medium">Profil</span>
        </a>

    </nav>

    <!-- Logout -->
    <div class="mt-auto pt-6 border-t border-gray-100">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button" onclick="confirmLogout()"
                class="w-full flex items-center gap-3 py-3 px-4 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 transition group">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>

</aside>

<!-- MAIN CONTENT -->
<div class="flex-1 flex flex-col lg:ml-64">

    <!-- TOPBAR -->
    <div class="w-full flex justify-between items-center px-6 py-4
                bg-white shadow-sm border-b border-gray-100 sticky top-0 z-40">

        <button class="lg:hidden"
                onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
            <i data-lucide="menu" class="w-7 h-7 text-[#4a7c2c]"></i>
        </button>

        <div class="flex items-center space-x-4 ml-auto">
            <!-- Notifikasi -->
            <a href="{{ route('petani.kelolaPesanan') }}" class="relative p-2 rounded-lg hover:bg-[#f5f1e8] transition">
                <i data-lucide="bell" class="w-6 h-6 text-[#4a7c2c]"></i>
                @if($newOrdersCount > 0)
                    <span class="absolute top-1 right-1 bg-[#ff8f00] text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-semibold">
                        {{ $newOrdersCount > 9 ? '9+' : $newOrdersCount }}
                    </span>
                @endif
            </a>

            <!-- User Info -->
            <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                <div class="w-10 h-10 rounded-full bg-[#7cb342] flex items-center justify-center text-white font-semibold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-[#4a7c2c]">{{ Auth::user()->name }}</p>
                    <p class="text-gray-500 text-xs">Petani</p>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-1 p-6 lg:p-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 px-8">
        <div class="flex flex-col md:flex-row justify-center md:justify-between items-center text-sm text-gray-600">
            <p class="text-center md:text-left">SalaKita - Platform Agribisnis Digital</p>
            <!-- Bisa tambahin copyright di sini kalau mau -->
            <p class="text-center md:text-right mt-2 md:mt-0">© 2025 Aminah Estu Utami</p>
        </div>
    </footer>
</div>

<script> lucide.createIcons(); </script>
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
    const sidebar = document.getElementById('sidebar');

    // Klik di luar sidebar → sidebar nutup
    document.addEventListener('click', function (e) {
        if (
            window.innerWidth < 1024 &&
            !sidebar.contains(e.target) &&
            !e.target.closest('button[onclick]')
        ) {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>
</body>
</html>
