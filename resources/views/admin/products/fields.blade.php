@if (auth()->user()->hasRole('admin'))
    <div
        class="mb-8 p-5 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-800/50 relative">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
            </svg>
        </div>
        <h3 class="text-sm font-bold text-indigo-800 dark:text-indigo-400 mb-4 flex items-center gap-2 relative z-10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                </path>
            </svg>
            Super Admin Only Area
        </h3>
        <div class="relative z-10">
            <x-select name="store_id" label="Assign Product to Store" value="{{ $product->store_id ?? '' }}"
                :options="$stores" />
        </div>
    </div>
@endif

<div class="grid grid-cols-1 gap-8 mb-4">
    <!-- Product Details Section -->
    <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 lg:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Product Details</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Information about the product you are selling.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <x-input-floating type="text" name="name" label="Product Name" value="{{ $product->name ?? '' }}" />
            <x-input-floating type="text" name="slug" label="URL (Slug)" value="{{ $product->slug ?? '' }}" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <x-select-floating name="category_id" label="Category" value="{{ $product->category_id ?? '' }}"
                :options="$categories" />
            <x-input-floating type="text" name="price" label="Price (Rp)" value="{{ $product->price ?? '' }}"
                :isCurrency="true" />
        </div>

        <x-textarea-floating name="description" label="Product Description" value="{{ $product->description ?? '' }}" />
    </div>

    <!-- Product Images Section -->
    <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 lg:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2.5 bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Product Images</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Upload pictures of your product.</p>
            </div>
        </div>

        <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-800">
            <x-filepond name="image" label="Gambar Utama" :defaultFile="isset($fileUrls['image']) ? $fileUrls['image'] : null" />
            <p class="text-xs text-gray-500 mt-2"><i class="fa fa-info-circle mr-1"></i> Rekomendasi: Gunakan gambar
                persegi (rasio 1:1) minimal resolusi 800x800px agar tampilan katalog rapi.</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Detail Images (Opsional)</h3>

            @if (isset($product) && $product->images && $product->images->count() > 0)
                <div
                    class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                    @foreach ($product->images as $img)
                        <div class="relative w-24 h-24 rounded-lg overflow-hidden border shadow-sm group"
                            id="detail-image-{{ $img->id }}">
                            <img src="{{ str_starts_with($img->image_path, 'http') ? $img->image_path : Storage::url($img->image_path) }}"
                                class="w-full h-full object-cover">
                            <button type="button"
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 shadow"
                                onclick="deleteDetailImage({{ $product->id }}, {{ $img->id }})"
                                title="Hapus Gambar">
                                <i class="fa fa-times text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div x-data="{ fields: [] }" class="mt-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-4">
                    <template x-for="(field, index) in fields" :key="field.id">
                        <div
                            class="relative bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl p-3 animate-fade-in-up flex flex-col justify-center">
                            <button type="button" @click="fields.splice(index, 1)"
                                class="absolute -top-2 -right-2 z-10 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-md transition-colors"
                                title="Hapus Input">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            <div class="pt-2 w-full">
                                <x-filepond name="detail_images[]" accept="image/*" />
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="fields.push({id: Date.now()})"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Gambar Lagi
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = true;
        $hasSlugField = true;
        $slugSourceField = 'name';
        $tagifyFields = [];
        $textareaFields = [];
        $selectFields = [
            0 => 'store_id',
            1 => 'category_id',
        ];
        $currencyFields = [
            0 => 'price',
        ];
        $passwordFields = [];
    @endphp
    @include('admin.partials.form-scripts')

    <script>
        function deleteDetailImage(productId, imageId) {
            if (confirm('Yakin ingin menghapus gambar ini?')) {
                fetch(`/admin/products/${productId}/images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const el = document.getElementById(`detail-image-${imageId}`);
                            if (el) el.remove();
                        } else {
                            alert('Gagal menghapus gambar.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat menghapus gambar.');
                    });
            }
        }
    </script>
@endpush
