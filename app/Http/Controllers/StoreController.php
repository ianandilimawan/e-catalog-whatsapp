<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\Store;
use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class StoreController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-stores')->only(['index', 'show']);
        $this->middleware('permission:create-stores')->only(['create', 'store']);
        $this->middleware('permission:edit-stores')->only(['edit', 'update']);
        $this->middleware('permission:delete-stores')->only('destroy');
    }

    public function index()
    {
        if (auth()->user()->hasRole('admin-toko')) {
            $store = Store::where('user_id', auth()->id())->first();
            if ($store) {
                return redirect()->route('admin.stores.edit', $store->id);
            }
            return redirect()->route('admin.stores.create');
        }
        
        return view('admin.stores.index');
    }

    public function create()
    {
        if (auth()->user()->hasRole('admin-toko')) {
            $storeCount = Store::where('user_id', auth()->id())->count();
            if ($storeCount >= 1) {
                return redirect()->route('admin.stores.index')->with('error', 'You can only have 1 store.');
            }
        }

        $store = new Store();
        $fileUrls = $this->getFileUrls(Store::class);
        $users = \App\Models\User::pluck('name', 'id');

        return view('admin.stores.create', compact('store', 'fileUrls', 'users'));
    }

    public function store(CreateStoreRequest $request)
    {
        if (auth()->user()->hasRole('admin-toko')) {
            $storeCount = Store::where('user_id', auth()->id())->count();
            if ($storeCount >= 1) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can only have 1 store.'
                    ], 403);
                }
                return redirect()->route('admin.stores.index')->with('error', 'You can only have 1 store.');
            }
        }

        $data = $request->validated();

        $this->handleFileUploads($request, $data, Store::class, 'store');

        $store = Store::create($data);

        ActivityLogService::logCreate($store);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Store created successfully.',
                'redirect' => route('admin.stores.index')
            ]);
        }

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }

    public function show(Store $store)
    {
        if (auth()->user()->hasRole('admin-toko') && $store->user_id !== auth()->id()) abort(403, 'Unauthorized');
        return view('admin.stores.show', compact('store'));
    }

    public function edit(Store $store)
    {
        if (auth()->user()->hasRole('admin-toko') && $store->user_id !== auth()->id()) abort(403, 'Unauthorized');

        $fileUrls = $this->getFileUrls(Store::class, $store);
        $users = \App\Models\User::pluck('name', 'id');

        return view('admin.stores.edit', compact('store', 'fileUrls', 'users'));
    }

    public function update(UpdateStoreRequest $request, Store $store)
    {
        if (auth()->user()->hasRole('admin-toko') && $store->user_id !== auth()->id()) abort(403, 'Unauthorized');

        $data = $request->validated();

        $this->handleFileUploads($request, $data, Store::class, 'store', $store);

        $oldValues = $store->getOriginal();

        $store->update($data);

        ActivityLogService::logUpdate($store, $oldValues);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Store updated successfully.',
                'redirect' => route('admin.stores.index')
            ]);
        }

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store)
    {
        if (auth()->user()->hasRole('admin-toko') && $store->user_id !== auth()->id()) abort(403, 'Unauthorized');

        $this->deleteAssociatedFiles(Store::class, $store);

        ActivityLogService::logDelete($store);

        $store->delete();
        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }


}
