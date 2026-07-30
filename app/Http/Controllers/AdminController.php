<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $storeId = $request->input('store_id');
        $isAdmin = auth()->user()->hasRole('admin');
        
        $productsQuery = \App\Models\Product::query();
        $categoriesQuery = \App\Models\Category::query();
        $visitorsQuery = \App\Models\StoreVisitor::query();
        
        $dashboardData = [];

        if ($isAdmin) {
            $dashboardData['totalStores'] = \App\Models\Store::count();
            // Count only users with admin-toko role
            $dashboardData['totalUsers'] = \App\Models\User::whereHas('roles', function ($q) {
                $q->where('name', 'admin-toko');
            })->count();
            
            $dashboardData['stores'] = \App\Models\Store::orderBy('name')->get();
            $dashboardData['selectedStoreId'] = $storeId;
            
            if ($storeId) {
                $productsQuery->where('store_id', $storeId);
                $categoriesQuery->where('store_id', $storeId);
                $visitorsQuery->where('store_id', $storeId);
                
                $dashboardData['recentVisitors'] = \App\Models\StoreVisitor::where('store_id', $storeId)
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get();
            } else {
                $dashboardData['recentVisitors'] = \App\Models\StoreVisitor::with('store')
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get();
            }
        } else {
            $store = \App\Models\Store::first(); // They only see their store due to global scope
            $dashboardData['store'] = $store;
            if ($store) {
                $visitorsQuery->where('store_id', $store->id);
                $dashboardData['recentVisitors'] = \App\Models\StoreVisitor::where('store_id', $store->id)
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get();
            } else {
                $dashboardData['recentVisitors'] = collect();
            }
        }

        // --- New Analytics Data ---
        $dateFilter = $request->input('date_filter', 'today');
        $startDate = null;
        $endDate = now()->endOfDay();
        $chartTitle = 'Grafik Pengunjung';

        if ($dateFilter === 'today') {
            $startDate = now()->startOfDay();
            $chartTitle = 'Grafik Pengunjung (Hari Ini)';
        } elseif ($dateFilter === '7days') {
            $startDate = now()->subDays(6)->startOfDay();
            $chartTitle = 'Grafik Pengunjung (7 Hari Terakhir)';
        } elseif ($dateFilter === '30days') {
            $startDate = now()->subDays(29)->startOfDay();
            $chartTitle = 'Grafik Pengunjung (30 Hari Terakhir)';
        } elseif ($dateFilter === 'custom') {
            $startDate = $request->input('start_date') ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfDay();
            $endDate = $request->input('end_date') ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();
            $chartTitle = 'Grafik Pengunjung (' . $startDate->format('d M') . ' - ' . $endDate->format('d M') . ')';
        }

        $dashboardData['dateFilter'] = $dateFilter;
        $dashboardData['startDate'] = $startDate ? $startDate->format('Y-m-d') : '';
        $dashboardData['endDate'] = $endDate ? $endDate->format('Y-m-d') : '';
        $dashboardData['chartTitle'] = $chartTitle;

        // Chart Data: Visitors
        $chartQuery = clone $visitorsQuery;
        if ($startDate) {
            $chartQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $chartLabels = [];
        $chartValues = [];

        if ($dateFilter === 'today') {
            $rawChartData = $chartQuery->selectRaw('HOUR(created_at) as hour, count(*) as count')
                                       ->groupByRaw('HOUR(created_at)')
                                       ->orderBy('hour', 'asc')
                                       ->get()
                                       ->pluck('count', 'hour')
                                       ->toArray();
            for ($i = 0; $i <= 23; $i++) {
                $chartLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $chartValues[] = $rawChartData[$i] ?? 0;
            }
        } else {
            $rawChartData = $chartQuery->selectRaw('DATE(created_at) as date, count(*) as count')
                                       ->groupByRaw('DATE(created_at)')
                                       ->orderBy('date', 'asc')
                                       ->get()
                                       ->pluck('count', 'date')
                                       ->toArray();
                                       
            $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $dString = $date->toDateString();
                $chartLabels[] = $date->format('d M');
                $chartValues[] = $rawChartData[$dString] ?? 0;
            }
        }
        
        $dashboardData['chartLabels'] = $chartLabels;
        $dashboardData['chartValues'] = $chartValues;

        if ($isAdmin && !$storeId) {
            $dashboardData['showGeneralOverview'] = true;
            
            $topStores = \App\Models\Store::withCount(['visitors' => function($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            }])->orderBy('visitors_count', 'desc')->take(5)->get();
            
            $dashboardData['topStores'] = $topStores;
            $dashboardData['topStore'] = $topStores->first();
            
        } else {
            $dashboardData['showGeneralOverview'] = false;
            
            // Top 5 Products by views in date range
            $topProductsQuery = \App\Models\Product::query();
            if ($storeId) {
                 $topProductsQuery->where('store_id', $storeId);
            } elseif (!$isAdmin && $store) {
                 $topProductsQuery->where('store_id', $store->id);
            }
            
            $dashboardData['topProducts'] = $topProductsQuery->withCount(['viewLogs' => function($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            }])->orderBy('view_logs_count', 'desc')->take(5)->get();
    
            // WA Checkout Clicks in date range
            $waClicksQuery = \App\Models\StoreWaClick::query();
            if ($storeId) {
                 $waClicksQuery->where('store_id', $storeId);
            } elseif (!$isAdmin && $store) {
                 $waClicksQuery->where('store_id', $store->id);
            }
            
            if ($startDate) {
                 $waClicksQuery->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            $dashboardData['totalWaClicks'] = $waClicksQuery->count();
        }
        // --------------------------

        $dashboardData['totalProducts'] = $productsQuery->count();
        $dashboardData['totalCategories'] = $categoriesQuery->count();
        $dashboardData['totalVisitors'] = $visitorsQuery->count();

        return view('admin.pages.dashboard', $dashboardData);
    }
}
