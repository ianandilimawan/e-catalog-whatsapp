<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $stores = Store::where(function ($query) {
                $query->where('is_active', true)
                      ->orWhereNull('is_active');
            })
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->view('sitemap', [
            'stores' => $stores,
        ])->header('Content-Type', 'text/xml; charset=utf-8');
    }
}
