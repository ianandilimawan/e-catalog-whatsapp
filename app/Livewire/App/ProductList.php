<?php

namespace App\Livewire\App;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Services\FileUploadService;
use Illuminate\Support\Str;

class ProductList extends Component
{
    public $search = '';
    public $selectedCategory = null;
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => null],
    ];

    public function updatedSearch()
    {
        $this->perPage = 10;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->perPage = 10;
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function confirmDelete($productId)
    {
        $this->confirmingDeleteId = $productId;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteProduct($productId)
    {
        $store = auth()->user()->store;
        if (!$store) return;

        $product = Product::where('store_id', $store->id)->where('id', $productId)->first();
        if ($product) {
            // Delete main image & detail images
            if ($product->image) {
                FileUploadService::deleteFile($product->image);
            }
            foreach ($product->images as $img) {
                FileUploadService::deleteFile($img->image_path);
                $img->delete();
            }
            $product->delete();

            $this->dispatch('toast', message: 'Produk berhasil dihapus.', type: 'success');
        }

        $this->confirmingDeleteId = null;
    }

    public function duplicateProduct($productId)
    {
        $store = auth()->user()->store;
        if (!$store) return;

        $product = Product::where('store_id', $store->id)->where('id', $productId)->first();
        if ($product) {
            $newSlug = Str::slug($product->name . '-copy-' . time());
            $newProduct = Product::create([
                'store_id' => $store->id,
                'category_id' => $product->category_id,
                'name' => $product->name . ' (Salinan)',
                'slug' => $newSlug,
                'description' => $product->description,
                'price' => $product->price,
                'image' => $product->image,
                'views_count' => 0,
            ]);

            $this->dispatch('toast', message: 'Produk berhasil diduplikasi!', type: 'success');
        }
    }

    public function render()
    {
        $store = auth()->user()->store;
        $categories = $store ? Category::where('store_id', $store->id)->get() : collect();

        $query = Product::query();
        if ($store) {
            $query->where('store_id', $store->id);
        } else {
            $query->whereRaw('1 = 0');
        }

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        $totalCount = (clone $query)->count();
        $products = $query->with(['category'])->latest()->take($this->perPage)->get();

        return view('livewire.app.product-list', [
            'products' => $products,
            'categories' => $categories,
            'totalCount' => $totalCount,
            'hasMore' => $products->count() < $totalCount,
        ]);
    }
}
