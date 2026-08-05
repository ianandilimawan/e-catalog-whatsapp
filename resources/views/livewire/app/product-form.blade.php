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
        <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
            Foto Produk (Tap untuk Kamera/Galeri)
        </label>

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
                        <div class="text-2xl md:text-3xl mb-1">📷</div>
                        <span
                            class="text-[11px] md:text-xs font-bold text-emerald-700 dark:text-emerald-400 leading-tight">Foto
                            Utama</span>
                        <span class="text-[9px] text-zinc-400 dark:text-zinc-500">Wajib</span>
                    </label>
                @endif
                <input id="main_image_input" type="file" wire:model="image" accept="image/*" capture="environment"
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
                    capture="environment" class="hidden" />
            </label>
        </div>
        @error('image')
            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <!-- 2. Form Fields Card (2 Columns on Desktop) -->
    <div
        class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm">
        <div class="space-y-4 md:grid md:grid-cols-2 md:gap-5 md:space-y-0">
            <!-- Nama Produk -->
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                    Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="name" placeholder="Contoh: Kopi Susu Aren Gula Jawa"
                    class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 transition-colors" />
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="md:col-span-1">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <button type="button" @click="$wire.set('showCategoryModal', true)"
                        class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                        <span>+ Tambah Baru</span>
                    </button>
                </div>
                <select wire:model="category_id"
                    class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga -->
            <div class="md:col-span-1 md:mt-4">
                <label
                    class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
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

            <!-- Deskripsi (Full Width di Desktop) -->
            <div class="md:col-span-2 md:mt-4">
                <label
                    class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
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

    <!-- Quick Modal Add Category -->
    @if ($showCategoryModal)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl max-w-xs md:max-w-sm w-full p-5 shadow-2xl border border-zinc-200 dark:border-zinc-800 space-y-4 animate-scale-up">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tambah Kategori Baru</h3>
                <div>
                    <input type="text" wire:model="newCategoryName" placeholder="Nama Kategori (contoh: Minuman)"
                        class="w-full h-11 px-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-sm font-medium focus:outline-none focus:border-emerald-500" />
                    @error('newCategoryName')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="$set('showCategoryModal', false)"
                        class="py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 text-xs font-bold text-zinc-700 dark:text-zinc-300">
                        Batal
                    </button>
                    <button type="button" wire:click="saveCategory"
                        class="py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow-md hover:bg-emerald-700">
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    @endif
</form>
