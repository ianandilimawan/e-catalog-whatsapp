@php
    $store = auth()->user()->store;
    $storeLogo = $store && $store->logo ? \App\Services\FileUploadService::getFileUrl($store->logo) : null;
    $themeColor = $store->theme_color ?? '#16a34a';
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<header
    class="fixed top-0 inset-x-0 z-40 h-[56px] bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md border-b border-zinc-200/80 dark:border-zinc-800/80 transition-colors">
    <div class="max-w-md md:max-w-2xl lg:max-w-5xl xl:max-w-6xl mx-auto h-full px-4 flex items-center justify-between">
        <!-- Left: Store Logo & Name -->
        <div class="flex items-center gap-3 overflow-hidden">
            <a href="{{ route('app.dashboard') }}" class="flex items-center gap-2.5 min-w-0">
                <div
                    class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center font-bold text-sm text-zinc-700 dark:text-zinc-300">
                    @if ($storeLogo)
                        <img src="{{ $storeLogo }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($store->name ?? 'Toko', 0, 1)) }}
                    @endif
                </div>
                <div class="truncate">
                    <h1 class="text-sm font-bold text-zinc-900 dark:text-white truncate leading-tight">
                        {{ $store->name ?? 'Toko Saya' }}
                    </h1>
                    {{-- <p class="text-[11px] text-zinc-500 dark:text-zinc-400 truncate flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                        vendor app
                    </p> --}}
                </div>
            </a>
        </div>

        <!-- Center: Desktop Navigation Bar (Visible on lg: and up) -->
        <nav class="hidden lg:flex items-center gap-8 text-sm">
            <a href="{{ route('app.dashboard') }}"
                class="flex items-center gap-1.5 transition-colors py-1 border-b-2 {{ $currentRoute === 'app.dashboard' ? 'text-emerald-600 dark:text-emerald-400 font-bold border-emerald-600 dark:border-emerald-400' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium border-transparent' }}">
                <span>🏠</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('app.products.index') }}"
                class="flex items-center gap-1.5 transition-colors py-1 border-b-2 {{ str_starts_with($currentRoute, 'app.products') ? 'text-emerald-600 dark:text-emerald-400 font-bold border-emerald-600 dark:border-emerald-400' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium border-transparent' }}">
                <span>📦</span>
                <span>Produk</span>
            </a>
            <a href="{{ route('app.stats.index') }}"
                class="flex items-center gap-1.5 transition-colors py-1 border-b-2 {{ $currentRoute === 'app.stats.index' ? 'text-emerald-600 dark:text-emerald-400 font-bold border-emerald-600 dark:border-emerald-400' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium border-transparent' }}">
                <span>📊</span>
                <span>Statistik</span>
            </a>
            <a href="{{ route('app.account.index') }}"
                class="flex items-center gap-1.5 transition-colors py-1 border-b-2 {{ in_array($currentRoute, ['app.account.index', 'app.store.edit']) ? 'text-emerald-600 dark:text-emerald-400 font-bold border-emerald-600 dark:border-emerald-400' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium border-transparent' }}">
                <span>👤</span>
                <span>Akun</span>
            </a>
        </nav>

        <!-- Right: Quick actions -->
        <div class="flex items-center gap-2">
            @if ($store && $store->slug)
                <a href="{{ route('catalog.show', $store->slug) }}" target="_blank" title="Lihat Katalog Public"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Lihat Katalog</span>
                </a>
            @endif
        </div>
    </div>
</header>
