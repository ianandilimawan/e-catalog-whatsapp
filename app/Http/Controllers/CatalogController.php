<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    public function show($slug, Request $request)
    {
        $store = Store::where('slug', $slug)->firstOrFail();

        if (isset($store->is_active) && !$store->is_active) {
            abort(404, 'Toko ini sedang tidak aktif.');
        }

        $categories = Category::where('store_id', $store->id)
            ->withCount(['products' => function ($q) use ($store) {
                $q->where('store_id', $store->id);
            }])
            ->get();

        $uncategorizedCount = Product::where('store_id', $store->id)->whereNull('category_id')->count();
        $totalProductsCount = Product::where('store_id', $store->id)->count();
        
        $productsQuery = Product::with('images')->where('store_id', $store->id);
        if ($request->category === 'uncategorized') {
            $productsQuery->whereNull('category_id');
        } elseif ($request->category && $request->category !== 'all') {
            $productsQuery->where('category_id', $request->category);
        }

        $products = $productsQuery->paginate(20);

        if ($request->ajax()) {
            $formattedProducts = collect($products->items())->map(function($p) {
                $images = [];
                $mainImage = $p->image ? (str_starts_with($p->image, 'http') ? $p->image : \Storage::url($p->image)) : null;
                if ($mainImage) $images[] = $mainImage;
                foreach($p->images as $img) {
                    $images[] = str_starts_with($img->image_path, 'http') ? $img->image_path : \Storage::url($img->image_path);
                }
                return [
                    'id' => $p->id,
                    'category_id' => $p->category_id,
                    'slug' => $p->slug,
                    'name' => $p->name,
                    'price' => $p->price,
                    'description' => $p->description,
                    'image' => $mainImage,
                    'images' => $images,
                    'views_count' => $p->views_count
                ];
            });

            return response()->json([
                'data' => $formattedProducts,
                'next_page_url' => $products->nextPageUrl()
            ]);
        }

        // Log visitor
        $ip = request()->ip();
        $date = now()->toDateString();
        
        // Only log once per IP per day for this store
        \App\Models\StoreVisitor::firstOrCreate([
            'store_id' => $store->id,
            'ip_address' => $ip,
            'date' => $date,
        ], [
            'user_agent' => request()->userAgent()
        ]);

        return view('katalog', compact('store', 'categories', 'products', 'uncategorizedCount', 'totalProductsCount'));
    }

    public function trackProductView($slug, Product $product)
    {
        // Simple increment
        $product->increment('views_count');
        
        \App\Models\ProductViewLog::create([
            'store_id' => $product->store_id,
            'product_id' => $product->id,
        ]);
        
        return response()->json(['success' => true]);
    }

    public function trackWaClick($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if ($store) {
            $store->increment('wa_checkout_clicks');
            
            \App\Models\StoreWaClick::create([
                'store_id' => $store->id,
            ]);
            
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
