@extends('admin.layouts.guest')

@section('title', 'Setup Toko')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-zinc-50 dark:bg-zinc-950 transition-all duration-500 relative overflow-hidden">

        {{-- Theme Toggle --}}
        <div class="absolute top-6 right-6 z-50">
            <button id="themeToggle" class="p-2.5 bg-white/70 dark:bg-zinc-900/70 backdrop-blur-md border border-zinc-200/50 dark:border-zinc-700/50 rounded-xl text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white shadow-sm transition-all hover:scale-105">
                <x-heroicon-s-sun id="sunIcon" class="w-5 h-5" style="display: block;" />
                <x-heroicon-s-moon id="moonIcon" class="w-5 h-5" style="display: none;" />
            </button>
        </div>

        {{-- Animated Background --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-500/20 dark:bg-blue-600/10 blur-[120px] animate-pulse" style="animation-duration: 8s;"></div>
            <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[60%] rounded-full bg-purple-500/20 dark:bg-purple-600/10 blur-[120px] animate-pulse" style="animation-duration: 12s;"></div>
        </div>

        <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white/70 dark:bg-zinc-900/70 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-zinc-200/50 dark:shadow-black/50 border border-white/50 dark:border-zinc-800/50 overflow-hidden m-4 relative z-10">

            {{-- Left Panel --}}
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/80 dark:to-purple-900/80 relative overflow-hidden transition-colors duration-500">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjMDAwIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] opacity-20"></div>
                <div class="relative z-10">
                    <img src="{{ asset('images/logo.jpg') }}" alt="{{ config('app.name') }}"
                        class="h-12 w-12 object-cover rounded-xl mb-8 filter drop-shadow-sm dark:drop-shadow-lg">
                    <h1 class="text-4xl font-extrabold tracking-tight mb-4 text-zinc-900 dark:text-white drop-shadow-sm">
                        Hampir selesai!<br>Setup toko kamu 🎉
                    </h1>
                    <p class="text-zinc-600 dark:text-blue-100/90 text-lg max-w-sm">
                        Isi beberapa info singkat tentang tokomu. Ini hanya butuh 30 detik.
                    </p>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 p-4 bg-blue-500/10 dark:bg-white/10 rounded-2xl border border-blue-200/50 dark:border-white/20">
                        <div class="w-8 h-8 rounded-full bg-blue-600 dark:bg-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white dark:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-sm text-zinc-600 dark:text-blue-100/80">
                            Akun berhasil dibuat. Satu langkah lagi dan tokomu siap!
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right Panel --}}
            <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">

                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('images/logo.jpg') }}" alt="{{ config('app.name') }}"
                        class="h-12 mx-auto object-cover rounded-xl">
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Setup Toko</h2>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Info ini bisa diubah kapan saja dari dashboard.</p>
                </div>

                @if (session('success'))
                    <div class="mb-5 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form class="space-y-5" action="{{ route('onboarding.store.post') }}" method="POST" id="onboardingForm">
                    @csrf

                    {{-- Store Name --}}
                    <x-input-floating type="text" name="name" id="storeName" label="Nama Toko" value="{{ old('name') }}" required="true" />

                    {{-- WA Number --}}
                    <div>
                        <x-input-floating type="text" name="wa_number" id="waNumber" label="Nomor WhatsApp" value="{{ old('wa_number') }}" required="true" />
                        <p class="mt-1.5 text-xs text-zinc-400 dark:text-zinc-500 pl-1">Contoh: 628123456789 (tanpa spasi atau tanda +)</p>
                    </div>

                    {{-- Slug with availability checker --}}
                    <div>
                        <div class="flex items-stretch rounded-xl border border-gray-300 dark:border-gray-700 focus-within:border-blue-500 dark:focus-within:border-blue-500 overflow-hidden transition-colors @error('slug') border-red-400 @enderror bg-transparent">
                            <span class="flex items-center px-3 text-xs text-zinc-400 dark:text-zinc-500 bg-zinc-50 dark:bg-zinc-800/50 border-r border-gray-300 dark:border-gray-700 whitespace-nowrap select-none">
                                {{ request()->getHost() }}/
                            </span>
                            <input type="text" name="slug" id="slug" required placeholder="nama-toko-ku"
                                value="{{ old('slug') }}"
                                class="flex-1 px-3 py-3 text-sm text-zinc-900 dark:text-white bg-transparent focus:outline-none">
                        </div>
                        {{-- Slug status indicator --}}
                        <div id="slugStatus" class="mt-1.5 pl-1 hidden">
                            <p id="slugAvailable" class="text-xs text-green-600 dark:text-green-400 hidden">✓ Link tersedia</p>
                            <p id="slugTaken" class="text-xs text-red-500 dark:text-red-400 hidden"></p>
                            <p id="slugChecking" class="text-xs text-zinc-400 dark:text-zinc-500 hidden">Mengecek ketersediaan...</p>
                        </div>
                        <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500 pl-1" id="slugHint">Link katalog yang kamu bagikan ke pembeli</p>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4">
                        <button type="submit" id="submitBtn"
                            class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span id="submitText">Buat Toko Sekarang →</span>
                            <svg id="submitSpinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const html = document.documentElement;
            const dbTheme = '{{ \App\Models\Setting::getSettings()->theme_default ?? "light" }}';
            let savedTheme = localStorage.getItem('adminTheme') ||
                (dbTheme === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : dbTheme);

            function applyTheme(theme) {
                html.classList.toggle('dark', theme === 'dark');
                document.getElementById('sunIcon').style.display = theme === 'dark' ? 'none' : 'block';
                document.getElementById('moonIcon').style.display = theme === 'dark' ? 'block' : 'none';
            }
            applyTheme(savedTheme);

            document.getElementById('themeToggle')?.addEventListener('click', function () {
                const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
                applyTheme(newTheme);
                localStorage.setItem('adminTheme', newTheme);
            });

            // Auto-generate slug from store name
            const storeNameInput = document.getElementById('storeName');
            const slugInput = document.getElementById('slug');
            let slugManuallyEdited = false;

            slugInput.addEventListener('input', () => {
                slugManuallyEdited = true;
                checkSlug(slugInput.value);
            });

            storeNameInput.addEventListener('input', function () {
                if (!slugManuallyEdited) {
                    const generated = this.value.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim()
                        .replace(/\s+/g, '-');
                    slugInput.value = generated;
                    if (generated) checkSlug(generated);
                }
            });

            // Slug availability check
            let slugTimer = null;
            function checkSlug(val) {
                if (!val || val.length < 3) {
                    hideSlugStatus();
                    return;
                }
                clearTimeout(slugTimer);
                showSlugChecking();
                slugTimer = setTimeout(() => {
                    fetch(`/check-slug?slug=${encodeURIComponent(val)}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.available) {
                            showSlugAvailable();
                        } else {
                            showSlugTaken(data.suggestions || []);
                        }
                    })
                    .catch(() => hideSlugStatus());
                }, 500);
            }

            function showSlugChecking() {
                document.getElementById('slugStatus').classList.remove('hidden');
                document.getElementById('slugChecking').classList.remove('hidden');
                document.getElementById('slugAvailable').classList.add('hidden');
                document.getElementById('slugTaken').classList.add('hidden');
            }
            function showSlugAvailable() {
                document.getElementById('slugStatus').classList.remove('hidden');
                document.getElementById('slugAvailable').classList.remove('hidden');
                document.getElementById('slugChecking').classList.add('hidden');
                document.getElementById('slugTaken').classList.add('hidden');
            }
            function showSlugTaken(suggestions) {
                document.getElementById('slugStatus').classList.remove('hidden');
                document.getElementById('slugTaken').classList.remove('hidden');
                document.getElementById('slugChecking').classList.add('hidden');
                document.getElementById('slugAvailable').classList.add('hidden');
                let msg = '✗ Link sudah dipakai.';
                if (suggestions.length > 0) {
                    msg += ' Coba: ' + suggestions.map(s =>
                        `<button type="button" onclick="useSlug('${s}')" class="underline font-medium hover:text-red-700 dark:hover:text-red-300">${s}</button>`
                    ).join(', ');
                }
                document.getElementById('slugTaken').innerHTML = msg;
            }
            function hideSlugStatus() {
                document.getElementById('slugStatus').classList.add('hidden');
            }

            window.useSlug = function(val) {
                slugInput.value = val;
                slugManuallyEdited = true;
                checkSlug(val);
            };

            // Spinner on submit
            document.getElementById('onboardingForm')?.addEventListener('submit', function () {
                const btn = document.getElementById('submitBtn');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                    document.getElementById('submitText').textContent = 'Membuat toko...';
                    document.getElementById('submitSpinner').classList.remove('hidden');
                }
            });
        });
    </script>
@endsection
