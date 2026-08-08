<!DOCTYPE html>
<html lang="id" class="{{ $store->dark_mode ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- SEO Title & Meta Overrides --}}
    <title>{{ $store->seo_title ? e($store->seo_title) : $store->name . ' - ' . config('app.name', 'Katalogin') }}
    </title>
    <meta name="description"
        content="{{ $store->seo_description ? e($store->seo_description) : strip_tags($store->welcome_message) }}">
    <meta property="og:title"
        content="{{ $store->seo_title ? e($store->seo_title) : $store->name . ' - ' . config('app.name', 'Katalogin') }}">
    <meta property="og:description"
        content="{{ $store->seo_description ? e($store->seo_description) : strip_tags($store->welcome_message) }}">
    @if ($store->logo)
        <meta property="og:image"
            content="{{ str_starts_with($store->logo, 'http') ? $store->logo : Storage::url($store->logo) }}">
    @endif
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">

    {{-- Google Search Console Verification --}}
    @if ($store->google_search_console_code)
        <meta name="google-site-verification" content="{{ e($store->google_search_console_code) }}">
    @endif

    {{-- Google Analytics 4 (gtag.js) --}}
    @if ($store->google_analytics_id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ e($store->google_analytics_id) }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ e($store->google_analytics_id) }}');
        </script>
    @endif

    {{-- Meta (Facebook) Pixel --}}
    @if ($store->meta_pixel_id)
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ e($store->meta_pixel_id) }}');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ e($store->meta_pixel_id) }}&ev=PageView&noscript=1" /></noscript>
    @endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamic CSS based on Store Settings -->
    @php
        $themeColor = $store->theme_color ?? '#10b981';
        if (
            $themeColor === 'dark' ||
            $themeColor === 'light' ||
            !preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$|^[a-zA-Z]+$/', $themeColor)
        ) {
            $themeColor = '#10b981';
        }

        $cleanWa = preg_replace('/\D/', '', $store->wa_number);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62' . substr($cleanWa, 1);
        }

        $logoUrl = $store->logo
            ? (str_starts_with($store->logo, 'http')
                ? $store->logo
                : Storage::url($store->logo))
            : null;
        $bannerUrl = $store->banner
            ? (str_starts_with($store->banner, 'http')
                ? $store->banner
                : Storage::url($store->banner))
            : null;
    @endphp
    <style>
        :root {
            --primary-color: {{ $themeColor }};
        }

        body {
            background-color: {{ $store->dark_mode ? '#111827' : '#f8fafc' }};
            color: {{ $store->dark_mode ? '#f9fafb' : '#1e293b' }};
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .btn-custom {
            border-radius: {{ $store->button_rounded ? '9999px' : '0.75rem' }} !important;
        }

        .card-custom {
            border-radius: {{ $store->button_rounded ? '1.25rem' : '0.875rem' }} !important;
            background-color: {{ $store->dark_mode ? '#1f2937' : '#ffffff' }};
            border-color: {{ $store->dark_mode ? '#374151' : '#f1f5f9' }};
        }

        /* Hide scrollbars for chrome, safari and opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbars for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>
</head>

<body
    class="antialiased min-h-screen flex flex-col justify-between relative selection:bg-emerald-500 selection:text-white"
    x-data="catalogApp()" :class="{ 'overflow-hidden': isProductModalOpen || isCheckoutModalOpen || isSideMenuOpen }">

    <!-- Toast Notification -->
    <div x-show="toast.show" x-cloak
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-full opacity-0"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-[120] bg-zinc-900/90 dark:bg-white/90 text-white dark:text-zinc-900 px-4 py-2.5 rounded-2xl shadow-xl text-xs font-bold backdrop-blur-md flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-400 dark:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span x-text="toast.message"></span>
    </div>

    <!-- 1. STICKY TOP NAVIGATION HEADER -->
    <header
        class="sticky top-0 z-40 w-full backdrop-blur-xl bg-white/90 dark:bg-gray-900/90 border-b border-gray-100 dark:border-gray-800 transition-colors shadow-sm">
        <div class="max-w-5xl mx-auto px-4 pt-2 sm:pt-0 h-16 sm:h-16 flex items-center justify-between gap-3">
            <!-- Left: Burger Menu Button -->
            <button @click="isSideMenuOpen = true" aria-label="Open Side Menu"
                class="p-2 rounded-full text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>

            <!-- Center: Store Brand Name or Logo -->
            <div class="flex items-center gap-2 truncate">
                @if ($logoUrl)
                    <div
                        class="w-8 h-8 rounded-full overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm flex-shrink-0 bg-white">
                        <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                @endif
                <span
                    class="font-bold text-base sm:text-lg truncate max-w-[180px] sm:max-w-xs text-gray-900 dark:text-white">
                    {{ $store->name }}
                </span>
            </div>

            <!-- Right: Search Toggle & Shopping Cart Icons -->
            <div class="flex items-center gap-1 sm:gap-2">
                <button @click="isSearchOpen = !isSearchOpen" aria-label="Toggle Search"
                    class="p-2 rounded-full text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <button @click="isCheckoutModalOpen = true" aria-label="Shopping Cart"
                    class="p-2 rounded-full text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span x-show="totalItems > 0" x-text="totalItems" x-cloak
                        class="absolute top-1 right-1 w-4 h-4 sm:w-5 sm:h-5 bg-primary text-white text-[10px] sm:text-xs font-black rounded-full flex items-center justify-center shadow">
                    </span>
                </button>
            </div>
        </div>

        <!-- Expandable Search Bar Input -->
        <div x-show="isSearchOpen" x-transition x-cloak class="max-w-5xl mx-auto px-4 pb-3">
            <div class="relative">
                <input type="text" x-model="searchQuery" placeholder="Cari produk di {{ $store->name }}..."
                    class="w-full h-10 pl-10 pr-10 rounded-full bg-gray-100 dark:bg-gray-800 border-0 text-sm focus:ring-2 focus:ring-emerald-500 font-medium text-gray-900 dark:text-white" />
                <svg class="w-4 h-4 absolute left-3.5 top-3 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <button x-show="searchQuery" @click="searchQuery = ''"
                    class="absolute right-3.5 top-2.5 text-gray-400 hover:text-gray-600">
                    ✕
                </button>
            </div>
        </div>
    </header>

    <!-- 2. HAMBURGER SIDE DRAWER MENU -->
    <div x-show="isSideMenuOpen" class="fixed inset-0 z-[200]" style="display: none;">
        <!-- Backdrop -->
        <div @click="isSideMenuOpen = false" x-show="isSideMenuOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <!-- Drawer -->
        <div x-show="isSideMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="absolute inset-y-0 left-0 w-80 max-w-[85vw] bg-white dark:bg-gray-900 shadow-2xl flex flex-col justify-between">

            <div>
                <!-- Drawer Header -->
                <div class="p-5 border-b dark:border-gray-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-11 h-11 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-800 border dark:border-gray-700 flex items-center justify-center font-bold text-primary text-xl flex-shrink-0">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($store->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900 dark:text-white truncate">{{ $store->name }}
                            </h3>
                            {{-- <p class="text-xs text-gray-500 dark:text-gray-400">Katalog Digital Resmi</p> --}}
                        </div>
                    </div>
                    <button @click="isSideMenuOpen = false"
                        class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5">
                    <button @click="isSideMenuOpen = false; window.scrollTo({top:0, behavior:'smooth'})"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-left font-semibold text-sm text-gray-800 dark:text-gray-200">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Beranda Toko</span>
                    </button>

                    <!-- Kategori (Expandable) -->
                    <div x-data="{ catOpen: true }">
                        <button @click="catOpen = !catOpen"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-left font-semibold text-sm text-gray-800 dark:text-gray-200">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <span>Kategori Produk</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="catOpen ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="catOpen" x-collapse class="pl-11 space-y-1 mt-1">
                            <button @click="selectedCategory = 'all'; isSideMenuOpen = false"
                                :class="selectedCategory === 'all' ?
                                    'text-primary font-bold bg-emerald-50 dark:bg-emerald-900/30' :
                                    'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                                class="w-full text-left py-2 px-3 rounded-lg text-xs transition-colors">
                                Semua Produk
                            </button>
                            @foreach ($categories as $cat)
                                <button @click="selectedCategory = {{ $cat->id }}; isSideMenuOpen = false"
                                    :class="selectedCategory == {{ $cat->id }} ?
                                        'text-primary font-bold bg-emerald-50 dark:bg-emerald-900/30' :
                                        'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                                    class="w-full text-left py-2 px-3 rounded-lg text-xs transition-colors">
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t dark:border-gray-800 my-2"></div>

                    <!-- Chat WA -->
                    <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Halo Admin ' . $store->name . ', saya ingin bertanya...') }}"
                        target="_blank"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors font-semibold text-sm text-gray-800 dark:text-gray-200">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                        </svg>
                        <span>Chat WhatsApp</span>
                    </a>

                    <!-- Share Store Link -->
                    <button @click="shareStore()"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-left font-semibold text-sm text-gray-800 dark:text-gray-200">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 100-5.999 3 3 0 000 5.999zm0 11.998a3 3 0 100-5.999 3 3 0 000 5.999z" />
                        </svg>
                        <span>Bagikan Toko</span>
                    </button>
                </nav>
            </div>

            <!-- Footer Branding in Drawer -->
            <div class="p-4 border-t dark:border-gray-800 text-center">
                <a href="{{ url('/') }}" target="_blank"
                    class="text-[11px] text-gray-400 hover:text-primary transition-colors">
                    Powered by {{ config('app.name', 'Katalogin') }}
                </a>
            </div>
        </div>
    </div>

    <!-- 3. STORE BRANDING HERO SECTION (CENTERED LAYOUT MATCHING DESIGN) -->
    <section class="w-full max-w-5xl mx-auto px-4 pt-3 sm:pt-4 text-center">
        <!-- Banner (if present) -->
        @if ($bannerUrl)
            <div
                class="w-full h-32 sm:h-48 md:h-56 rounded-2xl overflow-hidden shadow-sm bg-gradient-to-r from-emerald-600 to-teal-700 relative">
                <img src="{{ $bannerUrl }}" alt="Store Banner" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Centered Store Logo Badge (Overlapping Banner) -->
        <div class="relative inline-block {{ $bannerUrl ? '-mt-10 sm:-mt-12' : 'mt-2' }} z-10 mb-2">
            <div
                class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white dark:border-gray-900 shadow-md overflow-hidden bg-white dark:bg-gray-800 flex items-center justify-center font-bold text-2xl text-primary mx-auto">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($store->name, 0, 1)) }}
                @endif
            </div>
        </div>

        <!-- Store Name & Tagline -->
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
            {{ $store->name }}
        </h1>
        @if ($store->welcome_message)
            <p
                class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1 max-w-md mx-auto line-clamp-2 leading-relaxed">
                {{ htmlspecialchars_decode($store->welcome_message, ENT_QUOTES) }}
            </p>
        @endif
    </section>

    <!-- MAIN CONTENT AREA -->
    <main class="max-w-5xl w-full mx-auto px-3 sm:px-4 py-3 flex-1 flex flex-col"
        :class="totalItems > 0 ? 'pb-24' : 'pb-8'">

        <!-- 4. CATEGORY FILTER PILLS (HORIZONTAL SCROLLABLE) -->
        <div class="flex overflow-x-auto gap-2 py-3 no-scrollbar mb-2" x-show="allProducts.length > 0">
            <button @click="selectedCategory = 'all'"
                :class="selectedCategory === 'all' ? 'bg-primary text-white shadow-md' :
                    'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                class="px-4 py-2 rounded-full whitespace-nowrap text-xs sm:text-sm font-bold transition-all btn-custom">
                Semua ({{ $totalProductsCount ?? count($formattedProducts) }})
            </button>
            @foreach ($categories as $category)
                <button @click="selectedCategory = {{ $category->id }}"
                    :class="selectedCategory == {{ $category->id }} ? 'bg-primary text-white shadow-md' :
                        'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                    class="px-4 py-2 rounded-full whitespace-nowrap text-xs sm:text-sm font-bold transition-all btn-custom">
                    {{ $category->name }} ({{ $category->products_count }})
                </button>
            @endforeach
            @if (isset($uncategorizedCount) && $uncategorizedCount > 0)
                <button @click="selectedCategory = 'uncategorized'"
                    :class="selectedCategory === 'uncategorized' ? 'bg-primary text-white shadow-md' :
                        'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                    class="px-4 py-2 rounded-full whitespace-nowrap text-xs sm:text-sm font-bold transition-all btn-custom">
                    Tanpa Kategori ({{ $uncategorizedCount }})
                </button>
            @endif
        </div>

        <!-- Empty State -->
        <div x-show="filteredProducts.length === 0" style="display: none;"
            class="flex-1 flex flex-col items-center justify-center py-16 text-center px-4 my-auto">
            <div
                class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4 text-gray-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 class="text-base font-bold mb-1">Produk Tidak Ditemukan</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 max-w-xs">Tidak ada produk yang cocok dengan pencarian
                atau kategori yang dipilih.</p>
        </div>

        <!-- 5. 2-COLUMN PRODUCT GRID (MATCHING DESIGN) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-2" x-show="filteredProducts.length > 0">
            <template x-for="product in filteredProducts" :key="product.id">
                <div
                    class="card-custom border rounded-2xl overflow-hidden flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow h-full">
                    <div>
                        <!-- Product Image (1:1 Aspect Ratio) -->
                        <div class="relative w-full pt-[100%] bg-gray-100 dark:bg-gray-800 cursor-pointer overflow-hidden rounded-t-2xl"
                            @click="openProductDetail(product.id, product.name, product.price, product.description, product.images)">
                            <template x-if="product.views_count > 100">
                                <span
                                    class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full z-10 shadow flex items-center gap-1">
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M17.557 12c0 3.071-2.488 5.558-5.557 5.558-3.07 0-5.557-2.487-5.557-5.558 0-2.316 1.428-4.298 3.447-5.132-.234 1.107.414 2.164 1.472 2.454.912.25 1.87-.27 2.195-1.144.577.893 1.348 1.957 2.056 2.417.828.537 1.944.385 2.605-.333.867 1.05 1.339 2.278 1.339 3.738z" />
                                    </svg>
                                    <span>TERLARIS</span>
                                </span>
                            </template>
                            <template x-if="product.images && product.images.length > 0">
                                <img :src="product.images[0]" :alt="product.name"
                                    class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                    loading="lazy">
                            </template>
                            <template x-if="!product.images || product.images.length === 0">
                                <div
                                    class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs font-semibold">
                                    Belum Ada Foto</div>
                            </template>
                        </div>

                        <!-- Product Info (No Star Ratings per directive) -->
                        <div class="p-3">
                            <h3 class="font-bold text-xs sm:text-sm leading-snug line-clamp-2 cursor-pointer mb-1 text-gray-900 dark:text-white"
                                @click="openProductDetail(product.id, product.name, product.price, product.description, product.images)"
                                x-text="product.name"></h3>
                            <p class="font-black text-sm sm:text-base text-gray-900 dark:text-white mt-1">
                                Rp <span x-text="formatRupiah(product.price)"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Product Action Button (Pill CTA) -->
                    <div class="p-3 pt-0 mt-auto">
                        <div x-show="!getCartItem(product.id)">
                            <button @click.stop="addToCart(product.id, product.name, product.price)"
                                class="bg-primary text-white py-2 sm:py-2.5 w-full btn-custom text-xs font-bold shadow flex items-center justify-center gap-1.5 active:scale-95 transition-transform">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span>{{ $store->cta_button_text ? e($store->cta_button_text) : 'Tambah' }}</span>
                            </button>
                        </div>

                        <div x-show="getCartItem(product.id)"
                            class="flex items-center justify-between w-full bg-gray-100 dark:bg-gray-800 p-1 border card-custom shadow-inner">
                            <button @click.stop="updateQuantity(product.id, -1)"
                                class="w-7 h-7 flex items-center justify-center font-bold text-primary bg-white dark:bg-gray-700 btn-custom shadow-sm text-sm">-</button>
                            <span class="text-xs font-extrabold text-gray-900 dark:text-white"
                                x-text="getCartItem(product.id)?.qty"></span>
                            <button @click.stop="updateQuantity(product.id, 1)"
                                class="w-7 h-7 flex items-center justify-center font-bold text-primary bg-white dark:bg-gray-700 btn-custom shadow-sm text-sm">+</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Intersection Observer Target for Infinite Scroll -->
        <div x-intersect="loadMore()" class="py-6 text-center" x-show="nextPageUrl">
            <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div>
        </div>

    </main>

    <!-- 6. CART FLOATING BAR -->
    <div x-show="totalItems > 0" x-transition x-cloak class="fixed bottom-0 inset-x-0 z-50 p-3 sm:p-4">
        <div class="max-w-3xl mx-auto">
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 p-3.5 sm:p-4 flex justify-between items-center backdrop-blur-xl">
                <div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">Total (<span
                            x-text="totalItems"></span> item)</p>
                    <p class="font-black text-base sm:text-lg text-primary">Rp <span
                            x-text="formatRupiah(totalPrice)"></span></p>
                </div>
                <button @click="isCheckoutModalOpen = true"
                    class="bg-primary text-white px-5 sm:px-6 py-2.5 sm:py-3 btn-custom font-bold text-xs sm:text-sm flex items-center gap-2 shadow-lg active:scale-95 transition-transform">
                    Checkout WA
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path
                            d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- 7. CHECKOUT MODAL -->
    <div x-show="isCheckoutModalOpen" x-cloak
        class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center bg-black/60 backdrop-blur-xs"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

        <div x-show="isCheckoutModalOpen" @click.outside="isCheckoutModalOpen = false"
            class="bg-white dark:bg-gray-900 w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl p-6 transform transition-all card-custom border dark:border-gray-800 shadow-2xl"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white">Detail Pesanan WhatsApp</h2>
                <button @click="isCheckoutModalOpen = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold mb-1 text-gray-700 dark:text-gray-300">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" x-model="customer.name"
                        class="w-full border dark:border-gray-700 rounded-xl p-3 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 font-medium"
                        placeholder="Masukkan Nama Anda">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1 text-gray-700 dark:text-gray-300">Catatan Pesanan <span
                            class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea x-model="customer.notes"
                        class="w-full border dark:border-gray-700 rounded-xl p-3 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500 font-normal"
                        rows="3" placeholder="Alamat pengiriman, varian khusus, dll..."></textarea>
                </div>

                <button @click="processWhatsApp('{{ $store->wa_number }}')"
                    class="w-full bg-primary text-white py-3.5 btn-custom font-bold text-base shadow-lg flex justify-center items-center gap-2 mt-4 active:scale-95 transition-transform">
                    Kirim Pesanan ke WA
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path
                            d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- 8. FLOATING GENERAL WA CHAT BUTTON -->
    <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Halo Admin ' . $store->name . ', saya ingin bertanya...') }}"
        target="_blank" :class="totalItems > 0 ? 'bottom-24' : 'bottom-6'"
        class="fixed right-5 bg-[#25D366] text-white p-3.5 rounded-full shadow-xl hover:bg-[#1EBE5D] transition-all z-40 flex items-center justify-center hover:-translate-y-1">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
            class="bi bi-whatsapp" viewBox="0 0 16 16">
            <path
                d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
        </svg>
    </a>

    <!-- 9. PRODUCT DETAIL MODAL -->
    <div x-show="isProductModalOpen" x-cloak
        class="fixed inset-0 z-[110] flex items-end sm:items-center justify-center bg-black/70 backdrop-blur-xs"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

        <div x-show="isProductModalOpen" @click.outside="closeProductModal()"
            class="bg-white dark:bg-gray-900 w-full sm:max-w-md h-[92dvh] sm:h-auto sm:max-h-[85vh] rounded-t-2xl sm:rounded-2xl flex flex-col overflow-hidden shadow-2xl relative transform transition-all"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">

            <div class="relative w-full pt-[80%] sm:pt-[75%] bg-gray-100 dark:bg-gray-800 flex-shrink-0">
                <button @click="shareProduct()" title="Bagikan Produk Ini"
                    class="absolute top-4 left-4 bg-black/40 hover:bg-black/60 text-white rounded-full w-8 h-8 flex items-center justify-center z-[15] backdrop-blur-md border border-white/20 transition-transform active:scale-90 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684" />
                    </svg>
                </button>
                <button @click="closeProductModal()" title="Tutup"
                    class="absolute top-4 right-4 bg-black/40 hover:bg-black/60 text-white rounded-full w-8 h-8 flex items-center justify-center z-[15] backdrop-blur-md border border-white/20 transition-transform active:scale-90 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <template x-if="activeProduct?.images?.length > 0">
                    <img :src="activeProduct.images[activeProduct.activeImageIndex]"
                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300">
                </template>
                <template x-if="!activeProduct?.images || activeProduct.images.length === 0">
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400 font-semibold text-sm">
                        Belum Ada Foto</div>
                </template>
            </div>

            <template x-if="activeProduct?.images?.length > 1">
                <div
                    class="flex gap-3 p-3 overflow-x-auto no-scrollbar bg-gray-50 dark:bg-gray-800/60 border-b dark:border-gray-800 flex-shrink-0">
                    <template x-for="(img, index) in activeProduct.images" :key="index">
                        <img :src="img" @click="activeProduct.activeImageIndex = index"
                            :class="activeProduct.activeImageIndex === index ? 'border-primary ring-2 ring-emerald-500' :
                                'border-transparent opacity-60 hover:opacity-100'"
                            class="w-16 h-16 object-cover rounded-xl cursor-pointer border-2 transition-all flex-shrink-0 shadow-sm">
                    </template>
                </div>
            </template>

            <div class="p-5 overflow-y-auto flex-1 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 text-gray-900 dark:text-white" x-text="activeProduct?.name">
                    </h2>
                    <p class="font-black text-lg text-primary mb-4">Rp <span
                            x-text="activeProduct ? formatRupiah(activeProduct.price) : '0'"></span></p>

                    <h4 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-1">Deskripsi Produk:</h4>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line mb-6"
                        x-text="activeProduct?.description"></p>
                </div>

                <div class="mt-auto pt-3 border-t dark:border-gray-800">
                    <div x-show="activeProduct && !getCartItem(activeProduct.id)">
                        <button @click="addToCart(activeProduct.id, activeProduct.name, activeProduct.price)"
                            class="w-full bg-primary text-white py-3 btn-custom font-bold text-sm shadow-md active:scale-95 transition-transform flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span>{{ $store->cta_button_text ? e($store->cta_button_text) : 'Tambah' }}</span>
                        </button>
                    </div>

                    <div x-show="activeProduct && getCartItem(activeProduct.id)"
                        class="flex items-center justify-between bg-gray-100 dark:bg-gray-800 p-1.5 border card-custom shadow-inner">
                        <button @click="updateQuantity(activeProduct.id, -1)"
                            class="w-9 h-9 flex items-center justify-center font-bold text-primary text-lg btn-custom bg-white dark:bg-gray-700 shadow-sm">-</button>
                        <span class="text-sm font-extrabold text-gray-900 dark:text-white"
                            x-text="activeProduct ? getCartItem(activeProduct.id)?.qty : 0"></span>
                        <button @click="updateQuantity(activeProduct.id, 1)"
                            class="w-9 h-9 flex items-center justify-center font-bold text-primary text-lg btn-custom bg-white dark:bg-gray-700 shadow-sm">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 10. ALPINE.JS LOGIC & DATA INJECTION -->
    @php
        $formattedProducts = $products->map(function ($p) {
            $images = [];
            $mainImage = $p->image ? (str_starts_with($p->image, 'http') ? $p->image : Storage::url($p->image)) : null;
            if ($mainImage) {
                $images[] = $mainImage;
            }
            foreach ($p->images as $img) {
                $images[] = str_starts_with($img->image_path, 'http')
                    ? $img->image_path
                    : Storage::url($img->image_path);
            }
            return [
                'id' => $p->id,
                'category_id' => $p->category_id,
                'slug' => $p->slug,
                'name' => $p->name,
                'price' => $p->price,
                'description' => $p->description,
                'views_count' => $p->views_count,
                'images' => $images,
            ];
        });
    @endphp

    <!-- Alpine plugins (Intersect for infinite scroll) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        const initialProducts = @json($formattedProducts);

        document.addEventListener('alpine:init', () => {
            Alpine.data('catalogApp', () => ({
                allProducts: initialProducts,
                nextPageUrl: '{{ $products->nextPageUrl() }}',
                isLoading: false,
                selectedCategory: 'all',
                isSideMenuOpen: false,
                isSearchOpen: false,
                searchQuery: '',
                localDark: {{ $store->dark_mode ? 'true' : 'false' }},
                cart: {},
                isCheckoutModalOpen: false,
                isProductModalOpen: false,
                activeProduct: null,
                customer: {
                    name: '',
                    notes: ''
                },

                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const productSlug = urlParams.get('p');
                    if (productSlug) {
                        const product = this.allProducts.find(p => p.slug === productSlug);
                        if (product) {
                            this.openProductDetail(product.id, product.name, product.price, product
                                .description, product.images, false);
                        }
                    }
                },

                get filteredProducts() {
                    let products = this.allProducts;
                    if (this.selectedCategory === 'uncategorized') {
                        products = products.filter(p => !p.category_id);
                    } else if (this.selectedCategory !== 'all') {
                        products = products.filter(p => p.category_id == this.selectedCategory);
                    }
                    if (this.searchQuery && this.searchQuery.trim()) {
                        const q = this.searchQuery.toLowerCase();
                        products = products.filter(p => p.name.toLowerCase().includes(q));
                    }
                    return products;
                },

                toast: {
                    show: false,
                    message: ''
                },

                showToast(msg) {
                    this.toast.message = msg;
                    this.toast.show = true;
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                },

                shareStore() {
                    const url = window.location.href;
                    const title = '{{ e($store->name) }}';
                    if (navigator.share) {
                        navigator.share({
                            title,
                            url
                        }).catch(() => {});
                    } else {
                        navigator.clipboard.writeText(url);
                        this.showToast('Link katalog berhasil disalin!');
                    }
                },

                quickShareProduct(product) {
                    const url = window.location.protocol + "//" + window.location.host + window.location.pathname + '?p=' + product.slug;
                    const title = product.name + ' - {{ e($store->name) }}';
                    const text = 'Lihat ' + product.name + ' seharga Rp ' + this.formatRupiah(product.price) + ' di katalog {{ e($store->name) }}!';

                    if (navigator.share) {
                        navigator.share({
                            title: title,
                            text: text,
                            url: url
                        }).catch(() => {});
                    } else {
                        navigator.clipboard.writeText(url).then(() => {
                            this.showToast('Link produk "' + product.name + '" berhasil disalin!');
                        }).catch(() => {
                            this.showToast('Gagal menyalin link produk.');
                        });
                    }
                },

                shareProduct() {
                    if (!this.activeProduct) return;
                    const p = this.activeProduct;
                    const url = window.location.protocol + "//" + window.location.host + window.location.pathname + '?p=' + p.slug;
                    const title = p.name + ' - {{ e($store->name) }}';
                    const text = 'Lihat ' + p.name + ' seharga Rp ' + this.formatRupiah(p.price) + ' di katalog {{ e($store->name) }}!';

                    if (navigator.share) {
                        navigator.share({
                            title: title,
                            text: text,
                            url: url
                        }).catch(() => {});
                    } else {
                        navigator.clipboard.writeText(url).then(() => {
                            this.showToast('Link produk "' + p.name + '" berhasil disalin!');
                        }).catch(() => {
                            this.showToast('Gagal menyalin link produk.');
                        });
                    }
                },

                toggleLocalDark() {
                    this.localDark = !this.localDark;
                    document.documentElement.classList.toggle('dark', this.localDark);
                },

                async loadMore() {
                    if (this.isLoading || !this.nextPageUrl) return;

                    this.isLoading = true;
                    try {
                        const response = await fetch(this.nextPageUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const res = await response.json();

                        this.allProducts = [...this.allProducts, ...res.data];
                        this.nextPageUrl = res.next_page_url;
                    } catch (error) {
                        console.error("Gagal load products:", error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                getCartItem(id) {
                    return this.cart[id] || null;
                },

                openProductDetail(id, name, price, description, images, track = true) {
                    const p = this.allProducts.find(prod => prod.id === id);
                    const slug = p ? p.slug : '';

                    this.activeProduct = {
                        id,
                        name,
                        price,
                        description,
                        images: images || [],
                        activeImageIndex: 0,
                        slug: slug
                    };
                    this.isProductModalOpen = true;

                    if (p) {
                        const newUrl = window.location.protocol + "//" + window.location.host + window
                            .location.pathname + '?p=' + p.slug;
                        window.history.pushState({
                            path: newUrl
                        }, '', newUrl);
                    }

                    if (track) {
                        fetch(`/catalog/{{ $store->slug }}/track-product-view/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            }
                        }).catch(e => console.error(e));
                    }
                },

                closeProductModal() {
                    this.isProductModalOpen = false;
                    const newUrl = window.location.protocol + "//" + window.location.host + window
                        .location.pathname;
                    window.history.pushState({
                        path: newUrl
                    }, '', newUrl);
                },

                addToCart(id, name, price) {
                    this.cart[id] = {
                        id,
                        name,
                        price,
                        qty: 1
                    };
                },

                updateQuantity(id, change) {
                    if (this.cart[id]) {
                        this.cart[id].qty += change;
                        if (this.cart[id].qty <= 0) {
                            delete this.cart[id];
                        }
                    }
                },

                get totalItems() {
                    return Object.values(this.cart).reduce((sum, item) => sum + item.qty, 0);
                },

                get totalPrice() {
                    return Object.values(this.cart).reduce((sum, item) => sum + (item.price * item
                        .qty), 0);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                processWhatsApp(waNumber) {
                    if (!this.customer.name || !this.customer.name.trim()) {
                        alert('Mohon isi nama lengkap Anda!');
                        return;
                    }

                    let text = `*Halo, saya ingin memesan dari {{ $store->name }}:*\n\n`;
                    text += `*Detail Pesanan:*\n`;

                    Object.values(this.cart).forEach(item => {
                        text +=
                            `- ${item.name} (${item.qty}x) = Rp ${this.formatRupiah(item.price * item.qty)}\n`;
                    });

                    text += `\n*Total Harga: Rp ${this.formatRupiah(this.totalPrice)}*\n\n`;

                    text += `*Data Pemesan:*\n`;
                    text += `Nama: ${this.customer.name.trim()}\n`;
                    if (this.customer.notes && this.customer.notes.trim()) {
                        text += `Catatan: ${this.customer.notes.trim()}\n`;
                    }
                    text += `\nMohon segera diproses ya, terima kasih!`;

                    let encodedText = encodeURIComponent(text);

                    let cleanNumber = waNumber.replace(/\D/g, '');
                    if (cleanNumber.startsWith('0')) {
                        cleanNumber = '62' + cleanNumber.substring(1);
                    }

                    fetch(`/catalog/{{ $store->slug }}/track-wa-click`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    }).catch(e => console.error(e));

                    window.open(`https://wa.me/${cleanNumber}?text=${encodedText}`, '_blank');
                }
            }))
        })
    </script>

    <!-- 11. FOOTER BRANDING -->
    <footer
        class="w-full mt-12 py-6 border-t text-center transition-colors {{ $store->dark_mode ? 'border-gray-800 text-gray-500' : 'border-gray-100 text-gray-400' }}">
        <p class="text-xs">
            Katalog resmi <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $store->name }}</span>
        </p>
        <a href="{{ url('/') }}" target="_blank"
            class="inline-flex items-center gap-1 mt-1.5 text-xs font-bold text-primary opacity-80 hover:opacity-100 transition-opacity">
            Powered by {{ config('app.name', 'Katalogin') }}
        </a>
    </footer>

</body>

</html>
