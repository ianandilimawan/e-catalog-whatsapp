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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('app.products.index') }}"
                class="flex items-center gap-1.5 transition-colors py-1 border-b-2 {{ str_starts_with($currentRoute, 'app.products') ? 'text-emerald-600 dark:text-emerald-400 font-bold border-emerald-600 dark:border-emerald-400' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium border-transparent' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Produk</span>
            </a>
            <a href="{{ route('app.stats.index') }}"
                class="flex items-center gap-1.5 transition-colors py-1 border-b-2 {{ $currentRoute === 'app.stats.index' ? 'text-emerald-600 dark:text-emerald-400 font-bold border-emerald-600 dark:border-emerald-400' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium border-transparent' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Statistik</span>
            </a>
            <a href="{{ route('app.account.index') }}"
                class="flex items-center gap-1.5 transition-colors py-1 border-b-2 {{ in_array($currentRoute, ['app.account.index', 'app.store.edit']) ? 'text-emerald-600 dark:text-emerald-400 font-bold border-emerald-600 dark:border-emerald-400' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white font-medium border-transparent' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
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
