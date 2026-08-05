<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StoreVisitor;
use App\Models\StoreWaClick;
use App\Models\ProductViewLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $store = auth()->user()->store;
        if (!$store) {
            return redirect()->route('onboarding.store');
        }

        $periodKey = $request->query('period', '7days');

        if ($periodKey === 'today') {
            $startDate = Carbon::today();
            $daysCount = 1;
        } elseif ($periodKey === '30days') {
            $startDate = Carbon::today()->subDays(29);
            $daysCount = 30;
        } else {
            $periodKey = '7days';
            $startDate = Carbon::today()->subDays(6);
            $daysCount = 7;
        }

        $visitorsCount = StoreVisitor::where('store_id', $store->id)
            ->whereDate('created_at', '>=', $startDate)
            ->count();

        $waClicksCount = StoreWaClick::where('store_id', $store->id)
            ->whereDate('created_at', '>=', $startDate)
            ->count();

        $productViewsCount = ProductViewLog::where('store_id', $store->id)
            ->whereDate('created_at', '>=', $startDate)
            ->count();

        // Chart Data Array
        $chartDates = [];
        $chartVisitors = [];
        $chartWaClicks = [];

        $period = CarbonPeriod::create($startDate, Carbon::today());
        foreach ($period as $date) {
            $chartDates[] = $daysCount === 30 ? $date->format('d/m') : $date->isoFormat('D MMM');

            $chartVisitors[] = StoreVisitor::where('store_id', $store->id)
                ->whereDate('created_at', $date)
                ->count();

            $chartWaClicks[] = StoreWaClick::where('store_id', $store->id)
                ->whereDate('created_at', $date)
                ->count();
        }

        // Top 5 Products
        $topProducts = Product::where('store_id', $store->id)
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        return view('app.stats.index', compact(
            'store',
            'periodKey',
            'visitorsCount',
            'waClicksCount',
            'productViewsCount',
            'chartDates',
            'chartVisitors',
            'chartWaClicks',
            'topProducts'
        ));
    }
}
