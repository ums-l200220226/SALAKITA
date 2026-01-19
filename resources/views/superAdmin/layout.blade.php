<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - SalaKita</title>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f5f1e8] min-h-screen flex">

{{-- SIDEBAR --}}
<aside id="sidebar"
       class="w-64 bg-[#2d5016] text-white p-6 flex flex-col fixed inset-y-0 left-0 transform
              -translate-x-full lg:translate-x-0 transition-transform duration-200 z-50 shadow-xl">

    {{-- Logo --}}
    <div class="mb-1 pb-1 border-b border-gray-100">
        <div class="flex justify-center mb-1">
            <img src="/img/SalaKitaTanpaBg.png" alt="Logo Salakita" class="h-10 w-auto object-contain">
        </div>
        <p class="text-xs text-center text-white">Super Admin</p>
    </div>

    <nav class="flex-1 space-y-1 mt-2">

        <a href="{{ route('superAdmin.dashboardSuperAdmin') }}"
            class="flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200
                {{ request()->routeIs('superAdmin.dashboardSuperAdmin') ? 'bg-[#4a7c2c] text-white shadow-md' : 'text-white hover:bg-[#4a7c2c]' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('superAdmin.tokoPetani') }}"
            class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-[#4a7c2c] transition-all duration-200
                  {{ request()->routeIs('superAdmin.tokoPetani') ? 'bg-[#4a7c2c] shadow-md' : '' }}">
            <i data-lucide="store" class="w-5 h-5"></i>
            <span class="font-medium">Toko Petani</span>
        </a>

        <a href="{{ route('superAdmin.konfirmasiPetani') }}"
           class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-[#4a7c2c] transition-all duration-200
                  {{ request()->routeIs('superAdmin.konfirmasiPetani') ? 'bg-[#4a7c2c] shadow-md' : '' }}">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span class="font-medium">Konfirmasi Petani</span>
        </a>

        <a href="{{ route('superAdmin.kelolaLandingPage') }}"
           class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-[#4a7c2c] transition-all duration-200
                  {{ request()->routeIs('superAdmin.kelolaLandingPage') ? 'bg-[#4a7c2c] shadow-md' : '' }}">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span class="font-medium">Kelola Landing Page</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="mt-auto pt-6 border-t border-gray-100">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 py-3 px-4 rounded-lg text-gray-100 hover:bg-red-50 hover:text-red-600 transition group">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- MAIN CONTENT --}}
<div class="flex-1 flex flex-col lg:ml-64">

    {{-- TOPBAR --}}
    <div class="w-full flex justify-between items-center px-6 py-4 bg-white shadow-md sticky top-0 z-40">

        {{-- Mobile menu button --}}
        <button class="lg:hidden" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
            <i data-lucide="menu" class="w-7 h-7 text-[#2d5016]"></i>
        </button>

        <div class="flex items-center gap-4 ml-auto">
            {{-- User Info --}}
            <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                <div class="w-10 h-10 bg-[#4a7c2c] rounded-full flex items-center justify-center text-white font-semibold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-[#2d5016]">{{ Auth::user()->name }}</p>
                    <p class="text-[#6d4c41] text-xs hidden sm:block">Super Admin</p>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <main class="flex-1 p-6 sm:p-8 overflow-y-auto">
        @yield('content')
    </main>
</div>

<script>
    lucide.createIcons();

    // Close sidebar when clicking outside on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');

        document.addEventListener('click', function(event) {
            if (window.innerWidth < 1024) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnMenuButton = event.target.closest('button');

                if (!isClickInsideSidebar && !isClickOnMenuButton && !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });
    });
</script>

</body>
</html>
