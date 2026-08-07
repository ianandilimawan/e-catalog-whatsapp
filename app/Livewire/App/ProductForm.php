<?php

namespace App\Livewire\App;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Services\FileUploadService;
use Illuminate\Support\Str;

class ProductForm extends Component
{
    use WithFileUploads;

    public $productId = null;
    public $name = '';
    public $price = '';
    public $category_id = '';
    public $description = '';
    
    public $image; // New main image file
    public $existingImage = null;

    public $detailImages = []; // New detail image files
    public $existingDetailImages = []; // Array of ['id' => x, 'path' => y]

    // Quick Add Category Modal
    public $showCategoryModal = false;
    public $newCategoryName = '';

    protected function rules()
    {
        $imageRequired = !$this->productId || !$this->existingImage;

        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:2000',
            'image' => ($imageRequired ? 'required' : 'nullable') . '|image|max:5120',
            'detailImages.*' => 'nullable|image|max:5120',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama produk wajib diisi.',
        'price.required' => 'Harga produk wajib diisi.',
        'price.numeric' => 'Harga produk harus berupa angka.',
        'price.min' => 'Harga produk tidak boleh kurang dari 0.',
        'category_id.required' => 'Silakan pilih kategori produk.',
        'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        'image.required' => 'Foto utama produk wajib diunggah.',
        'image.image' => 'File foto utama harus berupa gambar (JPG, PNG, WEBP).',
        'image.max' => 'Ukuran foto utama maksimal 5MB.',
        'detailImages.*.image' => 'File foto tambahan harus berupa gambar.',
        'detailImages.*.max' => 'Ukuran foto tambahan maksimal 5MB.',
    ];

    public function mount($product = null)
    {
        if ($product && $product->exists) {
            $this->productId = $product->id;
            $this->name = $product->name;
            $this->price = (int)$product->price;
            $this->category_id = $product->category_id;
            $this->description = $product->description;
            $this->existingImage = $product->image;

            $this->existingDetailImages = $product->images->map(fn($img) => [
                'id' => $img->id,
                'image_path' => $img->image_path,
            ])->toArray();
        } else {
            // Default category if exists
            $store = auth()->user()->store;
            if ($store) {
                $firstCat = Category::where('store_id', $store->id)->first();
                if ($firstCat) {
                    $this->category_id = $firstCat->id;
                }
            }
        }
    }

    public function removeMainImage()
    {
        $this->image = null;
        $this->existingImage = null;
    }

    public function removeDetailImage($index)
    {
        if (isset($this->detailImages[$index])) {
            unset($this->detailImages[$index]);
            $this->detailImages = array_values($this->detailImages);
        }
    }

    public function removeExistingDetailImage($id)
    {
        $imgModel = ProductImage::find($id);
        if ($imgModel) {
            FileUploadService::deleteFile($imgModel->image_path);
            $imgModel->delete();
        }

        $this->existingDetailImages = array_values(array_filter(
            $this->existingDetailImages,
            fn($item) => $item['id'] !== $id
        ));
        $this->dispatch('toast', message: 'Foto dihapus', type: 'success');
    }

    public function startEditCategory($id, $name)
    {
        $this->editingCategoryId = $id;
        $this->editingCategoryName = $name;
    }

    public function cancelEditCategory()
    {
        $this->editingCategoryId = null;
        $this->editingCategoryName = '';
    }

    public function updateCategory()
    {
        $this->validate([
            'editingCategoryName' => 'required|string|max:255',
        ]);

        $store = auth()->user()->store;
        if (!$store) return;

        $category = Category::where('store_id', $store->id)->where('id', $this->editingCategoryId)->first();
        if ($category) {
            $category->update([
                'name' => trim($this->editingCategoryName),
                'slug' => Str::slug($this->editingCategoryName),
            ]);
            $this->dispatch('toast', message: 'Kategori berhasil diperbarui!', type: 'success');
        }

        $this->editingCategoryId = null;
        $this->editingCategoryName = '';
    }

    public function confirmDeleteCategory($id)
    {
        $this->confirmingDeleteCategoryId = $id;
    }

    public function cancelDeleteCategory()
    {
        $this->confirmingDeleteCategoryId = null;
    }

    public function deleteCategory($id)
    {
        $store = auth()->user()->store;
        if (!$store) return;

        $category = Category::where('store_id', $store->id)->where('id', $id)->first();
        if ($category) {
            Product::where('store_id', $store->id)->where('category_id', $category->id)->update(['category_id' => null]);
            $category->delete();

            if ($this->category_id == $id) {
                $this->category_id = '';
            }

            $this->dispatch('toast', message: 'Kategori berhasil dihapus.', type: 'success');
        }

        $this->confirmingDeleteCategoryId = null;
    }

    public function saveCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:255',
        ]);

        $store = auth()->user()->store;
        if (!$store) return;

        $slug = Str::slug($this->newCategoryName);
        $category = Category::create([
            'store_id' => $store->id,
            'name' => trim($this->newCategoryName),
            'slug' => $slug,
        ]);

        $this->category_id = $category->id;
        $this->newCategoryName = '';
        $this->showCategoryModal = false;
        $this->dispatch('toast', message: 'Kategori baru ditambahkan!', type: 'success');
    }

    public function save()
    {
        $this->validate();

        $store = auth()->user()->store;
        if (!$store) {
            $this->dispatch('toast', message: 'Toko tidak ditemukan.', type: 'error');
            return;
        }

        $slug = Str::slug($this->name);

        // Upload main image if provided
        $mainImagePath = $this->existingImage;
        if ($this->image) {
            $mainImagePath = (new FileUploadService())
                ->folder('products')
                ->upload($this->image, $this->existingImage);
        }

        if (!$mainImagePath) {
            $this->addError('image', 'Foto utama produk wajib diunggah.');
            $this->dispatch('toast', message: 'Silakan unggah foto utama produk terlebih dahulu.', type: 'error');
            return;
        }

        if ($this->productId) {
            // Update
            $product = Product::where('store_id', $store->id)->where('id', $this->productId)->firstOrFail();
            $product->update([
                'name' => $this->name,
                'slug' => $slug,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image' => $mainImagePath,
            ]);
            $msg = 'Produk berhasil diperbarui!';
        } else {
            // Create
            $product = Product::create([
                'store_id' => $store->id,
                'name' => $this->name,
                'slug' => $slug,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'image' => $mainImagePath,
                'views_count' => 0,
            ]);
            $msg = 'Produk berhasil ditambahkan!';
        }

        // Upload new detail images
        if (!empty($this->detailImages)) {
            $uploader = new FileUploadService();
            foreach ($this->detailImages as $detailFile) {
                if ($detailFile) {
                    $path = $uploader->folder('product_images')->upload($detailFile);
                    if ($path) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $path,
                        ]);
                    }
                }
            }
        }

        session()->flash('success', $msg);
        return redirect()->route('app.products.index');
    }

    public function render()
    {
        $store = auth()->user()->store;
        $categories = $store ? Category::where('store_id', $store->id)->get() : collect();

        return view('livewire.app.product-form', [
            'categories' => $categories,
        ]);
    }
}
