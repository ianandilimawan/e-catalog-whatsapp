<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StoreVisitor;
use App\Models\StoreWaClick;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('onboarding.store');
        }

        $totalProducts = Product::where('store_id', $store->id)->count();
        
        $todayVisitors = StoreVisitor::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $todayWaClicks = StoreWaClick::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // 7 days visitor trend for mini sparkline
        $trendDates = [];
        $trendCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $trendDates[] = $date->isoFormat('D MMM');
            $count = StoreVisitor::where('store_id', $store->id)
                ->whereDate('created_at', $date)
                ->count();
            $trendCounts[] = $count;
        }

        // Top viewed products
        $topProducts = Product::where('store_id', $store->id)
            ->orderBy('views_count', 'desc')
            ->take(4)
            ->get();

        return view('app.dashboard', compact(
            'store',
            'user',
            'totalProducts',
            'todayVisitors',
            'todayWaClicks',
            'trendDates',
            'trendCounts',
            'topProducts'
        ));
    }
}
