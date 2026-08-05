@extends('app.layouts.main')

@section('title', 'Statistik Toko')

@section('content')
<div class="space-y-5 md:space-y-6">
    <!-- Header Title -->
    <div>
        <h1 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white">Statistik Toko</h1>
        <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400">Pantau pergerakan calon pembeli dan klik WhatsApp.</p>
    </div>

    <!-- 1. Period Selector Pills -->
    <div class="flex gap-2 p-1 bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl shadow-sm max-w-md">
        <a href="{{ route('app.stats.index', ['period' => 'today']) }}"
           class="flex-1 py-2 text-center text-xs font-bold rounded-xl transition-colors {{ $periodKey === 'today' ? 'bg-emerald-600 text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
            Hari Ini
        </a>
        <a href="{{ route('app.stats.index', ['period' => '7days']) }}"
           class="flex-1 py-2 text-center text-xs font-bold rounded-xl transition-colors {{ $periodKey === '7days' ? 'bg-emerald-600 text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
            7 Hari
        </a>
        <a href="{{ route('app.stats.index', ['period' => '30days']) }}"
           class="flex-1 py-2 text-center text-xs font-bold rounded-xl transition-colors {{ $periodKey === '30days' ? 'bg-emerald-600 text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
            30 Hari
        </a>
    </div>

    <!-- 2. Stat Cards Grid -->
    <div class="grid grid-cols-3 gap-2.5 md:gap-4">
        <!-- Pengunjung -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-3 md:p-5 shadow-sm text-center">
            {{-- <div class="text-xl md:text-2xl mb-1">👁️</div> --}}
            <div class="text-xl md:text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($visitorsCount) }}</div>
            <div class="text-[10px] md:text-xs font-semibold text-zinc-500 dark:text-zinc-400 mt-0.5">Pengunjung</div>
        </div>

        <!-- Klik WA -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-3 md:p-5 shadow-sm text-center">
            {{-- <div class="text-xl md:text-2xl mb-1">💬</div> --}}
            <div class="text-xl md:text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($waClicksCount) }}</div>
            <div class="text-[10px] md:text-xs font-semibold text-zinc-500 dark:text-zinc-400 mt-0.5">Klik WA</div>
        </div>

        <!-- Views Produk -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-3 md:p-5 shadow-sm text-center">
            {{-- <div class="text-xl md:text-2xl mb-1">🛍️</div> --}}
            <div class="text-xl md:text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($productViewsCount) }}</div>
            <div class="text-[10px] md:text-xs font-semibold text-zinc-500 dark:text-zinc-400 mt-0.5">Views Produk</div>
        </div>
    </div>

    <!-- 3. Mini SVG Chart (Pengunjung & Klik WA - Taller on Desktop) -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Grafik Kunjungan & WA</h3>
            <div class="flex items-center gap-3 text-[10px] md:text-xs font-bold">
                <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pengunjung
                </span>
                <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Klik WA
                </span>
            </div>
        </div>

        @php
            $maxVal = max(max($chartVisitors), max($chartWaClicks), 1);
            $height = 80;
            $width = 280;
            $countTotal = count($chartDates);
            $stepX = $countTotal > 1 ? $width / ($countTotal - 1) : $width;

            $ptsVisitors = [];
            $ptsWa = [];
            foreach ($chartDates as $idx => $lbl) {
                $x = $idx * $stepX;
                $yV = $height - (($chartVisitors[$idx] / $maxVal) * ($height - 10));
                $yW = $height - (($chartWaClicks[$idx] / $maxVal) * ($height - 10));
                $ptsVisitors[] = "$x,$yV";
                $ptsWa[] = "$x,$yW";
            }
            $ptsVisitorsStr = implode(' ', $ptsVisitors);
            $ptsWaStr = implode(' ', $ptsWa);
        @endphp

        <div class="w-full h-28 md:h-44 relative my-3">
            <!-- Background Grid Lines -->
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-20 dark:opacity-10">
                <div class="border-b border-dashed border-zinc-400 dark:border-zinc-500 w-full"></div>
                <div class="border-b border-dashed border-zinc-400 dark:border-zinc-500 w-full"></div>
                <div class="border-b border-dashed border-zinc-400 dark:border-zinc-500 w-full"></div>
            </div>

            <!-- Gradient & Line SVG -->
            <svg class="w-full h-full overflow-visible" viewBox="0 0 280 80" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="statsChartGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#10b981" stop-opacity="0.25" />
                        <stop offset="100%" stop-color="#10b981" stop-opacity="0.0" />
                    </linearGradient>
                </defs>
                <polygon fill="url(#statsChartGrad)" points="0,80 {{ $ptsVisitorsStr }} 280,80" />
                
                <!-- Visitors Polyline -->
                <polyline fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="{{ $ptsVisitorsStr }}" />

                <!-- WA Clicks Polyline -->
                <polyline fill="none" stroke="#2563eb" stroke-width="2" stroke-dasharray="4,4" stroke-linecap="round" stroke-linejoin="round" points="{{ $ptsWaStr }}" />
            </svg>

            <!-- Visitor Data Point Dots (CSS Circular) -->
            @foreach($chartDates as $idx => $lbl)
                @php
                    $vCnt = $chartVisitors[$idx];
                    $leftPct = $countTotal > 1 ? ($idx / ($countTotal - 1)) * 100 : 50;
                    $topPct = 100 - (($vCnt / $maxVal) * 87.5 + 6.25);
                @endphp
                <div class="absolute group transform -translate-x-1/2 -translate-y-1/2 cursor-pointer z-10"
                    style="left: {{ $leftPct }}%; top: {{ $topPct }}%;">
                    <div class="w-3.5 h-3.5 md:w-4 md:h-4 rounded-full bg-emerald-500 border-2 border-white dark:border-zinc-900 shadow-md group-hover:scale-125 transition-transform"></div>
                    
                    <!-- Tooltip -->
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover:flex flex-col items-center pointer-events-none z-20">
                        <div class="bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-[10px] md:text-xs font-bold py-1 px-2 rounded-lg shadow-lg whitespace-nowrap">
                            {{ $vCnt }} pengunjung
                        </div>
                        <div class="w-1.5 h-1.5 bg-zinc-900 dark:bg-zinc-100 rotate-45 -mt-1"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Dates labels -->
        <div class="flex justify-between items-center text-[9px] md:text-xs text-zinc-400 dark:text-zinc-500 overflow-hidden font-medium mt-2">
            @foreach($chartDates as $lbl)
                <span>{{ $lbl }}</span>
            @endforeach
        </div>
    </div>

    <!-- 4. Top Products Ranking List (2 Columns on Desktop) -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-3">
        <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">5 Produk Paling Banyak Dilihat</h3>

        @if($topProducts->count() > 0)
            <div class="space-y-2.5 md:space-y-0 md:grid md:grid-cols-2 md:gap-3">
                @foreach($topProducts as $index => $prod)
                    <div class="flex items-center justify-between p-2.5 md:p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200/60 dark:border-zinc-700/60">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Rank Badge -->
                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0
                                {{ $index == 0 ? 'bg-amber-400 text-amber-950' : ($index == 1 ? 'bg-zinc-300 text-zinc-800' : ($index == 2 ? 'bg-amber-700 text-amber-100' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden bg-zinc-200 dark:bg-zinc-700 flex-shrink-0">
                                @if($prod->image)
                                    <img src="{{ \App\Services\FileUploadService::getFileUrl($prod->image) }}" class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[10px] text-zinc-400">No img</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs md:text-sm font-bold text-zinc-900 dark:text-white truncate">{{ $prod->name }}</h4>
                                <p class="text-[11px] md:text-xs font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($prod->price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="text-xs md:text-sm font-bold text-zinc-900 dark:text-white">{{ number_format($prod->views_count ?? 0) }}</span>
                            <span class="block text-[9px] md:text-[10px] text-zinc-400">views</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-zinc-500 text-center py-4">Belum ada data produk.</p>
        @endif
    </div>
</div>
@endsection
