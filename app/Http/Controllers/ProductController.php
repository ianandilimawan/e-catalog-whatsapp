<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;
use App\Services\FileUploadService;


class ProductController extends Controller
{
    use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-products')->only(['index', 'show']);
        $this->middleware('permission:create-products')->only(['create', 'store']);
        $this->middleware('permission:edit-products')->only(['edit', 'update']);
        $this->middleware('permission:delete-products')->only('destroy');
    }

    public function index()
    {
        return view('admin.products.index');
    }

    public function create()
    {
        $product = new Product();
        $fileUrls = $this->getFileUrls(Product::class);
        
        $storesQuery = \App\Models\Store::query();
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            $storesQuery->where('user_id', auth()->id());
        }
        $stores = $storesQuery->pluck('name', 'id');
        
        $categoriesQuery = \App\Models\Category::query();
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            $categoriesQuery->whereIn('store_id', $stores->keys());
        }
        $categories = $categoriesQuery->pluck('name', 'id');

        return view('admin.products.create', compact('product', 'fileUrls', 'stores', 'categories'));
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            $store = \App\Models\Store::find($data['store_id']);
            if (!$store || $store->user_id !== auth()->id()) abort(403, 'Unauthorized store selection');
        }

        $this->handleFileUploads($request, $data, Product::class, 'product');

        $product = Product::create($data);

        if ($request->hasFile('detail_images')) {
            $uploadedPaths = (new FileUploadService())->uploadMultiple($request->file('detail_images'), null, 'product_images');
            foreach ($uploadedPaths as $path) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        ActivityLogService::logCreate($product);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'redirect' => route('admin.products.index')
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin']) && $product->store->user_id !== auth()->id()) abort(403, 'Unauthorized');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin']) && $product->store->user_id !== auth()->id()) abort(403, 'Unauthorized');

        $fileUrls = $this->getFileUrls(Product::class, $product);
        
        $storesQuery = \App\Models\Store::query();
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            $storesQuery->where('user_id', auth()->id());
        }
        $stores = $storesQuery->pluck('name', 'id');
        
        $categoriesQuery = \App\Models\Category::query();
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            $categoriesQuery->whereIn('store_id', $stores->keys());
        }
        $categories = $categoriesQuery->pluck('name', 'id');

        return view('admin.products.edit', compact('product', 'fileUrls', 'stores', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin']) && $product->store->user_id !== auth()->id()) abort(403, 'Unauthorized');
        
        $data = $request->validated();
        
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            $store = \App\Models\Store::find($data['store_id']);
            if (!$store || $store->user_id !== auth()->id()) abort(403, 'Unauthorized store selection');
        }

        $this->handleFileUploads($request, $data, Product::class, 'product', $product);

        $oldValues = $product->getOriginal();

        $product->update($data);

        if ($request->hasFile('detail_images')) {
            $uploadedPaths = (new FileUploadService())->uploadMultiple($request->file('detail_images'), null, 'product_images');
            foreach ($uploadedPaths as $path) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        ActivityLogService::logUpdate($product, $oldValues);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'redirect' => route('admin.products.index')
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin']) && $product->store->user_id !== auth()->id()) abort(403, 'Unauthorized');

        $this->deleteAssociatedFiles(Product::class, $product);
        foreach ($product->images as $image) {
            FileUploadService::deleteFile($image->image_path);
        }

        ActivityLogService::logDelete($product);

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id === $product->id) {
            FileUploadService::deleteFile($image->image_path);
            $image->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 403);
    }
}
