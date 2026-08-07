<div class="space-y-4">
    <!-- 1. Search Bar -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" 
               placeholder="Cari nama produk..." 
               class="w-full pl-10 pr-10 py-2.5 bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl text-sm placeholder-zinc-400 focus:outline-none focus:border-emerald-500 shadow-sm transition-colors" />
        @if($search)
            <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        @endif
    </div>

    <!-- 2. Category Filter Pills -->
    <div class="flex items-center gap-2 overflow-x-auto snap-x no-scrollbar py-1">
        <button wire:click="selectCategory(null)" 
                class="snap-start px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors border {{ is_null($selectedCategory) ? 'bg-emerald-600 border-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 border-zinc-200/80 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300' }}">
            Semua ({{ $totalCount }})
        </button>
        @foreach($categories as $cat)
            <button wire:click="selectCategory({{ $cat->id }})" 
                    class="snap-start px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors border {{ $selectedCategory == $cat->id ? 'bg-emerald-600 border-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 border-zinc-200/80 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300' }}">
                {{ $cat->name }}
            </button>
        @endforeach
        <button wire:click="openCategoryManager" 
                class="snap-start px-3 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-colors border bg-zinc-100 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 text-emerald-600 dark:text-emerald-400 hover:bg-zinc-200 dark:hover:bg-zinc-700 flex items-center gap-1">
            <span>⚙️</span>
            <span>Kelola Kategori</span>
        </button>
    </div>

    <!-- 3. Product Cards Grid (2 cols on Mobile, 3 cols on Tablet, 4 cols on Desktop) -->
    @if($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @foreach($products as $prod)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between group hover:border-emerald-500/50 transition-colors">
                    <div>
                        <!-- Product Thumbnail -->
                        <a href="{{ route('app.products.edit', $prod->id) }}" class="block relative aspect-square bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                            @if($prod->image)
                                <img src="{{ \App\Services\FileUploadService::getFileUrl($prod->image) }}" 
                                     alt="{{ $prod->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-zinc-400 text-xs font-medium">
                                    📷 No image
                                </div>
                            @endif

                            @if($prod->category)
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-[10px] font-semibold text-white truncate max-w-[80%]">
                                    {{ $prod->category->name }}
                                </span>
                            @endif
                        </a>

                        <!-- Details -->
                        <div class="p-3 md:p-4">
                            <a href="{{ route('app.products.edit', $prod->id) }}">
                                <h3 class="text-xs md:text-sm font-bold text-zinc-900 dark:text-white line-clamp-2 leading-snug">
                                    {{ $prod->name }}
                                </h3>
                            </a>
                            <p class="text-sm md:text-base font-black text-emerald-600 dark:text-emerald-400 mt-1">
                                Rp {{ number_format($prod->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Action Footer -->
                    <div class="p-2 pt-0 md:p-3 md:pt-0 border-t border-zinc-100 dark:border-zinc-800/60 flex items-center justify-between mt-1">
                        <a href="{{ route('app.products.edit', $prod->id) }}" 
                           class="flex-1 py-1.5 text-center text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-lg transition-colors">
                            ✏️ Edit
                        </a>

                        <!-- Dropdown Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                            </button>

                            <div x-show="open" 
                                 x-transition
                                 class="absolute right-0 bottom-full mb-1 w-32 bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 py-1 z-20 text-xs">
                                <button wire:click="duplicateProduct({{ $prod->id }})" @click="open = false" 
                                        class="w-full text-left px-3 py-2 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 flex items-center gap-2">
                                    <span>📄</span> Duplikat
                                </button>
                                <button wire:click="confirmDelete({{ $prod->id }})" @click="open = false" 
                                        class="w-full text-left px-3 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 flex items-center gap-2">
                                    <span>🗑️</span> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Load More Button -->
        @if($hasMore)
            <div class="text-center pt-4">
                <button wire:click="loadMore" wire:loading.attr="disabled" 
                        class="px-6 py-2.5 bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl text-xs font-bold text-zinc-700 dark:text-zinc-300 shadow-sm hover:border-emerald-500 transition-colors">
                    <span wire:loading.remove wire:target="loadMore">Muat Lebih Banyak...</span>
                    <span wire:loading wire:target="loadMore">Memuat...</span>
                </button>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-8 md:p-12 lg:p-16 text-center shadow-sm">
            <div class="text-4xl md:text-5xl mb-3">🛍️</div>
            <h3 class="text-base md:text-lg font-bold text-zinc-900 dark:text-white">Tidak ada produk ditemukan</h3>
            <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-xs md:max-w-md mx-auto">
                @if($search || $selectedCategory)
                    Coba ubah katakunci pencarian atau filter kategori kamu.
                @else
                    Mulai tambahkan produk pertamamu ke katalog untuk langsung membagikannya ke pembeli!
                @endif
            </p>
            <a href="{{ route('app.products.create') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-emerald-600 text-white font-bold text-xs md:text-sm rounded-xl shadow-md hover:bg-emerald-700 transition-colors">
                <span>➕</span>
                <span>Tambah Produk Sekarang</span>
            </a>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeleteId)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl max-w-xs md:max-w-sm w-full p-5 shadow-2xl border border-zinc-200 dark:border-zinc-800 text-center animate-scale-up">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto text-xl mb-3">
                    🗑️
                </div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Hapus Produk Ini?</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    Produk dan foto yang dihapus tidak dapat dikembalikan.
                </p>
                <div class="grid grid-cols-2 gap-2 mt-5">
                    <button wire:click="cancelDelete" 
                            class="py-2.5 px-3 rounded-xl border border-zinc-200 dark:border-zinc-700 text-xs font-bold text-zinc-700 dark:text-zinc-300">
                        Batal
                    </button>
                    <button wire:click="deleteProduct({{ $confirmingDeleteId }})" 
                            class="py-2.5 px-3 rounded-xl bg-red-600 text-white text-xs font-bold shadow-md hover:bg-red-700">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Category Manager Modal -->
    @if($showCategoryManager)
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
                    <button wire:click="closeCategoryManager" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-xl">
                        ✕
                    </button>
                </div>

                <!-- Add New Category Form -->
                <div class="py-4 border-b border-zinc-200 dark:border-zinc-800">
                    <label class="block text-xs font-bold mb-1 text-zinc-700 dark:text-zinc-300">Tambah Kategori Baru</label>
                    <div class="flex items-center gap-2">
                        <input type="text" wire:model="newCategoryName" wire:keydown.enter="addCategory"
                            placeholder="Contoh: Skincare, Serum, Makanan..."
                            class="flex-1 h-10 px-3.5 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-xs font-medium border border-zinc-200 dark:border-zinc-700 focus:outline-none focus:border-emerald-500 text-zinc-900 dark:text-white" />
                        <button wire:click="addCategory"
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
                                    <input type="text" wire:model="editingCategoryName" wire:keydown.enter="updateCategory"
                                        class="flex-1 h-9 px-3 rounded-lg bg-white dark:bg-zinc-900 text-xs font-medium border border-emerald-500 focus:outline-none text-zinc-900 dark:text-white" />
                                    <button wire:click="updateCategory" class="px-3 h-9 rounded-lg bg-emerald-600 text-white text-xs font-bold">Simpan</button>
                                    <button wire:click="cancelEditCategory" class="px-2.5 h-9 rounded-lg bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs">Batal</button>
                                </div>
                            @elseif($confirmingDeleteCategoryId === $cat->id)
                                <!-- Inline Delete Confirmation -->
                                <div class="flex-1 flex items-center justify-between bg-red-50 dark:bg-red-950/40 p-2 rounded-lg border border-red-200 dark:border-red-800">
                                    <span class="text-xs font-semibold text-red-700 dark:text-red-300">Hapus "{{ $cat->name }}"?</span>
                                    <div class="flex items-center gap-1.5">
                                        <button wire:click="deleteCategory({{ $cat->id }})" class="px-3 py-1 rounded-md bg-red-600 text-white text-xs font-bold">Ya, Hapus</button>
                                        <button wire:click="cancelDeleteCategory" class="px-2.5 py-1 rounded-md bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs">Batal</button>
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
                                    <button wire:click="startEditCategory({{ $cat->id }}, '{{ e($cat->name) }}')"
                                        class="p-1.5 text-zinc-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                                        title="Edit Kategori">
                                        ✏️
                                    </button>
                                    <button wire:click="confirmDeleteCategory({{ $cat->id }})"
                                        class="p-1.5 text-zinc-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                                        title="Hapus Kategori">
                                        🗑️
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
                    <button wire:click="closeCategoryManager" class="px-5 h-10 rounded-xl bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-bold">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
