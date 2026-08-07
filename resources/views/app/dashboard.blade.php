@extends('app.layouts.main')

@section('title', 'Dashboard Vendor')

@section('content')
    <div class="space-y-5 md:space-y-6" x-data="dashboardShare()">
        <!-- 1. Greeting Card -->
        <div
            class="bg-gradient-to-r from-emerald-600 to-teal-700 dark:from-emerald-700 dark:to-teal-900 rounded-2xl p-4 md:p-6 text-white shadow-lg shadow-emerald-600/10 md:flex md:items-center md:justify-between">
            <div>
                <p class="text-emerald-100 text-xs font-medium uppercase tracking-wider">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
                <h2 class="text-xl md:text-2xl font-bold mt-0.5">
                    @php
                        $hour = date('H');
                        $greeting = 'Selamat Pagi';
                        if ($hour >= 11 && $hour < 15) {
                            $greeting = 'Selamat Siang';
                        } elseif ($hour >= 15 && $hour < 19) {
                            $greeting = 'Selamat Sore';
                        } elseif ($hour >= 19 || $hour < 4) {
                            $greeting = 'Selamat Malam';
                        }
                    @endphp
                    {{ $greeting }}, {{ strtok($user->name, ' ') }}! 👋
                </h2>
                <p class="text-emerald-100/90 text-xs md:text-sm mt-1">
                    Kelola katalog toko WhatsApp kamu dengan cepat dan responsif dari semua perangkat.
                </p>
            </div>
            {{-- <div class="w-12 h-12 md:w-16 md:h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center flex-shrink-0 text-2xl md:text-3xl mt-3 md:mt-0">
            🏪
        </div> --}}
        </div>

        <!-- 2. Quick Stats Row (Grid on Desktop) -->
        <div>
            <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2 px-1">Ringkasan Hari
                Ini</h3>
            <div class="grid grid-cols-3 gap-3 md:gap-4">
                <!-- Stat 1: Produk -->
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-3.5 md:p-5 shadow-sm">
                    <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-2">
                        <span class="text-xs md:text-sm font-medium">Produk</span>
                        {{-- <span
                            class="p-1 md:p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 text-sm md:text-base">📦</span> --}}
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalProducts }}</div>
                    <div class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Total Aktif</div>
                </div>

                <!-- Stat 2: Pengunjung Hari Ini -->
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-3.5 md:p-5 shadow-sm">
                    <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-2">
                        <span class="text-xs md:text-sm font-medium">Pengunjung</span>
                        {{-- <span
                            class="p-1 md:p-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-sm md:text-base">👁️</span> --}}
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white">{{ $todayVisitors }}</div>
                    <div class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Hari Ini</div>
                </div>

                <!-- Stat 3: Klik WA Hari Ini -->
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-3.5 md:p-5 shadow-sm">
                    <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-2">
                        <span class="text-xs md:text-sm font-medium">Klik WA</span>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-zinc-900 dark:text-white">{{ $todayWaClicks }}</div>
                    <div class="text-[11px] md:text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Hari Ini</div>
                </div>
            </div>
        </div>

        <!-- 3. Quick Actions Grid (4 Cols on Desktop) -->
        <div>
            <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2 px-1">Aksi Cepat
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <a href="{{ route('app.products.create') }}"
                    class="flex items-center gap-3 p-3.5 md:p-4 bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl shadow-sm hover:border-emerald-500 transition-colors active:scale-98">
                    <div
                        class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white leading-tight">Tambah Produk</h4>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Foto & Harga</p>
                    </div>
                </a>

                <a href="{{ route('app.store.edit') }}"
                    class="flex items-center gap-3 p-3.5 md:p-4 bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl shadow-sm hover:border-emerald-500 transition-colors active:scale-98">
                    <div
                        class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white leading-tight">Edit Toko</h4>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Logo & WA</p>
                    </div>
                </a>

                <a href="{{ route('catalog.show', $store->slug) }}" target="_blank"
                    class="flex items-center gap-3 p-3.5 md:p-4 bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl shadow-sm hover:border-emerald-500 transition-colors active:scale-98">
                    <div
                        class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8M11.5 3a17 17 0 000 18M12.5 3a17 17 0 010 18" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white leading-tight">Lihat Toko</h4>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Pratinjau Live</p>
                    </div>
                </a>

                <a href="{{ route('app.stats.index') }}"
                    class="flex items-center gap-3 p-3.5 md:p-4 bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl shadow-sm hover:border-emerald-500 transition-colors active:scale-98">
                    <div
                        class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white leading-tight">Statistik</h4>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Pengunjung & WA</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- 4. Mini Grafik Visitor Trend (Inline SVG Sparkline - Taller on Desktop) -->
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Trend Pengunjung (7 Hari)</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Total: {{ array_sum($trendCounts) }} kunjungan</p>
                </div>
                <a href="{{ route('app.stats.index') }}"
                    class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                    <span>Detail</span>
                    <span>→</span>
                </a>
            </div>

            @php
                $maxCount = max(max($trendCounts), 1);
                $points = [];
                $yTrendArr = [];
                $width = 280;
                $height = 60;
                $countTotal = count($trendCounts);
                $stepX = $countTotal > 1 ? $width / ($countTotal - 1) : $width;

                foreach ($trendCounts as $idx => $cnt) {
                    $x = $idx * $stepX;
                    $y = $height - ($cnt / $maxCount) * ($height - 10);
                    $points[] = "$x,$y";
                    $yTrendArr[$idx] = $y;
                }
                $pointsString = implode(' ', $points);
            @endphp

            <div class="w-full h-24 md:h-36 relative my-3">
                <!-- Background Grid Lines -->
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-20 dark:opacity-10">
                    <div class="border-b border-dashed border-zinc-400 dark:border-zinc-500 w-full"></div>
                    <div class="border-b border-dashed border-zinc-400 dark:border-zinc-500 w-full"></div>
                    <div class="border-b border-dashed border-zinc-400 dark:border-zinc-500 w-full"></div>
                </div>

                <!-- Gradient & Line SVG -->
                <svg class="w-full h-full overflow-visible" viewBox="0 0 280 60" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="dashChartGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10b981" stop-opacity="0.3" />
                            <stop offset="100%" stop-color="#10b981" stop-opacity="0.0" />
                        </linearGradient>
                    </defs>
                    <polygon fill="url(#dashChartGrad)" points="0,60 {{ $pointsString }} 280,60" />
                    <polyline fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" points="{{ $pointsString }}" />
                </svg>

                <!-- Data Point Dots (CSS Circular) -->
                @foreach ($trendCounts as $idx => $cnt)
                    @php
                        $leftPct = $countTotal > 1 ? ($idx / ($countTotal - 1)) * 100 : 50;
                        $topPct = ($yTrendArr[$idx] / $height) * 100;
                    @endphp
                    <div class="absolute group transform -translate-x-1/2 -translate-y-1/2 cursor-pointer z-10"
                        style="left: {{ $leftPct }}%; top: {{ $topPct }}%;">
                        <div class="w-3 h-3 md:w-3.5 md:h-3.5 rounded-full bg-emerald-500 border-2 border-white dark:border-zinc-900 shadow-md group-hover:scale-125 transition-transform"></div>
                        
                        <!-- Tooltip -->
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover:flex flex-col items-center pointer-events-none z-20">
                            <div class="bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-[10px] md:text-xs font-bold py-1 px-2 rounded-lg shadow-lg whitespace-nowrap">
                                {{ $cnt }} kunjungan
                            </div>
                            <div class="w-1.5 h-1.5 bg-zinc-900 dark:bg-zinc-100 rotate-45 -mt-1"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div
                class="flex justify-between items-center text-[10px] md:text-xs text-zinc-400 dark:text-zinc-500 mt-3 px-1 font-medium">
                @foreach ($trendDates as $d)
                    <span>{{ $d }}</span>
                @endforeach
            </div>
        </div>

        <!-- 5. Produk Terbanyak Dilihat (Grid on Desktop) -->
        <div>
            <div class="flex items-center justify-between mb-2 px-1">
                <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Produk Sering
                    Dilihat</h3>
                <a href="{{ route('app.products.index') }}"
                    class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Semua Produk →
                </a>
            </div>

            @if ($topProducts->count() > 0)
                <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-3">
                    @foreach ($topProducts as $prod)
                        <a href="{{ route('app.products.edit', $prod->id) }}"
                            class="flex items-center justify-between p-3 bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl shadow-sm hover:border-emerald-500 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div
                                    class="w-11 h-11 md:w-12 md:h-12 rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 flex-shrink-0 border border-zinc-200 dark:border-zinc-700">
                                    @if ($prod->image)
                                        <img src="{{ \App\Services\FileUploadService::getFileUrl($prod->image) }}"
                                            alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-zinc-400 text-xs">Tanpa foto</div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-white truncate">
                                        {{ $prod->name }}</h4>
                                    <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">Rp
                                        {{ number_format($prod->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400 flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 px-2.5 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>{{ $prod->views_count ?? 0 }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div
                    class="p-6 md:p-10 text-center bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl">
                    <div class="text-3xl md:text-4xl mb-2">📦</div>
                    <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400">Belum ada produk yang ditambahkan.</p>
                    <a href="{{ route('app.products.create') }}"
                        class="inline-block mt-3 px-4 py-2 bg-emerald-600 text-white font-bold text-xs md:text-sm rounded-xl shadow-md">
                        + Tambah Produk Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function dashboardShare() {
            return {
                shareCatalog(url, storeName) {
                    if (navigator.share) {
                        navigator.share({
                            title: storeName,
                            text: 'Kunjungi katalog produk digital ' + storeName + ' di WhatsApp!',
                            url: url
                        }).catch(() => {});
                    } else {
                        navigator.clipboard.writeText(url).then(() => {
                            showAppToast('Link katalog berhasil disalin!', 'success');
                        }).catch(() => {
                            showAppToast('Gagal menyalin link.', 'error');
                        });
                    }
                }
            }
        }
    </script>
@endsection
