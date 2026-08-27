<form wire:submit.prevent="save" class="space-y-5 pb-8">
    <!-- Header Title -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('app.products.index') }}"
                class="p-2 rounded-full bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white">
                    {{ $productId ? 'Edit Produk' : 'Tambah Produk' }}
                </h1>
                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400">Isi detail produk dengan mudah dari HP
                    atau Desktop.</p>
            </div>
        </div>
    </div>

    <!-- 1. Image Upload Section (Horizontal Scrollable Slots - Larger on Desktop) -->
    <div
        class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                Foto Produk (Tap untuk Kamera/Galeri)
            </label>
            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                Disarankan: <strong class="text-zinc-700 dark:text-zinc-300 font-semibold">800 x 800 px</strong> (Persegi 1:1, Maks. 5MB)
            </p>
        </div>

        <div class="flex gap-3 md:gap-4 overflow-x-auto snap-x no-scrollbar py-1">
            <!-- Slot 1: Main Photo (Larger) -->
            <div
                class="relative snap-start flex-shrink-0 w-28 h-28 md:w-36 md:h-36 rounded-2xl border-2 border-dashed border-emerald-500/50 bg-emerald-50/50 dark:bg-emerald-950/20 flex flex-col items-center justify-center overflow-hidden cursor-pointer group">
                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover" />
                    <button type="button" wire:click="removeMainImage"
                        class="absolute top-1.5 right-1.5 p-1 bg-red-600 text-white rounded-full shadow-md hover:bg-red-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <span
                        class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded bg-black/60 text-[10px] text-white font-bold">Utama</span>
                @elseif ($existingImage)
                    <img src="{{ \App\Services\FileUploadService::getFileUrl($existingImage) }}"
                        class="w-full h-full object-cover" />
                    <button type="button" wire:click="removeMainImage"
                        class="absolute top-1.5 right-1.5 p-1 bg-red-600 text-white rounded-full shadow-md hover:bg-red-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <span
                        class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded bg-black/60 text-[10px] text-white font-bold">Utama</span>
                @else
                    <label for="main_image_input"
                        class="w-full h-full flex flex-col items-center justify-center cursor-pointer p-2 text-center">
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] md:text-xs font-bold text-emerald-700 dark:text-emerald-400 leading-tight">Foto
                            Utama</span>
                        <span class="text-[9px] text-zinc-400 dark:text-zinc-500">Wajib</span>
                    </label>
                @endif
                <input id="main_image_input" type="file" wire:model="image" accept="image/*"
                    class="hidden" />
            </div>

            <!-- Existing Detail Photos -->
            @foreach ($existingDetailImages as $existDetail)
                <div
                    class="relative snap-start flex-shrink-0 w-24 h-28 md:w-32 md:h-36 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                    <img src="{{ \App\Services\FileUploadService::getFileUrl($existDetail['image_path']) }}"
                        class="w-full h-full object-cover" />
                    <button type="button" wire:click="removeExistingDetailImage({{ $existDetail['id'] }})"
                        class="absolute top-1.5 right-1.5 p-1 bg-red-600 text-white rounded-full shadow-md">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endforeach

            <!-- New Detail Photo Upload Slots -->
            @foreach ($detailImages as $idx => $detImg)
                @if ($detImg)
                    <div
                        class="relative snap-start flex-shrink-0 w-24 h-28 md:w-32 md:h-36 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                        <img src="{{ $detImg->temporaryUrl() }}" class="w-full h-full object-cover" />
                        <button type="button" wire:click="removeDetailImage({{ $idx }})"
                            class="absolute top-1.5 right-1.5 p-1 bg-red-600 text-white rounded-full shadow-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
            @endforeach

            <!-- Add Additional Photo Slot -->
            <label for="detail_image_input"
                class="snap-start flex-shrink-0 w-24 h-28 md:w-32 md:h-36 rounded-2xl border-2 border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/40 flex flex-col items-center justify-center text-center cursor-pointer p-2 hover:border-emerald-500 transition-colors">
                <div class="text-xl md:text-2xl mb-1 text-zinc-400">+</div>
                <span class="text-[10px] md:text-xs font-semibold text-zinc-500 dark:text-zinc-400">Tambah Foto</span>
                <input id="detail_image_input" type="file" wire:model="detailImages" accept="image/*" multiple
                    class="hidden" />
            </label>
        </div>
        @error('image')
            <div class="mt-2.5 p-2.5 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 text-xs font-bold text-red-600 dark:text-red-400 flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>

    <!-- 2. Form Fields Card -->
    <div
        class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-5">
        
        <!-- Row 1: Nama Produk & Harga Jual (Sejajar Sempurna di Desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
            <!-- Nama Produk -->
            <div>
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                    Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="name" placeholder="Contoh: Kopi Susu Aren Gula Jawa"
                    class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 transition-colors" />
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga Jual -->
            <div>
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                    Harga Jual (Rp) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-zinc-500 dark:text-zinc-400 font-bold text-base">
                        Rp
                    </div>
                    <input type="number" inputmode="numeric" wire:model="price" placeholder="25000"
                        class="w-full h-12 pl-12 pr-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-bold text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 transition-colors" />
                </div>
                @error('price')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Row 2: Kategori Produk -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                    Kategori Produk
                </label>
                <button type="button" @click="$wire.set('showCategoryModal', true)"
                    class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 text-xs font-bold transition-colors flex items-center gap-1 border border-emerald-200 dark:border-emerald-800/60 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Kategori</span>
                </button>
            </div>

            @if ($categories->isEmpty())
                <div class="mb-2.5 p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/60 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-sm flex-shrink-0">📁</span>
                        <p class="text-xs text-amber-800 dark:text-amber-300 font-medium leading-tight">
                            <strong>Toko belum punya kategori!</strong> Buat kategori agar produkmu rapi di katalog.
                        </p>
                    </div>
                    <button type="button" @click="$wire.set('showCategoryModal', true)"
                        class="flex-shrink-0 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs shadow-sm flex items-center gap-1">
                        <span>+ Buat</span>
                    </button>
                </div>
            @endif

            <select wire:model="category_id"
                class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors">
                <option value="">{{ $categories->isEmpty() ? '⚠️ Belum ada kategori — Klik + Tambah Kategori' : '-- Pilih Kategori (Opsional / Tanpa Kategori) --' }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-1.5 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Kategori mengelompokkan produk di katalog tokomu agar pembeli mudah mencari.</span>
            </p>
            @error('category_id')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Row 3: Deskripsi Produk -->
        <div>
            <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                Deskripsi Produk
            </label>
            <textarea wire:model="description" rows="4"
                placeholder="Jelaskan detail bahan, porsi, rasa, atau info penting produk ini..."
                class="w-full p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-normal text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 transition-colors"></textarea>
            @error('description')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- 3. Sticky Save Bar (Fixed Bottom - Scaled Container) -->
    <div
        class="fixed bottom-[64px] lg:bottom-0 inset-x-0 z-30 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-lg border-t border-zinc-200 dark:border-zinc-800 py-3 shadow-lg">
        <div
            class="px-4 md:px-6 lg:px-8 max-w-md md:max-w-2xl lg:max-w-5xl xl:max-w-6xl mx-auto flex items-center justify-between gap-3">
            <a href="{{ route('app.products.index') }}"
                class="px-5 h-[48px] rounded-xl border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 font-bold text-sm flex items-center justify-center hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                Batal
            </a>
            <button type="submit" wire:loading.attr="disabled"
                class="w-full md:w-auto md:px-16 h-[48px] rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white font-bold text-base shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition-all">
                <span wire:loading.remove
                    wire:target="save">{{ $productId ? 'Simpan Perubahan' : 'Simpan Produk' }}</span>
                <span wire:loading wire:target="save">Memproses...</span>
            </button>
        </div>
    </div>

    <!-- Quick Modal Add / Manage Category -->
    @if ($showCategoryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl max-w-lg w-full p-5 md:p-6 shadow-2xl border border-zinc-200 dark:border-zinc-800 flex flex-col max-h-[85vh]">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-800">
                    <div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                            <span>📂</span>
                            <span>Kelola Kategori Produk</span>
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Tambah, ubah nama, atau hapus kategori toko Anda.</p>
                    </div>
                    <button type="button" wire:click="$set('showCategoryModal', false)" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-xl">
                        ✕
                    </button>
                </div>

                <!-- Add New Category Form -->
                <div class="py-4 border-b border-zinc-200 dark:border-zinc-800">
                    <label class="block text-xs font-bold mb-1 text-zinc-700 dark:text-zinc-300">Tambah Kategori Baru</label>
                    <div class="flex items-center gap-2">
                        <input type="text" wire:model="newCategoryName" wire:keydown.enter.prevent="saveCategory"
                            placeholder="Contoh: Skincare, Serum, Makanan..."
                            class="flex-1 h-10 px-3.5 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-xs font-medium border border-zinc-200 dark:border-zinc-700 focus:outline-none focus:border-emerald-500 text-zinc-900 dark:text-white" />
                        <button type="button" wire:click="saveCategory"
                            class="px-4 h-10 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow hover:bg-emerald-700 transition-colors flex items-center gap-1 whitespace-nowrap">
                            <span>+ Tambah</span>
                        </button>
                    </div>
                    @error('newCategoryName')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category List -->
                <div class="flex-1 overflow-y-auto py-3 space-y-2.5">
                    @forelse($categories as $cat)
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-800/60 rounded-xl border border-zinc-200/60 dark:border-zinc-700/60 flex items-center justify-between gap-2">
                            @if($editingCategoryId === $cat->id)
                                <!-- Inline Edit Form -->
                                <div class="flex-1 flex items-center gap-2">
                                    <input type="text" wire:model="editingCategoryName" wire:keydown.enter.prevent="updateCategory"
                                        class="flex-1 h-9 px-3 rounded-lg bg-white dark:bg-zinc-900 text-xs font-medium border border-emerald-500 focus:outline-none text-zinc-900 dark:text-white" />
                                    <button type="button" wire:click="updateCategory" class="px-3 h-9 rounded-lg bg-emerald-600 text-white text-xs font-bold">Simpan</button>
                                    <button type="button" wire:click="cancelEditCategory" class="px-2.5 h-9 rounded-lg bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs">Batal</button>
                                </div>
                            @elseif($confirmingDeleteCategoryId === $cat->id)
                                <!-- Inline Delete Confirmation -->
                                <div class="flex-1 flex items-center justify-between bg-red-50 dark:bg-red-950/40 p-2 rounded-lg border border-red-200 dark:border-red-800">
                                    <span class="text-xs font-semibold text-red-700 dark:text-red-300">Hapus "{{ $cat->name }}"?</span>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" wire:click="deleteCategory({{ $cat->id }})" class="px-3 py-1 rounded-md bg-red-600 text-white text-xs font-bold">Ya, Hapus</button>
                                        <button type="button" wire:click="cancelDeleteCategory" class="px-2.5 py-1 rounded-md bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs">Batal</button>
                                    </div>
                                </div>
                            @else
                                <!-- Category Info & Actions -->
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $cat->name }}</h4>
                                    <p class="text-[10px] text-zinc-500 dark:text-zinc-400">
                                        {{ \App\Models\Product::where('category_id', $cat->id)->count() }} produk
                                    </p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" wire:click="startEditCategory({{ $cat->id }}, '{{ e($cat->name) }}')"
                                        class="p-2 text-zinc-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                                        title="Edit Kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="confirmDeleteCategory({{ $cat->id }})"
                                        class="p-2 text-zinc-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                                        title="Hapus Kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-zinc-400 text-xs">Belum ada kategori. Silakan buat kategori baru di atas.</div>
                    @endforelse
                </div>

                <!-- Footer -->
                <div class="pt-3 border-t border-zinc-200 dark:border-zinc-800 flex justify-end">
                    <button type="button" wire:click="$set('showCategoryModal', false)" class="px-5 h-10 rounded-xl bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-bold">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif
</form>
