<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }} - {{ config('app.name', 'Katalogin') }}</title>
    <meta name="description" content="{{ strip_tags($store->welcome_message) }}">
    <meta property="og:title" content="{{ $store->name }} - {{ config('app.name', 'Katalogin') }}">
    <meta property="og:description" content="{{ strip_tags($store->welcome_message) }}">
    @if ($store->logo)
        <meta property="og:image" content="{{ str_starts_with($store->logo, 'http') ? $store->logo : Storage::url($store->logo) }}">
    @endif
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <!-- Tailwind CSS (CDN for quick demo, replace with Vite in prod) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Dynamic CSS based on Store Settings -->
    @php
        $themeColor = $store->theme_color ?? '#10b981';
        // Basic validation: if it doesn't look like a hex or valid color name, fallback
if (
    $themeColor === 'dark' ||
    $themeColor === 'light' ||
    !preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$|^[a-zA-Z]+$/', $themeColor)
) {
    $themeColor = '#10b981';
        }
    @endphp
    <style>
        :root {
            --primary-color: {{ $themeColor }};
        }

        body {
            background-color: {{ $store->dark_mode ? '#111827' : '#f3f4f6' }};
            /* gray-900 / gray-100 */
            color: {{ $store->dark_mode ? '#f9fafb' : '#1f2937' }};
            /* gray-50 / gray-800 */
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .btn-custom {
            border-radius: {{ $store->button_rounded ? '9999px' : '0.375rem' }} !important;
        }

        .card-custom {
            border-radius: {{ $store->button_rounded ? '1rem' : '0.375rem' }} !important;
            background-color: {{ $store->dark_mode ? '#1f2937' : '#ffffff' }};
            border-color: {{ $store->dark_mode ? '#374151' : '#e5e7eb' }};
        }
    </style>
</head>

<body class="antialiased min-h-screen flex flex-col justify-between" x-data="catalogApp()" :class="{'overflow-hidden': isProductModalOpen || isCheckoutModalOpen}">

    <!-- Banner (Scrollable) -->
    @if ($store->banner)
        <div class="w-full aspect-[3/1] max-h-[380px] bg-cover bg-center shadow-sm"
            style="background-image: url('{{ str_starts_with($store->banner, 'http') ? $store->banner : Storage::url($store->banner) }}');">
        </div>
    @endif

    <!-- Header Info (Sticky) -->
    <header class="bg-primary text-white sticky top-0 z-50 shadow-md">
        <div class="p-4 flex items-center gap-4">
            @if ($store->logo)
                <img src="{{ str_starts_with($store->logo, 'http') ? $store->logo : Storage::url($store->logo) }}"
                    alt="Logo" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow">
            @else
                <div
                    class="w-12 h-12 rounded-full bg-white text-primary flex items-center justify-center font-bold text-xl shadow">
                    {{ substr($store->name, 0, 1) }}
                </div>
            @endif

            <div>
                <h1 class="text-xl font-bold">{{ $store->name }}</h1>
                @if ($store->welcome_message)
                    <p class="text-sm opacity-90 truncate max-w-[250px]">{{ htmlspecialchars_decode($store->welcome_message, ENT_QUOTES) }}</p>
                @endif
                <p class="text-xs opacity-75 mt-0.5">{{ $products->total() }} Produk</p>
            </div>

            @php
                $cleanWa = preg_replace('/\D/', '', $store->wa_number);
                if(str_starts_with($cleanWa, '0')) $cleanWa = '62' . substr($cleanWa, 1);
            @endphp

        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl w-full mx-auto p-4 flex-1 flex flex-col" :class="totalItems > 0 ? 'pb-20' : 'pb-4'">

        <!-- Category Filter -->
        <div class="flex overflow-x-auto gap-2 py-4 no-scrollbar" x-show="allProducts.length > 0" style="display: none;">
            <button @click="selectedCategory = 'all'"
                :class="selectedCategory === 'all' ? 'bg-primary text-white' : 'card-custom border'"
                class="px-4 py-2 rounded-full whitespace-nowrap text-sm font-semibold transition">
                Semua
            </button>
            @foreach ($categories as $category)
                <button @click="selectedCategory = {{ $category->id }}"
                    :class="selectedCategory == {{ $category->id }} ? 'bg-primary text-white' : 'card-custom border'"
                    class="px-4 py-2 rounded-full whitespace-nowrap text-sm font-semibold transition">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- Product Grid -->
        <div x-show="allProducts.length === 0" style="display: none;" class="flex-1 flex flex-col items-center justify-center py-16 text-center px-4 my-auto">
            <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 opacity-50">
                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-lg font-bold mb-2">Belum Ada Produk</h3>
            <p class="text-sm opacity-70">Toko ini belum menambahkan produk apapun ke dalam katalognya.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4" x-show="allProducts.length > 0">
            <template x-for="product in allProducts" :key="product.id">
                <div class="card-custom border rounded-lg overflow-hidden flex flex-col shadow-sm h-full"
                    x-show="selectedCategory === 'all' || selectedCategory == product.category_id">
                    <div class="relative w-full pt-[100%] bg-gray-200 cursor-pointer"
                        @click="openProductDetail(product.id, product.name, product.price, product.description, product.images)">
                        <template x-if="product.views_count > 100">
                            <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full z-10 shadow">TERLARIS</span>
                        </template>
                        <template x-if="product.images && product.images.length > 0">
                            <img :src="product.images[0]" :alt="product.name" class="absolute inset-0 w-full h-full object-cover">
                        </template>
                        <template x-if="!product.images || product.images.length === 0">
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400">No Image</div>
                        </template>
                    </div>
                    <div class="p-3 flex-1 flex flex-col">
                        <h3 class="font-semibold text-sm leading-tight mb-1 cursor-pointer"
                            @click="openProductDetail(product.id, product.name, product.price, product.description, product.images)" x-text="product.name"></h3>
                        <p class="text-xs opacity-70 line-clamp-2 mb-2 cursor-pointer"
                            @click="openProductDetail(product.id, product.name, product.price, product.description, product.images)" x-text="product.description"></p>
                        <div class="mt-auto flex flex-col gap-2 pt-2 border-t">
                            <span class="font-bold text-sm">Rp <span x-text="formatRupiah(product.price)"></span></span>

                            <div x-show="!getCartItem(product.id)" class="w-full">
                                <button
                                    @click.stop="addToCart(product.id, product.name, product.price)"
                                    class="bg-primary text-white py-2 w-full btn-custom text-xs font-bold shadow-sm active:scale-95 transition-transform">
                                    + Add
                                </button>
                            </div>

                            <div x-show="getCartItem(product.id)"
                                class="flex items-center justify-between w-full bg-gray-100 p-1 border card-custom">
                                <button @click.stop="updateQuantity(product.id, -1)"
                                    class="w-7 h-7 flex items-center justify-center font-bold text-primary bg-white btn-custom shadow-sm">-</button>
                                <span class="text-xs font-bold" x-text="getCartItem(product.id)?.qty"></span>
                                <button @click.stop="updateQuantity(product.id, 1)"
                                    class="w-7 h-7 flex items-center justify-center font-bold text-primary bg-white btn-custom shadow-sm">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Intersection Observer Target -->
        <div x-intersect="loadMore()" class="py-6 text-center" x-show="nextPageUrl">
            <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div>
        </div>

    </main>

    <!-- Cart Floating Bar (WhatsApp Magic) -->
    <div x-show="totalItems > 0" x-transition
        class="fixed bottom-0 left-0 right-0 p-4 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] border-t z-50 flex justify-between items-center card-custom">
        <div class="max-w-3xl mx-auto w-full flex justify-between items-center">
            <div>
                <p class="text-xs opacity-70">Total (<span x-text="totalItems"></span> item)</p>
                <p class="font-bold text-lg text-primary">Rp <span x-text="formatRupiah(totalPrice)"></span></p>
            </div>
            <button @click="isCheckoutModalOpen = true"
                class="bg-primary text-white px-6 py-3 btn-custom font-bold flex items-center gap-2 shadow-lg">
                Checkout WA
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-whatsapp" viewBox="0 0 16 16">
                    <path
                        d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div x-show="isCheckoutModalOpen"
        class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center bg-black bg-opacity-50"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;">

        <div x-show="isCheckoutModalOpen"
            @click.outside="isCheckoutModalOpen = false"
            class="bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl p-6 transform transition-all card-custom"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Detail Pesanan</h2>
                <button @click="isCheckoutModalOpen = false" class="text-gray-500 hover:text-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" x-model="customer.name" class="w-full border rounded-lg p-2 card-custom"
                        placeholder="Nama Anda">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Catatan Pesanan <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea x-model="customer.notes" class="w-full border rounded-lg p-2 card-custom" rows="3"
                        placeholder="Catatan tambahan, alamat, atau request khusus..."></textarea>
                </div>

                <button @click="processWhatsApp('{{ $store->wa_number }}')"
                    class="w-full bg-primary text-white py-3 btn-custom font-bold text-lg shadow flex justify-center items-center gap-2 mt-4">
                    Kirim Pesanan ke WA
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path
                            d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Floating WA General Chat Button -->
    <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Halo Admin ' . $store->name . ', saya ingin bertanya...') }}"
       target="_blank"
       :class="totalItems > 0 ? 'bottom-24' : 'bottom-6'"
       class="fixed right-6 bg-[#25D366] text-white p-4 rounded-full shadow-lg hover:bg-[#1EBE5D] transition-all z-40 flex items-center justify-center hover:-translate-y-1">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
        </svg>
    </a>

    <!-- Product Detail Modal -->
    <div x-show="isProductModalOpen"
        class="fixed inset-0 z-[110] flex items-end sm:items-center justify-center bg-black bg-opacity-70"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;">

        <div x-show="isProductModalOpen"
            @click.outside="closeProductModal()"
            class="bg-white w-full sm:max-w-md h-[100dvh] flex flex-col overflow-hidden shadow-2xl relative transform transition-all"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full">

            <div class="relative w-full pt-[100%] bg-gray-200 flex-shrink-0">
                <button @click="closeProductModal()"
                    class="absolute top-4 right-4 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center z-[15] hover:bg-opacity-70 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <template x-if="activeProduct?.images?.length > 0">
                    <img :src="activeProduct.images[activeProduct.activeImageIndex]" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300">
                </template>
                <template x-if="!activeProduct?.images || activeProduct.images.length === 0">
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400">No Image</div>
                </template>
            </div>

            <template x-if="activeProduct?.images?.length > 1">
                <div class="flex gap-4 p-4 overflow-x-auto no-scrollbar bg-gray-100 border-b flex-shrink-0">
                    <template x-for="(img, index) in activeProduct.images" :key="index">
                        <img :src="img"
                             @click="activeProduct.activeImageIndex = index"
                             :class="activeProduct.activeImageIndex === index ? 'border-primary ring-2 ring-primary ring-offset-2' : 'border-transparent opacity-50 hover:opacity-80'"
                             class="w-20 h-20 object-cover rounded-lg cursor-pointer border-2 transition-all flex-shrink-0 shadow-sm">
                    </template>
                </div>
            </template>

            <div class="p-5 overflow-y-auto flex-1 flex flex-col">
                <h2 class="text-2xl font-bold mb-2" x-text="activeProduct?.name"></h2>
                <p class="font-bold text-lg text-primary mb-4">Rp <span
                        x-text="activeProduct ? formatRupiah(activeProduct.price) : '0'"></span></p>

                <h4 class="font-semibold text-sm mb-1">Deskripsi Produk:</h4>
                <p class="text-sm opacity-80 leading-relaxed whitespace-pre-line mb-6"
                    x-text="activeProduct?.description"></p>

                <div class="mt-auto">
                    <div x-show="activeProduct && !getCartItem(activeProduct.id)">
                        <button @click="addToCart(activeProduct.id, activeProduct.name, activeProduct.price)"
                            class="w-full bg-primary text-white py-3 btn-custom font-bold text-base shadow">
                            + Tambah ke Keranjang
                        </button>
                    </div>

                    <div x-show="activeProduct && getCartItem(activeProduct.id)"
                        class="flex items-center justify-between bg-gray-100 p-2 border card-custom shadow-sm">
                        <button @click="updateQuantity(activeProduct.id, -1)"
                            class="w-10 h-10 flex items-center justify-center font-bold text-primary text-xl btn-custom bg-white shadow-sm">-</button>
                        <span class="text-lg font-bold"
                            x-text="activeProduct ? getCartItem(activeProduct.id)?.qty : 0"></span>
                        <button @click="updateQuantity(activeProduct.id, 1)"
                            class="w-10 h-10 flex items-center justify-center font-bold text-primary text-xl btn-custom bg-white shadow-sm">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine JS Logic -->
    @php
        $formattedProducts = $products->map(function($p) {
            $images = [];
            $mainImage = $p->image ? (str_starts_with($p->image, 'http') ? $p->image : Storage::url($p->image)) : null;
            if ($mainImage) $images[] = $mainImage;
            foreach($p->images as $img) {
                $images[] = str_starts_with($img->image_path, 'http') ? $img->image_path : Storage::url($img->image_path);
            }
            return [
                'id' => $p->id,
                'category_id' => $p->category_id,
                'slug' => $p->slug,
                'name' => $p->name,
                'price' => $p->price,
                'description' => $p->description,
                'images' => $images
            ];
        });
    @endphp

    <!-- Alpine plugins (Intersect for infinite scroll) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>

    <script>
        const initialProducts = @json($formattedProducts);

        document.addEventListener('alpine:init', () => {
            Alpine.data('catalogApp', () => ({
                allProducts: initialProducts,
                nextPageUrl: '{{ $products->nextPageUrl() }}',
                isLoading: false,
                selectedCategory: 'all',
                cart: {},
                isCheckoutModalOpen: false,
                isProductModalOpen: false,
                activeProduct: null,
                customer: {
                    name: '',
                    notes: ''
                },

                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const productSlug = urlParams.get('p');
                    if (productSlug) {
                        const product = this.allProducts.find(p => p.slug === productSlug);
                        if (product) {
                            this.openProductDetail(product.id, product.name, product.price, product.description, product.images, false);
                        }
                    }
                },

                async loadMore() {
                    if (this.isLoading || !this.nextPageUrl) return;

                    this.isLoading = true;
                    try {
                        const response = await fetch(this.nextPageUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const res = await response.json();

                        // Append new items
                        this.allProducts = [...this.allProducts, ...res.data];
                        this.nextPageUrl = res.next_page_url;
                    } catch (error) {
                        console.error("Gagal load products:", error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                getCartItem(id) {
                    return this.cart[id] || null;
                },

                openProductDetail(id, name, price, description, images, track = true) {
                    this.activeProduct = {
                        id,
                        name,
                        price,
                        description,
                        images: images || [],
                        activeImageIndex: 0
                    };
                    this.isProductModalOpen = true;

                    const p = this.allProducts.find(prod => prod.id === id);
                    if (p) {
                        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?p=' + p.slug;
                        window.history.pushState({path:newUrl}, '', newUrl);
                    }

                    if (track) {
                        fetch(`/catalog/{{ $store->slug }}/track-product-view/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        }).catch(e => console.error(e));
                    }
                },

                closeProductModal() {
                    this.isProductModalOpen = false;
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.pushState({path:newUrl}, '', newUrl);
                },

                addToCart(id, name, price) {
                    this.cart[id] = {
                        id,
                        name,
                        price,
                        qty: 1
                    };
                },

                updateQuantity(id, change) {
                    if (this.cart[id]) {
                        this.cart[id].qty += change;
                        if (this.cart[id].qty <= 0) {
                            delete this.cart[id];
                        }
                    }
                },

                get totalItems() {
                    return Object.values(this.cart).reduce((sum, item) => sum + item.qty, 0);
                },

                get totalPrice() {
                    return Object.values(this.cart).reduce((sum, item) => sum + (item.price * item
                        .qty), 0);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                processWhatsApp(waNumber) {
                    if (!this.customer.name || !this.customer.name.trim()) {
                        alert('Mohon isi nama lengkap Anda!');
                        return;
                    }

                    let text = `*Halo, saya ingin memesan dari {{ $store->name }}:*\n\n`;
                    text += `*Detail Pesanan:*\n`;

                    Object.values(this.cart).forEach(item => {
                        text +=
                            `- ${item.name} (${item.qty}x) = Rp ${this.formatRupiah(item.price * item.qty)}\n`;
                    });

                    text += `\n*Total Harga: Rp ${this.formatRupiah(this.totalPrice)}*\n\n`;

                    text += `*Data Pemesan:*\n`;
                    text += `Nama: ${this.customer.name.trim()}\n`;
                    if (this.customer.notes && this.customer.notes.trim()) {
                        text += `Catatan: ${this.customer.notes.trim()}\n`;
                    }
                    text += `\nMohon segera diproses ya, terima kasih!`;

                    let encodedText = encodeURIComponent(text);

                    // Format number: ensure it starts with country code, remove leading 0, +, or spaces
                    let cleanNumber = waNumber.replace(/\D/g, '');
                    if (cleanNumber.startsWith('0')) {
                        cleanNumber = '62' + cleanNumber.substring(1);
                    }

                    // Track WhatsApp Click
                    fetch(`/catalog/{{ $store->slug }}/track-wa-click`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).catch(e => console.error(e));

                    window.open(`https://wa.me/${cleanNumber}?text=${encodedText}`, '_blank');
                }
            }))
        })
    </script>

    {{-- Free Tier Branding Footer --}}
    <footer class="w-full mt-8 py-4 border-t text-center {{ $store->dark_mode ? 'border-gray-700/60' : 'border-gray-200' }}">
        <p class="text-xs {{ $store->dark_mode ? 'text-gray-500' : 'text-gray-400' }}">
            Katalog milik <span class="font-medium {{ $store->dark_mode ? 'text-gray-300' : 'text-gray-600' }}">{{ $store->name }}</span>
            &middot; Dibuat dengan <a href="{{ url('/') }}" target="_blank" class="font-semibold underline underline-offset-2 decoration-dotted transition-opacity hover:opacity-70 text-primary">{{ config('app.name', 'Katalogin') }}</a>
        </p>
    </footer>

</body>

</html>
