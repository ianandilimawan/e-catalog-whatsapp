@extends('admin.layouts.guest')

@section('title', 'Verifikasi Email')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>

            <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight mb-4">Cek Email Anda</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-6">
                Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan.
            </p>

            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl p-4">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="text-sm font-medium text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300">
                    Keluar (Logout)
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
        });
    </script>
@endsection
