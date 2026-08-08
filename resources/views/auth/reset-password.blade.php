@extends('admin.layouts.guest')

@section('title', 'Reset Password')

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

        <div
            class="w-full max-w-md flex flex-col bg-white/70 dark:bg-zinc-900/70 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-zinc-200/50 dark:shadow-black/50 border border-white/50 dark:border-zinc-800/50 overflow-hidden m-4 relative z-10 p-8 sm:p-12 text-center">

            <div class="mb-6 mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>

            <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight mb-4">Reset Password</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-6 text-sm">
                Silakan buat password baru Anda.
            </p>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 text-left">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5 text-left">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <x-input-floating type="email" name="email" label="Alamat Email" value="{{ $email ?? old('email') }}" required="true" />
                
                {{-- Password --}}
                <div>
                    <div class="relative">
                        <input type="password" name="password" id="password" required placeholder=" "
                            class="block px-4 pb-2.5 pt-4.5 w-full text-base text-gray-900 bg-transparent rounded-xl border border-gray-300 appearance-none dark:text-white dark:border-gray-700 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-500 peer transition-colors pr-12 @error('password') border-red-400 @enderror">
                        <label for="password"
                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text">Password Baru</label>
                        <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 focus:outline-none transition-colors z-20"
                            style="margin-top: 0;">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder=" "
                        class="block px-4 pb-2.5 pt-4.5 w-full text-base text-gray-900 bg-transparent rounded-xl border border-gray-300 appearance-none dark:text-white dark:border-gray-700 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-500 peer transition-colors">
                    <label for="password_confirmation"
                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text">Konfirmasi Password Baru</label>
                </div>

                <button type="submit"
                    class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Reset Password
                </button>
            </form>
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
        });
    </script>
@endsection
