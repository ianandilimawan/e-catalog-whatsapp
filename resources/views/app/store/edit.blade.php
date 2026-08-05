@extends('app.layouts.main')

@section('title', 'Pengaturan Toko')

@push('styles')
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
@endpush

@section('content')
    <form action="{{ route('app.store.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 pb-8"
        x-data="storeSettings('{{ $store->theme_color ?? '#16a34a' }}', '{{ $store->logo ? \App\Services\FileUploadService::getFileUrl($store->logo) : '' }}', '{{ $store->banner ? \App\Services\FileUploadService::getFileUrl($store->banner) : '' }}')">
        @csrf
        @method('PUT')

        <!-- Header Title -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white">Pengaturan Toko</h1>
                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400">Atur profil, branding, dan tampilan katalog HP
                    kamu.</p>
            </div>
            <a href="{{ route('catalog.show', $store->slug) }}" target="_blank"
                class="px-3.5 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs md:text-sm font-bold flex items-center gap-1.5 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                <span>🌐</span>
                <span>Preview Katalog</span>
            </a>
        </div>

        <!-- 1. Store Preview Card -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm">
            <!-- Banner Preview (Taller on Desktop) -->
            <div class="h-28 md:h-40 lg:h-48 bg-gradient-to-r from-emerald-600 to-teal-700 relative overflow-hidden group">
                <template x-if="bannerPreview">
                    <img :src="bannerPreview" class="w-full h-full object-cover" />
                </template>
                <template x-if="!bannerPreview && bannerSource">
                    <img :src="bannerSource" class="w-full h-full object-cover" />
                </template>

                <!-- Quick Banner Crop Button Overlay -->
                <button type="button" @click="openCropperForBanner()" x-show="bannerSource || bannerPreview"
                    class="absolute bottom-3 right-3 px-3 py-1.5 rounded-xl bg-black/60 hover:bg-black/80 text-white text-xs font-bold backdrop-blur-md transition-all flex items-center gap-1.5 shadow-md">
                    <span>Geser / Crop Banner</span>
                </button>
            </div>
            
            <!-- Store Profile Info Container -->
            <div class="px-4 md:px-6 pb-4 md:pb-5 relative">
                <!-- Logo Frame (Overlapping Banner) -->
                <div class="flex items-end justify-between">
                    <div class="-mt-8 md:-mt-10 mb-2 relative z-10">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl border-4 border-white dark:border-zinc-900 bg-white dark:bg-zinc-800 overflow-hidden shadow-md flex items-center justify-center font-bold text-xl md:text-2xl text-zinc-700 dark:text-zinc-300 relative group">
                            <template x-if="logoPreview">
                                <img :src="logoPreview" class="w-full h-full object-cover" />
                            </template>
                            <template x-if="!logoPreview && logoSource">
                                <img :src="logoSource" class="w-full h-full object-cover" />
                            </template>
                            <template x-if="!logoPreview && !logoSource">
                                <span>{{ strtoupper(substr($store->name, 0, 1)) }}</span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Store Text Details (High Contrast in Card Body) -->
                <div class="mt-1 min-w-0">
                    <h3 class="text-base md:text-xl font-bold text-zinc-900 dark:text-white leading-tight truncate">{{ $store->name }}</h3>
                    <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">wa.me/{{ $store->wa_number }}</p>
                </div>
            </div>
        </div>

        <!-- 2. Informasi Dasar Toko (2 Columns on Desktop) -->
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Info Dasar</h3>

            <div class="space-y-4 md:grid md:grid-cols-2 md:gap-5 md:space-y-0">
                <!-- Nama Toko -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Nama Toko <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $store->name) }}" required
                        class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium focus:outline-none focus:border-emerald-500" />
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No WhatsApp -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Nomor WhatsApp Toko <span
                            class="text-red-500">*</span></label>
                    <input type="tel" name="wa_number" value="{{ old('wa_number', $store->wa_number) }}"
                        placeholder="6281234567890" required
                        class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium focus:outline-none focus:border-emerald-500" />
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Gunakan kode negara (contoh:
                        6281234567890).</p>
                    @error('wa_number')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Welcome Message (Full Width di Desktop) -->
                <div class="md:col-span-2 md:mt-4">
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Pesan Selamat
                        Datang</label>
                    <textarea name="welcome_message" rows="3" placeholder="Halo! Selamat datang di katalog digital kami..."
                        class="w-full p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-normal focus:outline-none focus:border-emerald-500">{{ old('welcome_message', $store->welcome_message) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 3. Branding & Tampilan -->
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Branding & Visual</h3>

            <!-- Logo & Banner File Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                <!-- Input Logo -->
                <div
                    class="bg-zinc-50 dark:bg-zinc-800/50 p-3.5 rounded-xl border border-zinc-200/80 dark:border-zinc-700/80 space-y-2">
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300">Logo Toko (Rasio 1:1)</label>
                    <input type="file" x-ref="logoInput" name="logo" accept="image/*" @change="onLogoSelect($event)"
                        class="block w-full text-xs text-zinc-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-400 cursor-pointer" />

                    <button type="button" @click="openCropperForLogo()" x-show="logoSource || logoPreview"
                        class="w-full py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-xs font-bold hover:bg-emerald-100 dark:hover:bg-emerald-900 transition-colors flex items-center justify-center gap-1.5">
                        {{-- <span>✂️</span> --}}
                        <span>Geser & Crop Logo (1:1)</span>
                    </button>
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500">Rekomendasi logo berbentuk persegi 1:1.</p>
                </div>

                <!-- Input Banner -->
                <div
                    class="bg-zinc-50 dark:bg-zinc-800/50 p-3.5 rounded-xl border border-zinc-200/80 dark:border-zinc-700/80 space-y-2">
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300">Banner Toko (Rasio 3:1)</label>
                    <input type="file" x-ref="bannerInput" name="banner" accept="image/*"
                        @change="onBannerSelect($event)"
                        class="block w-full text-xs text-zinc-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-400 cursor-pointer" />

                    <button type="button" @click="openCropperForBanner()" x-show="bannerSource || bannerPreview"
                        class="w-full py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-xs font-bold hover:bg-emerald-100 dark:hover:bg-emerald-900 transition-colors flex items-center justify-center gap-1.5">
                        {{-- <span>✂️</span> --}}
                        <span>Geser & Crop Banner (3:1)</span>
                    </button>
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500">Bisa digeser dan dicrop sesuai rasio banner.</p>
                </div>
            </div>

            <!-- Color Theme Presets -->
            <div>
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-2">Tema Warna Toko</label>
                <input type="hidden" name="theme_color" :value="currentColor" />
                <div class="flex items-center gap-2.5 overflow-x-auto py-1">
                    @php
                        $presets = ['#16a34a', '#2563eb', '#7c3aed', '#e11d48', '#d97706', '#0891b2', '#18181b'];
                    @endphp
                    @foreach ($presets as $hex)
                        <button type="button" @click="currentColor = '{{ $hex }}'"
                            class="w-8 h-8 rounded-full border-2 transition-transform"
                            :class="currentColor === '{{ $hex }}' ?
                                'border-black dark:border-white scale-110 shadow-md' : 'border-transparent opacity-80'"
                            style="background-color: {{ $hex }}">
                        </button>
                    @endforeach
                    <!-- Custom Color Picker -->
                    <label
                        class="w-8 h-8 rounded-full border-2 border-dashed border-zinc-400 flex items-center justify-center cursor-pointer relative overflow-hidden"
                        title="Custom Warna">
                        <span class="text-xs">🎨</span>
                        <input type="color" x-model="currentColor" class="absolute inset-0 opacity-0 cursor-pointer" />
                    </label>
                </div>
            </div>

            <!-- Toggles -->
            <div class="space-y-3 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="text-xs md:text-sm font-bold text-zinc-700 dark:text-zinc-300">Tombol Bulat
                        (Rounded)</span>
                    <input type="checkbox" name="button_rounded" value="1"
                        {{ $store->button_rounded ? 'checked' : '' }}
                        class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" />
                </label>

                <label class="flex items-center justify-between cursor-pointer">
                    <span class="text-xs md:text-sm font-bold text-zinc-700 dark:text-zinc-300">Dark Mode Secara
                        Default</span>
                    <input type="checkbox" name="dark_mode" value="1" {{ $store->dark_mode ? 'checked' : '' }}
                        class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" />
                </label>
            </div>
        </div>

        <!-- 4. Tracking & SEO Section -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
            <div>
                <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tracking & SEO</h3>
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-0.5">Hubungkan katalog kamu ke Google Analytics & Meta Pixel untuk tracking pengunjung.</p>
            </div>

            <!-- Google Analytics ID -->
            <div>
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Google Analytics ID
                </label>
                <input type="text" name="google_analytics_id"
                       value="{{ old('google_analytics_id', $store->google_analytics_id) }}"
                       placeholder="G-XXXXXXXXXX"
                       class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-mono font-medium focus:outline-none focus:border-emerald-500" />
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Contoh: G-AB1CD2EF3G. Dapatkan di <span class="font-semibold">analytics.google.com</span></p>
                @error('google_analytics_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Meta Pixel ID -->
            <div>
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Meta (Facebook) Pixel ID
                </label>
                <input type="text" name="meta_pixel_id"
                       value="{{ old('meta_pixel_id', $store->meta_pixel_id) }}"
                       placeholder="123456789012345"
                       class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-mono font-medium focus:outline-none focus:border-emerald-500" />
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Contoh: 123456789012345. Dapatkan di <span class="font-semibold">business.facebook.com</span></p>
                @error('meta_pixel_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Google Search Console -->
            <div>
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Kode Verifikasi Google Search Console
                </label>
                <input type="text" name="google_search_console_code"
                       value="{{ old('google_search_console_code', $store->google_search_console_code) }}"
                       placeholder="abc123def456..."
                       class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-mono font-medium focus:outline-none focus:border-emerald-500" />
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Isi bagian <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded text-[10px]">content="..."</code> dari meta tag verifikasi.</p>
                @error('google_search_console_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-zinc-100 dark:border-zinc-800 pt-4 space-y-4">
                <h4 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">SEO & Tampilan</h4>

                <!-- SEO Title -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Judul SEO (Custom Title)
                    </label>
                    <input type="text" name="seo_title"
                           value="{{ old('seo_title', $store->seo_title) }}"
                           placeholder="{{ $store->name }} - Katalogin"
                           maxlength="120"
                           class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium focus:outline-none focus:border-emerald-500" />
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Muncul di tab browser & hasil pencarian Google. Maks 120 karakter.</p>
                </div>

                <!-- SEO Description -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Deskripsi SEO
                    </label>
                    <textarea name="seo_description" rows="2"
                              placeholder="Pesan langsung kopi terbaik di Bandung via WhatsApp..."
                              maxlength="300"
                              class="w-full p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-normal focus:outline-none focus:border-emerald-500">{{ old('seo_description', $store->seo_description) }}</textarea>
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Muncul di bawah judul di Google. Maks 300 karakter.</p>
                </div>

                <!-- CTA Button Text -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Teks Tombol Pesan
                    </label>
                    <input type="text" name="cta_button_text"
                           value="{{ old('cta_button_text', $store->cta_button_text) }}"
                           placeholder="+ Add"
                           maxlength="50"
                           class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium focus:outline-none focus:border-emerald-500" />
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Contoh: "Pesan Sekarang", "Booking", "Hubungi Kami". Kosongkan untuk default "+ Add".</p>
                </div>
            </div>
        </div>

        <!-- 5. Share Section -->
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-3">
            <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Link Katalog Toko</h3>
            @php
                $catalogUrl = route('catalog.show', $store->slug);
            @endphp
            <div class="flex items-center gap-2">
                <input type="text" readonly value="{{ $catalogUrl }}"
                    class="flex-1 h-11 px-3.5 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-xs font-mono text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700" />
                <button type="button" @click="copyLink('{{ $catalogUrl }}')"
                    class="px-4 h-11 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow-md hover:bg-emerald-700 transition-colors">
                    Salin
                </button>
            </div>
        </div>

        <!-- 5. Sticky Save Bar (Container Width Scaled) -->
        <div
            class="fixed bottom-[64px] lg:bottom-0 inset-x-0 z-30 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-lg border-t border-zinc-200 dark:border-zinc-800 py-3 shadow-lg">
            <div class="px-4 md:px-6 lg:px-8 max-w-md md:max-w-2xl lg:max-w-5xl xl:max-w-6xl mx-auto flex justify-end">
                <button type="submit"
                    class="w-full md:w-auto md:px-16 h-[48px] rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white font-bold text-base shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition-all">
                    💾 Simpan Pengaturan Toko
                </button>
            </div>
        </div>

        <!-- Cropper Modal -->
        <template x-teleport="body">
            <div x-show="showCropper" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
                @keydown.escape.window="closeCropper()">
                <div
                    class="bg-white dark:bg-zinc-900 rounded-3xl max-w-3xl w-full p-5 md:p-6 shadow-2xl border border-zinc-200 dark:border-zinc-800 flex flex-col max-h-[92vh]">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-800">
                        <div>
                            <h3
                                class="text-base md:text-lg font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                                {{-- <span>✂️</span> --}}
                                <span
                                    x-text="cropType === 'banner' ? 'Geser & Crop Banner Toko (3:1)' : 'Geser & Crop Logo Toko (1:1)'"></span>
                            </h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Geser atau perbesar gambar agar sesuai
                                dengan tampilan yang diinginkan.</p>
                        </div>
                        <button type="button" @click="closeCropper()"
                            class="p-2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-xl">
                            ✕
                        </button>
                    </div>

                    <!-- Cropper Canvas Container -->
                    <div
                        class="my-4 flex-1 overflow-hidden min-h-[300px] md:min-h-[420px] max-h-[60vh] bg-zinc-950 rounded-2xl flex items-center justify-center relative shadow-inner">
                        <img x-ref="cropperImg" :src="cropImageSrc" class="max-w-full max-h-full block"
                            alt="Crop Target">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-between pt-3 border-t border-zinc-200 dark:border-zinc-800">
                        <span class="text-xs text-zinc-500 dark:text-zinc-400 hidden sm:inline">
                            💡 Geser gambar atau scroll mouse untuk Zoom
                        </span>
                        <div class="flex items-center gap-3 ml-auto">
                            <button type="button" @click="closeCropper()"
                                class="px-4 py-2 text-xs font-bold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="button" @click="applyCrop()"
                                class="px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-1.5">
                                <span>✓</span>
                                <span>Gunakan Posisi Ini</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </form>

    <script>
        function storeSettings(defaultColor, initialLogoUrl, initialBannerUrl) {
            return {
                currentColor: defaultColor,
                logoSource: initialLogoUrl || null,
                bannerSource: initialBannerUrl || null,
                logoPreview: null,
                bannerPreview: null,
                rawLogoFile: null,
                rawBannerFile: null,

                // Cropper state
                showCropper: false,
                cropType: 'banner', // 'logo' or 'banner'
                cropper: null,
                cropImageSrc: '',

                onLogoSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.rawLogoFile = file;
                        this.openCropperForLogo(file);
                    }
                },

                onBannerSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.rawBannerFile = file;
                        this.openCropperForBanner(file);
                    }
                },

                openCropperForLogo(fileSource = null) {
                    this.cropType = 'logo';
                    let source = fileSource || this.rawLogoFile || this.logoSource;
                    this.loadAndInitCropper(source);
                },

                openCropperForBanner(fileSource = null) {
                    this.cropType = 'banner';
                    let source = fileSource || this.rawBannerFile || this.bannerSource;
                    this.loadAndInitCropper(source);
                },

                loadAndInitCropper(source) {
                    if (!source) return;

                    if (source instanceof File || source instanceof Blob) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.cropImageSrc = e.target.result;
                            this.startCropperInstance();
                        };
                        reader.readAsDataURL(source);
                    } else if (typeof source === 'string') {
                        this.cropImageSrc = source;
                        this.startCropperInstance();
                    }
                },

                startCropperInstance() {
                    this.showCropper = true;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }

                    setTimeout(() => {
                        const img = this.$refs.cropperImg;
                        if (!img) return;

                        if (this.cropper) {
                            this.cropper.destroy();
                            this.cropper = null;
                        }

                        const targetRatio = this.cropType === 'banner' ? (3 / 1) : (1 / 1);

                        this.cropper = new Cropper(img, {
                            aspectRatio: targetRatio,
                            viewMode: 1,
                            autoCropArea: 0.95,
                            responsive: true,
                            restore: false,
                            background: false
                        });
                    }, 150);
                },

                applyCrop() {
                    if (!this.cropper) return;

                    const canvas = this.cropper.getCroppedCanvas({
                        maxWidth: 2000,
                        maxHeight: 2000
                    });

                    canvas.toBlob((blob) => {
                        if (!blob) return;

                        const originalName = this.cropType === 'banner' ?
                            (this.rawBannerFile ? this.rawBannerFile.name : 'banner.jpg') :
                            (this.rawLogoFile ? this.rawLogoFile.name : 'logo.jpg');

                        const fileName = 'cropped-' + originalName;
                        const mimeType = blob.type || 'image/jpeg';
                        const croppedFile = new File([blob], fileName, {
                            type: mimeType
                        });

                        const previewUrl = URL.createObjectURL(blob);

                        if (this.cropType === 'banner') {
                            this.bannerPreview = previewUrl;
                            try {
                                const dt = new DataTransfer();
                                dt.items.add(croppedFile);
                                this.$refs.bannerInput.files = dt.files;
                            } catch (e) {
                                console.warn('DataTransfer sync banner:', e);
                            }
                        } else {
                            this.logoPreview = previewUrl;
                            try {
                                const dt = new DataTransfer();
                                dt.items.add(croppedFile);
                                this.$refs.logoInput.files = dt.files;
                            } catch (e) {
                                console.warn('DataTransfer sync logo:', e);
                            }
                        }

                        this.closeCropper();
                        if (typeof showAppToast === 'function') {
                            showAppToast('Posisi ' + (this.cropType === 'banner' ? 'Banner' : 'Logo') +
                                ' diperbarui!');
                        }
                    }, 'image/jpeg', 0.92);
                },

                closeCropper() {
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                    this.showCropper = false;
                    this.cropImageSrc = '';
                },

                copyLink(url) {
                    navigator.clipboard.writeText(url).then(() => {
                        showAppToast('Link katalog disalin ke clipboard!', 'success');
                    });
                }
            }
        }
    </script>
@endsection
