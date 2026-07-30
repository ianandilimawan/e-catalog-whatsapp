<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\Category;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class CategoryController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-categories')->only(['index', 'show']);
        $this->middleware('permission:create-categories')->only(['create', 'store']);
        $this->middleware('permission:edit-categories')->only(['edit', 'update']);
        $this->middleware('permission:delete-categories')->only('destroy');
    }

    public function index()
    {
        return view('admin.categories.index');
    }

    public function create()
    {
        $category = new Category();
        $fileUrls = $this->getFileUrls(Category::class);
        
        $storesQuery = \App\Models\Store::query();
        if (auth()->user()->hasRole('admin-toko')) {
            $storesQuery->where('user_id', auth()->id());
        }
        $stores = $storesQuery->pluck('name', 'id');

        return view('admin.categories.create', compact('category', 'fileUrls', 'stores'));
    }

    public function store(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        
        if (auth()->user()->hasRole('admin-toko')) {
            $store = \App\Models\Store::find($data['store_id']);
            if (!$store || $store->user_id !== auth()->id()) abort(403, 'Unauthorized store selection');
        }

        $this->handleFileUploads($request, $data, Category::class, 'category');

        $category = Category::create($data);

        ActivityLogService::logCreate($category);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'redirect' => route('admin.categories.index')
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        if (auth()->user()->hasRole('admin-toko') && $category->store->user_id !== auth()->id()) abort(403, 'Unauthorized');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        if (auth()->user()->hasRole('admin-toko') && $category->store->user_id !== auth()->id()) abort(403, 'Unauthorized');

        $fileUrls = $this->getFileUrls(Category::class, $category);
        
        $storesQuery = \App\Models\Store::query();
        if (auth()->user()->hasRole('admin-toko')) {
            $storesQuery->where('user_id', auth()->id());
        }
        $stores = $storesQuery->pluck('name', 'id');

        return view('admin.categories.edit', compact('category', 'fileUrls', 'stores'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if (auth()->user()->hasRole('admin-toko') && $category->store->user_id !== auth()->id()) abort(403, 'Unauthorized');

        $data = $request->validated();
        
        if (auth()->user()->hasRole('admin-toko')) {
            $store = \App\Models\Store::find($data['store_id']);
            if (!$store || $store->user_id !== auth()->id()) abort(403, 'Unauthorized store selection');
        }

        $this->handleFileUploads($request, $data, Category::class, 'category', $category);

        $oldValues = $category->getOriginal();

        $category->update($data);

        ActivityLogService::logUpdate($category, $oldValues);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'redirect' => route('admin.categories.index')
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if (auth()->user()->hasRole('admin-toko') && $category->store->user_id !== auth()->id()) abort(403, 'Unauthorized');

        $this->deleteAssociatedFiles(Category::class, $category);

        ActivityLogService::logDelete($category);

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }


}
