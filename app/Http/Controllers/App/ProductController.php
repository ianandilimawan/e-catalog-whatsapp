<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('app.products.index');
    }

    public function create()
    {
        $product = new Product();
        return view('app.products.form', compact('product'));
    }

    public function edit(Product $product)
    {
        $store = auth()->user()->store;
        if (!$store || $product->store_id !== $store->id) {
            abort(403, 'Unauthorized product access.');
        }

        return view('app.products.form', compact('product'));
    }
}
