@extends('pembeli.layout')

@section('content')

<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#2d5016] mb-2">Profil Saya</h1>
        <p class="text-gray-600">Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
    </div>

    <!-- Success/Error Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Side - Form (2 kolom) -->
                <div class="lg:col-span-2">
                    <form action="{{ route('pembeli.updateProfil') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Role -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Role / Daftar Sebagai
                                </label>
                                <input type="text"
                                       value="{{ ucfirst(Auth::user()->role) }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600"
                                       disabled>
                                <p class="text-xs text-gray-500 mt-1">Role tidak dapat diubah</p>
                            </div>

                            <!-- Nama -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ Auth::user()->name }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                                       required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email"
                                       value="{{ Auth::user()->email }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600"
                                       disabled>
                                <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                            </div>

                            <!-- Nomor HP -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor HP
                                </label>
                                <input type="tel"
                                       name="no_hp"
                                       value="{{ Auth::user()->no_hp }}"
                                       placeholder="Contoh: 081234567890"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">
                            </div>

                        </div>

                        <!-- Alamat (Full Width) -->
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea name="alamat"
                                      rows="4"
                                      placeholder="Masukkan alamat lengkap Anda"
                                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent">{{ Auth::user()->alamat }}</textarea>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex gap-3 mt-6">
                            <button type="submit"
                                    class="bg-[#4a7c2c] hover:bg-[#2d5016] text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                                <i data-lucide="save" class="w-5 h-5"></i>
                                Save
                            </button>
                            <a href="{{ route('pembeli.dashboardPembeli') }}"
                               class="py-3 px-6 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Right Side - Avatar & Info (1 kolom) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <div class="text-center bg-[#f5f1e8] rounded-xl p-6">
                            <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-[#4a7c2c] flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <h3 class="font-bold text-xl text-[#2d5016] mb-1">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ Auth::user()->email }}</p>

                            <div class="border-t border-gray-300 pt-4">
                                <div class="flex items-center justify-center gap-2 text-gray-600 mb-2">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    <p class="text-xs">Bergabung sejak</p>
                                </div>
                                <p class="text-sm font-semibold text-[#2d5016]">
                                    {{ Auth::user()->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Change Password Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <h2 class="text-xl font-bold text-[#2d5016] mb-6 flex items-center gap-2">
                <i data-lucide="lock" class="w-5 h-5"></i>
                Ubah Password
            </h2>

            <form action="{{ route('pembeli.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Password Lama -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Lama
                        </label>
                        <input type="password"
                               name="current_password"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                               required>
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <input type="password"
                               name="new_password"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                               required>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password"
                               name="new_password_confirmation"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c2c] focus:border-transparent"
                               required>
                    </div>
                </div>

                <button type="submit"
                        class="mt-6 bg-[#ff8f00] hover:bg-[#e67e00] text-white py-3 px-8 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                    <i data-lucide="key" class="w-5 h-5"></i>
                    Update Password
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    lucide.createIcons();
</script>

@endsection
