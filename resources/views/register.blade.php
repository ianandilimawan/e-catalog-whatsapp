@extends('admin.layouts.guest')

@section('title', 'Daftar Gratis')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-zinc-50 dark:bg-zinc-950 transition-all duration-500 relative overflow-hidden">

        {{-- Theme Toggle --}}
        <div class="absolute top-6 right-6 z-50">
            <button id="themeToggle"
                class="p-2.5 bg-white/70 dark:bg-zinc-900/70 backdrop-blur-md border border-zinc-200/50 dark:border-zinc-700/50 rounded-xl text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white shadow-sm transition-all hover:scale-105">
                <x-heroicon-s-sun id="sunIcon" class="w-5 h-5" style="display: block;" />
                <x-heroicon-s-moon id="moonIcon" class="w-5 h-5" style="display: none;" />
            </button>
        </div>

        {{-- Animated Background --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-500/20 dark:bg-blue-600/10 blur-[120px] animate-pulse"
                style="animation-duration: 8s;"></div>
            <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[60%] rounded-full bg-purple-500/20 dark:bg-purple-600/10 blur-[120px] animate-pulse"
                style="animation-duration: 12s;"></div>
        </div>

        <div
            class="w-full max-w-5xl flex flex-col lg:flex-row bg-white/70 dark:bg-zinc-900/70 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-zinc-200/50 dark:shadow-black/50 border border-white/50 dark:border-zinc-800/50 overflow-hidden m-4 relative z-10">

            {{-- Left Panel --}}
            <div
                class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/80 dark:to-purple-900/80 relative overflow-hidden transition-colors duration-500">
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjMDAwIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')] opacity-20">
                </div>
                <div class="relative z-10">
                    <img src="{{ asset('images/logo.jpg') }}" alt="{{ config('app.name') }}"
                        class="h-12 w-12 object-cover rounded-xl mb-8 filter drop-shadow-sm dark:drop-shadow-lg">
                    <h1 class="text-4xl font-extrabold tracking-tight mb-4 text-zinc-900 dark:text-white drop-shadow-sm">
                        Buat Toko<br>WA-mu Gratis
                    </h1>
                    <p class="text-zinc-600 dark:text-blue-100/90 text-lg max-w-sm">
                        Katalog digital profesional yang terhubung langsung ke WhatsApp kamu. Daftar, setup toko, dan mulai
                        terima order.
                    </p>
                </div>
                <div class="relative z-10 space-y-3">
                    @foreach (['Katalog produk yang bisa dibagikan', 'Pembeli langsung chat WhatsApp', 'Gratis selamanya'] as $item)
                        <div class="flex items-center gap-3 text-sm text-zinc-600 dark:text-blue-100/70">
                            <div
                                class="w-5 h-5 rounded-full bg-blue-500/20 dark:bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-blue-600 dark:text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right Panel --}}
            <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">

                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('images/logo.jpg') }}" alt="{{ config('app.name') }}"
                        class="h-12 mx-auto object-cover rounded-xl">
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Buat Akun</h2>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Gratis selamanya. Tidak perlu kartu kredit.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-2 flex-shrink-0"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form class="space-y-5" action="{{ route('register.post') }}" method="POST" id="registerForm">
                    @csrf

                    {{-- Name --}}
                    <x-input-floating type="text" name="name" label="Nama Lengkap" value="{{ old('name') }}"
                        required="true" />

                    {{-- Email --}}
                    <x-input-floating type="email" name="email" label="Alamat Email" value="{{ old('email') }}"
                        required="true" />

                    {{-- Password --}}
                    <div>
                        <div class="relative">
                            <input type="password" name="password" id="password" required placeholder=" "
                                class="block px-4 pb-2.5 pt-4.5 w-full text-base text-gray-900 bg-transparent rounded-xl border border-gray-300 appearance-none dark:text-white dark:border-gray-700 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-500 peer transition-colors pr-12 @error('password') border-red-400 @enderror">
                            <label for="password"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text">Password</label>
                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 focus:outline-none transition-colors z-20"
                                style="margin-top: 0;">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1.5 text-xs text-zinc-400 dark:text-zinc-500 pl-1">Minimal 8 karakter</p>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            placeholder=" "
                            class="block px-4 pb-2.5 pt-4.5 w-full text-base text-gray-900 bg-transparent rounded-xl border border-gray-300 appearance-none dark:text-white dark:border-gray-700 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-500 peer transition-colors">
                        <label for="password_confirmation"
                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text">Konfirmasi
                            Password</label>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4">
                        <button type="submit" id="submitBtn"
                            class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span id="submitText">Buat Akun Gratis</span>
                            <svg id="submitSpinner" class="animate-spin ml-2 h-5 w-5 text-white hidden"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-10">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Sudah punya akun?
                        <a href="{{ route('login') }}"
                            class="font-semibold text-blue-600 dark:text-blue-400 hover:underline underline-offset-2">Masuk
                            di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.documentElement;
            const dbTheme = '{{ \App\Models\Setting::getSettings()->theme_default ?? 'light' }}';
            let savedTheme = localStorage.getItem('adminTheme') ||
                (dbTheme === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                    'light') : dbTheme);

            function applyTheme(theme) {
                html.classList.toggle('dark', theme === 'dark');
                document.getElementById('sunIcon').style.display = theme === 'dark' ? 'none' : 'block';
                document.getElementById('moonIcon').style.display = theme === 'dark' ? 'block' : 'none';
            }
            applyTheme(savedTheme);

            document.getElementById('themeToggle')?.addEventListener('click', function() {
                const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
                applyTheme(newTheme);
                localStorage.setItem('adminTheme', newTheme);
            });

            document.getElementById('togglePassword')?.addEventListener('click', function() {
                const pwd = document.getElementById('password');
                const type = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
                pwd.setAttribute('type', type);
                document.getElementById('eyeIcon').classList.toggle('hidden');
                document.getElementById('eyeOffIcon').classList.toggle('hidden');
            });

            document.getElementById('registerForm')?.addEventListener('submit', function() {
                const btn = document.getElementById('submitBtn');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                    document.getElementById('submitText').textContent = 'Membuat akun...';
                    document.getElementById('submitSpinner').classList.remove('hidden');
                }
            });
        });
    </script>
@endsection
