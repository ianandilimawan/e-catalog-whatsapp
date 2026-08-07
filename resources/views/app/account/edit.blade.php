@extends('app.layouts.main')

@section('title', 'Pengaturan Akun')

@section('content')
    <div class="space-y-5 md:space-y-6">
        <!-- Header Title & Back Button -->
        <div class="flex items-center gap-3">
            <a href="{{ route('app.account.index') }}"
                class="p-2 rounded-full bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white">Pengaturan Akun</h1>
                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400">Kelola nama, alamat email, dan kata sandi
                    kamu.</p>
            </div>
        </div>

        <!-- 1. Edit Profil (Nama & Email) -->
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Informasi Akun</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Update nama dan alamat email kamu.</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>

            <form action="{{ route('app.account.update_profile') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-4 md:grid md:grid-cols-2 md:gap-5 md:space-y-0">
                    <!-- Nama Lengkap -->
                    <div>
                        <label
                            class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full h-11 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500" />
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label
                            class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full h-11 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500" />
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button type="submit"
                        class="w-full md:w-auto px-6 h-11 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white font-bold text-xs md:text-sm shadow-md transition-all">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Ganti Password -->
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm md:text-base font-bold text-zinc-900 dark:text-white">Keamanan & Password</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Ubah kata sandi akun vendor kamu.</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <form action="{{ route('app.account.update_password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-4 md:grid md:grid-cols-3 md:gap-4 md:space-y-0">
                    <!-- Password Saat Ini -->
                    <div>
                        <label
                            class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="current_password" placeholder="••••••••" required
                            class="w-full h-11 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500" />
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label
                            class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" placeholder="Min. 8 Karakter" required
                            class="w-full h-11 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500" />
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label
                            class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                            Ulangi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required
                            class="w-full h-11 px-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-900 dark:text-white focus:outline-none focus:border-emerald-500" />
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button type="submit"
                        class="w-full md:w-auto px-6 h-11 rounded-xl bg-zinc-900 dark:bg-zinc-100 hover:bg-zinc-800 dark:hover:bg-white text-white dark:text-zinc-900 font-bold text-xs md:text-sm shadow-md transition-all">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
