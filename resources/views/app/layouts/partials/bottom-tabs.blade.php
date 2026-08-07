@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<nav class="fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-lg border-t border-zinc-200/80 dark:border-zinc-800/80 safe-area-inset-bottom lg:hidden">
    <div class="max-w-md mx-auto h-[64px] grid grid-cols-5 items-center px-1">
        <!-- 1. Home / Dashboard -->
        <a href="{{ route('app.dashboard') }}" 
           class="flex flex-col items-center justify-center h-full gap-1 transition-colors {{ $currentRoute === 'app.dashboard' ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
            <svg class="w-5 h-5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] tracking-tight">Beranda</span>
        </a>

        <!-- 2. Produk -->
        <a href="{{ route('app.products.index') }}" 
           class="flex flex-col items-center justify-center h-full gap-1 transition-colors {{ str_starts_with($currentRoute, 'app.products') && $currentRoute !== 'app.products.create' ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
            <svg class="w-5 h-5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span class="text-[10px] tracking-tight">Produk</span>
        </a>

        <!-- 3. Center FAB: Tambah Produk -->
        <div class="flex items-center justify-center">
            <a href="{{ route('app.products.create') }}" 
               class="w-12 h-12 rounded-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white flex items-center justify-center shadow-lg shadow-emerald-600/30 -mt-5 transition-transform"
               title="Tambah Produk Baru">
                <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>

        <!-- 4. Stats -->
        <a href="{{ route('app.stats.index') }}" 
           class="flex flex-col items-center justify-center h-full gap-1 transition-colors {{ $currentRoute === 'app.stats.index' ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
            <svg class="w-5 h-5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-[10px] tracking-tight">Statistik</span>
        </a>

        <!-- 5. Akun / Toko -->
        <a href="{{ route('app.account.index') }}" 
           class="flex flex-col items-center justify-center h-full gap-1 transition-colors {{ in_array($currentRoute, ['app.account.index', 'app.store.edit']) ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
            <svg class="w-5 h-5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px] tracking-tight">Akun</span>
        </a>
    </div>
</nav>
