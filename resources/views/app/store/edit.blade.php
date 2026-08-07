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
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8M11.5 3a17 17 0 000 18M12.5 3a17 17 0 010 18" />
                </svg>
                <span>Preview Katalog</span>
            </a>
        </div>

        <!-- 1. Store Preview Card -->
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm">
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
                        <div
                            class="w-16 h-16 md:w-20 md:h-20 rounded-2xl border-4 border-white dark:border-zinc-900 bg-white dark:bg-zinc-800 overflow-hidden shadow-md flex items-center justify-center font-bold text-xl md:text-2xl text-zinc-700 dark:text-zinc-300 relative group">
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
                    <h3 class="text-base md:text-xl font-bold text-zinc-900 dark:text-white leading-tight truncate">
                        {{ $store->name }}</h3>
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
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300">Logo Toko (Persegi 1:1)</label>
                    <input type="file" x-ref="logoInput" name="logo" accept="image/*" @change="onLogoSelect($event)"
                        class="block w-full text-xs text-zinc-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-400 cursor-pointer" />

                    <button type="button" @click="openCropperForLogo()" x-show="logoSource || logoPreview"
                        class="w-full py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-xs font-bold hover:bg-emerald-100 dark:hover:bg-emerald-900 transition-colors flex items-center justify-center gap-1.5">
                        <span>Geser & Crop Logo (1:1)</span>
                    </button>
                    <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                        Resolusi disarankan: <strong class="text-zinc-700 dark:text-zinc-300 font-semibold">500 x 500 px</strong> (Format PNG/JPG/WEBP, Maks. 5MB).
                    </p>
                </div>

                <!-- Input Banner -->
                <div
                    class="bg-zinc-50 dark:bg-zinc-800/50 p-3.5 rounded-xl border border-zinc-200/80 dark:border-zinc-700/80 space-y-2">
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300">Banner Toko (Lansekap 3:1)</label>
                    <input type="file" x-ref="bannerInput" name="banner" accept="image/*"
                        @change="onBannerSelect($event)"
                        class="block w-full text-xs text-zinc-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-400 cursor-pointer" />

                    <button type="button" @click="openCropperForBanner()" x-show="bannerSource || bannerPreview"
                        class="w-full py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-xs font-bold hover:bg-emerald-100 dark:hover:bg-emerald-900 transition-colors flex items-center justify-center gap-1.5">
                        <span>Geser & Crop Banner (3:1)</span>
                    </button>
                    <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                        Resolusi disarankan: <strong class="text-zinc-700 dark:text-zinc-300 font-semibold">1200 x 400 px</strong> (Format PNG/JPG/WEBP, Maks. 5MB).
                    </p>
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
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
            <div>
                <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tracking & SEO</h3>
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-0.5">Hubungkan katalog kamu ke Google Analytics &
                    Meta Pixel untuk tracking pengunjung.</p>
            </div>

            <!-- Google Analytics ID -->
            <div>
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Google Analytics ID
                </label>
                <input type="text" name="google_analytics_id"
                    value="{{ old('google_analytics_id', $store->google_analytics_id) }}" placeholder="G-XXXXXXXXXX"
                    class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-mono font-medium focus:outline-none focus:border-emerald-500" />
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Contoh: G-AB1CD2EF3G. Dapatkan di <span
                        class="font-semibold">analytics.google.com</span></p>
                @error('google_analytics_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Pixel ID -->
            <div>
                <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Meta (Facebook) Pixel ID
                </label>
                <input type="text" name="meta_pixel_id" value="{{ old('meta_pixel_id', $store->meta_pixel_id) }}"
                    placeholder="123456789012345"
                    class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-mono font-medium focus:outline-none focus:border-emerald-500" />
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Contoh: 123456789012345. Dapatkan di <span
                        class="font-semibold">business.facebook.com</span></p>
                @error('meta_pixel_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
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
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Isi bagian <code
                        class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded text-[10px]">content="..."</code> dari meta tag
                    verifikasi.</p>
                @error('google_search_console_code')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-zinc-100 dark:border-zinc-800 pt-4 space-y-4">
                <h4 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">SEO & Tampilan</h4>

                <!-- SEO Title -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Judul SEO (Custom Title)
                    </label>
                    <input type="text" name="seo_title" value="{{ old('seo_title', $store->seo_title) }}"
                        placeholder="{{ $store->name }} - Katalogin" maxlength="120"
                        class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium focus:outline-none focus:border-emerald-500" />
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Muncul di tab browser & hasil pencarian
                        Google. Maks 120 karakter.</p>
                </div>

                <!-- SEO Description -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Deskripsi SEO
                    </label>
                    <textarea name="seo_description" rows="2" placeholder="Pesan langsung kopi terbaik di Bandung via WhatsApp..."
                        maxlength="300"
                        class="w-full p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-normal focus:outline-none focus:border-emerald-500">{{ old('seo_description', $store->seo_description) }}</textarea>
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Muncul di bawah judul di Google. Maks 300
                        karakter.</p>
                </div>

                <!-- CTA Button Text -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Teks Tombol Pesan (WhatsApp CTA)
                    </label>
                    <input type="text" name="cta_button_text"
                        value="{{ old('cta_button_text', $store->cta_button_text) }}"
                        placeholder="Contoh: Pesan Sekarang / Order via WA"
                        class="w-full h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-base font-medium text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-emerald-500 transition-colors" />
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Teks yang akan muncul di tombol aksi pemesanan katalog pembeli.</p>
                    @error('cta_button_text')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 5. Link & QR Code Toko Section -->
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
                    <div>
                        <h3 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Link & QR
                            Code Katalog Toko</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Bagikan link atau cetak QR Code toko
                            Anda untuk ditempel di meja/kasir.</p>
                    </div>

                    @php
                        $catalogUrl = route('catalog.show', $store->slug);
                        $qrCodeUrl =
                            'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($catalogUrl);
                        $logoUrl = $store->logo ? \App\Services\FileUploadService::getFileUrl($store->logo) : '';
                    @endphp

                    <!-- Link Box -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300">Link Katalog Public</label>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                            <input type="text" readonly value="{{ $catalogUrl }}"
                                class="w-full flex-1 h-12 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-xs sm:text-sm font-mono text-zinc-800 dark:text-zinc-200 focus:outline-none" />
                            <div class="grid grid-cols-2 sm:flex items-center gap-2">
                                <button type="button" @click="copyLink('{{ $catalogUrl }}')"
                                    class="h-12 px-5 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow-md hover:bg-emerald-700 active:scale-98 transition-all flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 002-2h2a2 2 0 002-2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                        </path>
                                    </svg>
                                    <span>Salin Link</span>
                                </button>
                                <a href="{{ $catalogUrl }}" target="_blank"
                                    class="h-12 px-5 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-bold border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 active:scale-98 transition-all flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <span>Buka</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Card Box -->
                    <div
                        class="pt-4 border-t border-zinc-200/80 dark:border-zinc-800 flex flex-col md:flex-row items-center gap-5">
                        <div
                            class="w-36 h-36 bg-white p-2.5 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm flex-shrink-0 flex items-center justify-center relative">
                            <img src="{{ $qrCodeUrl }}" alt="QR Code {{ $store->name }}"
                                class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1 w-full text-center md:text-left space-y-2.5">
                            <h4 class="text-sm font-bold text-zinc-900 dark:text-white">Cetak QR Code / Poster Standee Toko
                            </h4>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                                Cetak QR Code ini dan letakkan di meja kasir, flyer, atau banner toko Anda agar pembeli bisa
                                langsung melakukan scan dan memilih produk lewat WhatsApp.
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1 max-w-md mx-auto md:mx-0">
                                <button type="button"
                                    @click="downloadQrCode('{{ $qrCodeUrl }}', 'QR-Code-{{ $store->slug }}.png')"
                                    class="h-12 px-4 rounded-xl bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-bold shadow hover:bg-zinc-800 dark:hover:bg-zinc-200 active:scale-98 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    <span>Unduh Gambar QR</span>
                                </button>
                                <button type="button"
                                    @click="printQrCard('{{ e($store->name) }}', '{{ $logoUrl }}', '{{ $qrCodeUrl }}', '{{ $catalogUrl }}')"
                                    class="h-12 px-4 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow hover:bg-emerald-700 active:scale-98 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    <span>Cetak Poster QR</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Sticky Save Bar (Container Width Scaled) -->
                <div
                    class="fixed bottom-[64px] lg:bottom-0 inset-x-0 z-30 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-lg border-t border-zinc-200 dark:border-zinc-800 py-3 shadow-lg">
                    <div
                        class="px-4 md:px-6 lg:px-8 max-w-md md:max-w-2xl lg:max-w-5xl xl:max-w-6xl mx-auto flex justify-end">
                        <button type="submit"
                            class="w-full md:w-auto md:px-16 h-[48px] rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white font-bold text-base shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Simpan Pengaturan Toko</span>
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
                            <div
                                class="flex items-center justify-between pb-3 border-b border-zinc-200 dark:border-zinc-800">
                                <div>
                                    <h3
                                        class="text-base md:text-lg font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                                        <span
                                            x-text="cropType === 'banner' ? 'Geser & Crop Banner Toko (1200 x 400 px / Rasio 3:1)' : 'Geser & Crop Logo Toko (500 x 500 px / Rasio 1:1)'"></span>
                                    </h3>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Geser atau perbesar gambar agar
                                        sesuai
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
                                    alt="To Crop">
                            </div>

                            <!-- Cropper Toolbar Controls -->
                            <div class="flex items-center justify-between gap-2 px-1 pt-3 text-xs text-zinc-500 dark:text-zinc-400 border-t border-zinc-200 dark:border-zinc-800">
                                <span class="hidden sm:inline">Zoom: Scroll / Cubit layar</span>
                                <div class="flex items-center gap-1.5 ml-auto">
                                    <button type="button" @click="cropper?.zoom(0.1)" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 font-bold text-xs flex items-center gap-1">
                                        <span>+ Zoom</span>
                                    </button>
                                    <button type="button" @click="cropper?.zoom(-0.1)" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 font-bold text-xs flex items-center gap-1">
                                        <span>- Zoom</span>
                                    </button>
                                    <button type="button" @click="cropper?.rotate(90)" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 font-bold text-xs flex items-center gap-1">
                                        <span>↻ Putar</span>
                                    </button>
                                    <button type="button" @click="cropper?.reset()" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 font-bold text-xs">
                                        Reset
                                    </button>
                                </div>
                            </div>

                            <!-- Modal Action Footer -->
                            <div
                                class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-200 dark:border-zinc-800">
                                <button type="button" @click="closeCropper()"
                                    class="px-5 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold text-sm hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                                    Batal
                                </button>
                                <button type="button" @click="applyCrop()"
                                    class="px-6 h-11 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-600/30 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 0L4 4m5.121 5.121L4 14.121" />
                                    </svg>
                                    <span>Gunakan Hasil Crop</span>
                                </button>
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
                            dragMode: 'move',
                            autoCropArea: 1,
                            responsive: true,
                            restore: false,
                            background: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                    }, 150);
                },

                applyCrop() {
                    if (!this.cropper) return;

                    const outputWidth = this.cropType === 'banner' ? 1200 : 500;
                    const outputHeight = this.cropType === 'banner' ? 400 : 500;

                    const canvas = this.cropper.getCroppedCanvas({
                        width: outputWidth,
                        height: outputHeight,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
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
                        if (typeof showAppToast === 'function') {
                            showAppToast('Link katalog disalin ke clipboard!', 'success');
                        }
                    });
                },

                downloadQrCode(qrUrl, filename) {
                    fetch(qrUrl)
                        .then(response => response.blob())
                        .then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            if (typeof showAppToast === 'function') {
                                showAppToast('QR Code berhasil diunduh!', 'success');
                            }
                        })
                        .catch(() => alert('Gagal mengunduh QR Code. Silakan coba lagi.'));
                },

                printQrCard(storeName, logoUrl, qrUrl, catalogUrl) {
                    const printWindow = window.open('', '_blank', 'width=700,height=900');
                    const logoHtml = logoUrl ?
                        `<img src="${logoUrl}" style="width:70px;height:70px;border-radius:50%;object-fit:cover;margin:0 auto 12px;border:3px solid #10b981;box-shadow:0 2px 8px rgba(0,0,0,0.1)">` :
                        '';

                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Cetak QR Code - ${storeName}</title>
                            <style>
                                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&display=swap');
                                body {
                                    font-family: 'Plus Jakarta Sans', sans-serif;
                                    background: #f8fafc;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    min-height: 100vh;
                                    margin: 0;
                                    padding: 20px;
                                    box-sizing: border-box;
                                }
                                .card {
                                    background: #ffffff;
                                    width: 100%;
                                    max-width: 420px;
                                    border-radius: 28px;
                                    padding: 40px 32px;
                                    text-align: center;
                                    border: 2px solid #e2e8f0;
                                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
                                }
                                .title {
                                    font-size: 24px;
                                    font-weight: 800;
                                    color: #0f172a;
                                    margin: 0 0 20px;
                                }
                                .subtitle {
                                    font-size: 13px;
                                    font-weight: 600;
                                    color: #64748b;
                                    margin: 0 0 24px;
                                }
                                .qr-box {
                                    background: #ffffff;
                                    padding: 16px;
                                    border-radius: 20px;
                                    border: 2px solid #10b981;
                                    display: inline-block;
                                    margin-bottom: 24px;
                                }
                                .qr-box img {
                                    width: 240px;
                                    height: 240px;
                                    display: block;
                                }
                                .instruction {
                                    background: #ecfdf5;
                                    color: #065f46;
                                    padding: 12px 16px;
                                    border-radius: 16px;
                                    font-size: 12px;
                                    font-weight: 700;
                                    margin-bottom: 20px;
                                }
                                .footer {
                                    font-size: 11px;
                                    color: #94a3b8;
                                    font-weight: 600;
                                }
                                @media print {
                                    body { background: white; padding: 0; }
                                    .card { border: none; box-shadow: none; width: 100%; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="card">
                                ${logoHtml}
                                <h1 class="title">${storeName}</h1>
                                <div class="qr-box">
                                    <img src="${qrUrl}" alt="QR Code">
                                </div>
                                <div class="instruction">
                                    Scan QR Code untuk buka Katalog & Pesan via WA
                                </div>
                                <p class="footer">Powered by Katalogin</p>
                            </div>
                            <script>
                                window.onload = () => {
                                    setTimeout(() => {
                                        window.print();
                                    }, 400);
                                };
                            <\/script>
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                }
            }
        }
    </script>
@endsection
