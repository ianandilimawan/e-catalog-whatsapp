@if (auth()->user() &&
        auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin']))
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
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <div>
                <x-select name="user_id" label="Assign Store to User" value="{{ $store->user_id ?? '' }}"
                    :options="$users" />
            </div>
            <div class="pt-2">
                <x-toggle name="is_active" label="Store Active Status" :checked="$store->is_active ?? true" />
                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">If inactive, this store's catalog cannot be
                    accessed publicly.</p>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 gap-8 mb-4">
    <!-- Store Profile Section -->
    <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 lg:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Store Profile</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Basic information about your store.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <x-input-floating type="text" name="name" label="Store Name" value="{{ $store->name ?? '' }}" />
            </div>
            <input type="hidden" name="slug" value="{{ $store->slug ?? '' }}">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <x-input-floating type="text" name="wa_number" label="WhatsApp Number (e.g. 628...)"
                value="{{ $store->wa_number ?? '' }}" />
        </div>
        <x-textarea-floating name="welcome_message" label="Welcome Message (Displayed on Catalog)"
            value="{{ $store->welcome_message ?? '' }}" />
    </div>

    <!-- Design & Branding Section -->
    <div class="bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 lg:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2.5 bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Design & Branding</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Customize how your store looks to customers.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <x-filepond name="logo" label="Store Logo" aspectRatio="1:1" :defaultFile="isset($fileUrls['logo']) ? $fileUrls['logo'] : null" />
                <p class="text-xs text-gray-500 mt-2"><i class="fa fa-info-circle mr-1"></i> Rekomendasi: Gunakan gambar
                    persegi (rasio 1:1) misal 500x500px.</p>
            </div>
            <div>
                <x-filepond name="banner" label="Store Banner" aspectRatio="3:1" :defaultFile="isset($fileUrls['banner']) ? $fileUrls['banner'] : null" />
                <p class="text-xs text-gray-500 mt-2"><i class="fa fa-info-circle mr-1"></i> Rekomendasi: Gunakan gambar
                    persegi panjang (rasio 3:1) misal 1200x400px. Bisa digeser untuk mengatur posisi.</p>
            </div>
        </div>

        <div
            class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="col-span-1">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Theme Color</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="theme_color"
                        value="{{ isset($store->theme_color) && str_starts_with($store->theme_color, '#') ? $store->theme_color : '#10b981' }}"
                        class="h-10 w-full max-w-[80px] rounded-lg cursor-pointer border-0 p-0 bg-transparent shadow-sm ring-1 ring-inset ring-gray-200 dark:ring-gray-700">
                </div>
                <p class="text-xs text-gray-500 mt-2">Pick your main brand color.</p>
            </div>
            <div
                class="col-span-1 flex flex-col justify-center border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-700 md:pl-6 pt-4 md:pt-0">
                <x-toggle name="button_rounded" label="Rounded Buttons" :checked="$store->button_rounded ?? false" />
                <p class="text-xs text-gray-500 mt-2">Make buttons pill-shaped.</p>
            </div>
            <div
                class="col-span-1 flex flex-col justify-center border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-700 md:pl-6 pt-4 md:pt-0">
                <x-toggle name="dark_mode" label="Default Dark Mode" :checked="$store->dark_mode ?? false" />
                <p class="text-xs text-gray-500 mt-2">Force catalog to dark mode.</p>
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
        $textareaFields = [
            0 => 'welcome_message',
        ];
        $selectFields = [
            0 => 'user_id',
        ];
        $currencyFields = [];
        $passwordFields = [];
    @endphp
    @include('admin.partials.form-scripts')
@endpush
