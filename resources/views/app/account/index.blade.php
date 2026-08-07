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
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0v-4a1 1 0 011-1h2a1 1 0 011 1v4m-4 0h4" />
                    </svg>
                </div>
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
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
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
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
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
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8M11.5 3a17 17 0 000 18M12.5 3a17 17 0 010 18" />
                        </svg>
                    </div>
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
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
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
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 flex items-center justify-center flex-shrink-0">
                    <template x-if="isDark">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </template>
                    <template x-if="!isDark">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </template>
                </div>
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
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
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
