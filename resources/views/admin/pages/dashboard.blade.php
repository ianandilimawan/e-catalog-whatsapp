@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 animate-fade-in-up">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Dashboard Overview</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Here's what's happening with your platform today.</p>
            </div>
            
            <!-- Filters -->
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col sm:flex-row items-end gap-2" id="filterForm">
                @if(auth()->user()->hasRole('admin'))
                <div class="flex items-center bg-white dark:bg-zinc-800 p-2 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <select name="store_id" class="text-sm border-0 bg-transparent py-1 pl-3 pr-8 text-zinc-900 dark:text-white focus:ring-0 cursor-pointer w-48 truncate" onchange="this.form.submit()">
                        <option value="">All Stores (Overview)</option>
                        @foreach($stores as $s)
                            <option value="{{ $s->id }}" {{ (isset($selectedStoreId) && $selectedStoreId == $s->id) ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="flex items-center gap-2 bg-white dark:bg-zinc-800 p-2 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <select name="date_filter" id="dateFilter" class="text-sm border-0 bg-transparent py-1 pl-3 pr-8 text-zinc-900 dark:text-white focus:ring-0 cursor-pointer" onchange="toggleCustomDate(this.value)">
                        <option value="today" {{ (isset($dateFilter) && $dateFilter == 'today') ? 'selected' : '' }}>Hari Ini</option>
                        <option value="7days" {{ (isset($dateFilter) && $dateFilter == '7days') ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30days" {{ (isset($dateFilter) && $dateFilter == '30days') ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="custom" {{ (isset($dateFilter) && $dateFilter == 'custom') ? 'selected' : '' }}>Kustom</option>
                    </select>
                    
                    <div id="customDateInputs" class="{{ (isset($dateFilter) && $dateFilter == 'custom') ? 'flex' : 'hidden' }} items-center gap-2 border-l border-zinc-200 dark:border-zinc-700 pl-2 ml-1">
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="text-sm border border-zinc-200 dark:border-zinc-600 rounded-md py-1 px-2 bg-transparent dark:text-white">
                        <span class="text-zinc-400 text-sm">to</span>
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="text-sm border border-zinc-200 dark:border-zinc-600 rounded-md py-1 px-2 bg-transparent dark:text-white">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-1.5 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </div>
                
                @if((isset($selectedStoreId) && $selectedStoreId) || (isset($dateFilter) && $dateFilter != 'today'))
                    <a href="{{ route('admin.dashboard') }}" class="p-2 bg-white dark:bg-zinc-800 text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm transition-colors" title="Clear Filters">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>
            
            <script>
                function toggleCustomDate(value) {
                    const customInputs = document.getElementById('customDateInputs');
                    if (value === 'custom') {
                        customInputs.classList.remove('hidden');
                        customInputs.classList.add('flex');
                    } else {
                        customInputs.classList.add('hidden');
                        customInputs.classList.remove('flex');
                        document.getElementById('filterForm').submit();
                    }
                }
            </script>
        </div>

        <!-- Stats Grid -->
        @php
            $gridCols = auth()->user()->hasRole('admin') ? 'xl:grid-cols-5' : 'lg:grid-cols-4';
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 {{ $gridCols }} gap-4 animate-fade-in-up delay-100">
            <!-- Stat Card 1 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-5 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer" onclick="window.location.href='{{ route('admin.products.index') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Products</p>
                        <p class="mt-1 text-lg font-bold tracking-tight text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ number_format($totalProducts) }}</p>
                    </div>
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/40 rounded-lg group-hover:bg-blue-100 dark:group-hover:bg-blue-900/60 transition-colors">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-5 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer" onclick="window.location.href='{{ route('admin.categories.index') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Categories</p>
                        <p class="mt-1 text-lg font-bold tracking-tight text-zinc-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">{{ number_format($totalCategories) }}</p>
                    </div>
                    <div class="p-2 bg-green-50 dark:bg-green-900/40 rounded-lg group-hover:bg-green-100 dark:group-hover:bg-green-900/60 transition-colors">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Stat Card: Visitors -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-5 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Visitors</p>
                        <p class="mt-1 text-lg font-bold tracking-tight text-zinc-900 dark:text-white group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">{{ number_format($totalVisitors ?? 0) }}</p>
                    </div>
                    <div class="p-2 bg-pink-50 dark:bg-pink-900/40 rounded-lg group-hover:bg-pink-100 dark:group-hover:bg-pink-900/60 transition-colors">
                        <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            @if(auth()->user()->hasRole('admin'))
            <!-- Stat Card 4 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-5 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer" onclick="window.location.href='{{ route('admin.stores.index') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Stores</p>
                        <p class="mt-1 text-lg font-bold tracking-tight text-zinc-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ number_format($totalStores ?? 0) }}</p>
                    </div>
                    <div class="p-2 bg-purple-50 dark:bg-purple-900/40 rounded-lg group-hover:bg-purple-100 dark:group-hover:bg-purple-900/60 transition-colors">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Stat Card 5 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-5 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer" onclick="window.location.href='{{ route('admin.users.index') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Users</p>
                        <p class="mt-1 text-lg font-bold tracking-tight text-zinc-900 dark:text-white group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">{{ number_format($totalUsers ?? 0) }}</p>
                    </div>
                    <div class="p-2 bg-yellow-50 dark:bg-yellow-900/40 rounded-lg group-hover:bg-yellow-100 dark:group-hover:bg-yellow-900/60 transition-colors">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @else
            <!-- Stat Card 4 (Catalog Quick Link) -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-5 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer" onclick="window.open('{{ isset($store) && $store ? route('catalog.show', $store->slug) : '#' }}', '_blank')">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">View Catalog</p>
                        <p class="mt-1 text-lg font-bold tracking-tight text-zinc-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Visit</p>
                    </div>
                    <div class="p-2 bg-orange-50 dark:bg-orange-900/40 rounded-lg group-hover:bg-orange-100 dark:group-hover:bg-orange-900/60 transition-colors">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-200">
            <!-- Traffic Chart -->
            <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-4">{{ $chartTitle ?? 'Grafik Pengunjung' }}</h3>
                <div class="relative h-64 w-full">
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                @if(isset($showGeneralOverview) && $showGeneralOverview)
                    <!-- Top Store Card -->
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-sm p-6 text-white transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-indigo-100">Toko Teramai</p>
                                <p class="mt-2 text-2xl font-bold tracking-tight truncate max-w-[180px]">{{ $topStore ? $topStore->name : '-' }}</p>
                                <p class="text-xs text-indigo-200 mt-1">{{ $topStore ? number_format($topStore->visitors_count) . ' Pengunjung' : '' }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Top 5 Stores Table -->
                    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 overflow-hidden">
                        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700">
                            <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Peringkat Toko (Top 5)</h3>
                        </div>
                        <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            @forelse($topStores as $ts)
                            <div class="px-5 py-3 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.dashboard') }}?store_id={{ $ts->id }}'">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-md bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                        <span class="font-bold text-sm">{{ $loop->iteration }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white truncate max-w-[140px]">{{ $ts->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-zinc-900 dark:text-white">{{ number_format($ts->visitors_count) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-zinc-500">Visitors</p>
                                </div>
                            </div>
                            @empty
                            <div class="px-5 py-6 text-center text-sm text-zinc-500">Belum ada data toko.</div>
                            @endforelse
                        </div>
                    </div>
                @else
                    <!-- WA Clicks Card -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-100">Total Klik Pesan WA</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight">{{ number_format($totalWaClicks ?? 0) }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Top Products Table -->
                    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 overflow-hidden">
                        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700">
                            <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Top 5 Produk</h3>
                        </div>
                        <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            @forelse($topProducts as $tp)
                            <div class="px-5 py-3 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors cursor-pointer" onclick="window.open('{{ route('catalog.show', $tp->store->slug) }}?p={{ $tp->slug }}', '_blank')">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-md bg-zinc-200 dark:bg-zinc-700 flex-shrink-0 overflow-hidden">
                                        @if($tp->image)
                                            <img src="{{ str_starts_with($tp->image, 'http') ? $tp->image : Storage::url($tp->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-zinc-400 text-xs">No Img</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white truncate max-w-[140px]" title="{{ $tp->name }}">{{ $tp->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ $tp->category->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-zinc-900 dark:text-white">{{ number_format($tp->view_logs_count ?? $tp->views_count) }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-zinc-500">Views</p>
                                </div>
                            </div>
                            @empty
                            <div class="px-5 py-6 text-center text-sm text-zinc-500">Belum ada data view produk.</div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ctx = document.getElementById('trafficChart');
                if (ctx) {
                    new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: @json($chartLabels ?? []),
                            datasets: [{
                                label: 'Pengunjung',
                                data: @json($chartValues ?? []),
                                borderColor: '#3b82f6', // blue-500
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                pointBackgroundColor: '#3b82f6',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    </div>
@endsection
