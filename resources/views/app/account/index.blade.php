@extends('app.layouts.main')

@section('title', 'Akun & Pengaturan')

@section('content')
<div class="space-y-5 md:space-y-6" x-data="accountPage()">
    <!-- Profile Card (Scaled on Desktop) -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm flex items-center gap-4 md:gap-6">
        <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xl md:text-3xl flex-shrink-0 shadow-md">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
            <h2 class="text-base md:text-xl font-bold text-zinc-900 dark:text-white truncate">{{ $user->name }}</h2>
            <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 truncate">{{ $user->email }}</p>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="px-2.5 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-[10px] md:text-xs font-bold">
                    {{ $store ? $store->name : 'Admin Toko' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation Menu Group -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm divide-y divide-zinc-100 dark:divide-zinc-800">
        <!-- 1. Pengaturan Toko -->
        <a href="{{ route('app.store.edit') }}" class="flex items-center justify-between p-4 md:p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
            <div class="flex items-center gap-3.5">
                <span class="text-xl md:text-2xl">🏪</span>
                <div>
                    <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Pengaturan Toko</h3>
                    <p class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400">Edit logo, banner, No. WA, dan warna</p>
                </div>
            </div>
            <span class="text-zinc-400 font-bold">→</span>
        </a>

        <!-- 2. Pengaturan Akun -->
        <a href="{{ route('app.account.edit') }}" class="flex items-center justify-between p-4 md:p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
            <div class="flex items-center gap-3.5">
                <span class="text-xl md:text-2xl">👤</span>
                <div>
                    <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Pengaturan Akun</h3>
                    <p class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400">Ubah nama, email, dan kata sandi</p>
                </div>
            </div>
            <span class="text-zinc-400 font-bold">→</span>
        </a>

        <!-- 3. Kelola Produk -->
        <a href="{{ route('app.products.index') }}" class="flex items-center justify-between p-4 md:p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
            <div class="flex items-center gap-3.5">
                <span class="text-xl md:text-2xl">📦</span>
                <div>
                    <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Kelola Produk</h3>
                    <p class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400">Daftar produk, harga, dan foto</p>
                </div>
            </div>
            <span class="text-zinc-400 font-bold">→</span>
        </a>

        <!-- 4. Lihat Katalog Public -->
        @if($store && $store->slug)
            <a href="{{ route('catalog.show', $store->slug) }}" target="_blank" class="flex items-center justify-between p-4 md:p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                <div class="flex items-center gap-3.5">
                    <span class="text-xl md:text-2xl">🌐</span>
                    <div>
                        <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Lihat Katalog Public</h3>
                        <p class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400">Buka tampilan katalog pembeli</p>
                    </div>
                </div>
                <span class="text-zinc-400 font-bold">↗</span>
            </a>
        @endif

        <!-- 5. Desktop Admin Panel (If Super Admin / Administrator) -->
        @if($user->hasAnyRole(['administrator', 'admin', 'super-admin']))
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between p-4 md:p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                <div class="flex items-center gap-3.5">
                    <span class="text-xl md:text-2xl">🖥️</span>
                    <div>
                        <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Admin Panel Desktop</h3>
                        <p class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400">Akses panel lengkap Super Admin</p>
                    </div>
                </div>
                <span class="text-zinc-400 font-bold">→</span>
            </a>
        @endif
    </div>

    <!-- Preferences Group -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tampilan App</h3>

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <span class="text-xl md:text-2xl" x-text="isDark ? '🌙' : '☀️'"></span>
                <div>
                    <h4 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Mode Gelap (Dark Mode)</h4>
                    <p class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400">Sesuaikan tema tampilan app</p>
                </div>
            </div>
            <button @click="toggleDarkMode()" 
                    class="w-12 h-7 rounded-full p-1 transition-colors relative"
                    :class="isDark ? 'bg-emerald-600' : 'bg-zinc-300'">
                <div class="w-5 h-5 rounded-full bg-white transition-transform shadow-md"
                     :class="isDark ? 'translate-x-5' : 'translate-x-0'"></div>
            </button>
        </div>
    </div>

    <!-- Logout Button -->
    <form action="{{ route('admin.logout') }}" method="POST" class="md:flex md:justify-end">
        @csrf
        <button type="submit" class="w-full md:w-auto md:px-12 h-12 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-600 dark:text-red-400 font-bold text-sm flex items-center justify-center gap-2 hover:bg-red-100 transition-colors">
            <span>🚪</span>
            <span>Keluar Akun</span>
        </button>
    </form>
</div>

<script>
    function accountPage() {
        return {
            isDark: document.documentElement.classList.contains('dark'),
            toggleDarkMode() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('appTheme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('appTheme', 'light');
                }
            }
        }
    }
</script>
@endsection
