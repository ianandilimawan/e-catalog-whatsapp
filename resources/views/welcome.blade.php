<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Primary SEO Meta Tags -->
    <title>{{ config('app.name', 'Katalogin') }} - Buat Katalog Digital & Toko WhatsApp Gratis UMKM</title>
    <meta name="description" content="Katalogin adalah platform e-catalog yang memudahkan UMKM membuat toko online dan menerima pesanan produk & jasa langsung melalui WhatsApp tanpa potongan komisi. Daftar gratis!">
    <meta name="keywords" content="katalog digital, toko online whatsapp, e-catalog whatsapp, buat toko online gratis, umkm go digital, katalog toko hp, aplikasi jualan whatsapp, katalogin">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="author" content="Intech Studio">
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Katalogin">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Katalogin - Buat Katalog Digital & Toko WhatsApp Gratis UMKM">
    <meta property="og:description" content="Platform e-catalog WhatsApp tercepat untuk UMKM. Terima pesanan produk & jasa langsung di WhatsApp tanpa potongan komisi.">
    <meta property="og:image" content="{{ asset('images/logo.jpg') }}">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="600">
    
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="Katalogin - Buat Katalog Digital & Toko WhatsApp Gratis UMKM">
    <meta name="twitter:description" content="Platform e-catalog WhatsApp tercepat untuk UMKM. Terima pesanan produk & jasa langsung di WhatsApp tanpa potongan komisi.">
    <meta name="twitter:image" content="{{ asset('images/logo.jpg') }}">
    
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">

    <!-- Google Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Schema.org Structured Data (JSON-LD) for Search Engines -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@graph": [
        {
          "@@type": "WebApplication",
          "@@id": "{{ url('/') }}#webapp",
          "name": "Katalogin",
          "url": "{{ url('/') }}",
          "applicationCategory": "BusinessApplication",
          "operatingSystem": "All",
          "browserRequirements": "Requires JavaScript. Requires HTML5.",
          "offers": {
            "@@type": "Offer",
            "price": "0",
            "priceCurrency": "IDR",
            "availability": "https://schema.org/InStock"
          },
          "description": "Platform e-catalog digital terintegrasi WhatsApp untuk UMKM Indonesia tanpa potongan komisi."
        },
        {
          "@@type": "Organization",
          "@@id": "{{ url('/') }}#organization",
          "name": "Katalogin",
          "url": "{{ url('/') }}",
          "logo": "{{ asset('images/logo.jpg') }}",
          "parentOrganization": {
            "@@type": "Organization",
            "name": "Intech Studio"
          }
        },
        {
          "@@type": "FAQPage",
          "@@id": "{{ url('/') }}#faq",
          "mainEntity": [
            {
              "@@type": "Question",
              "name": "Apakah aplikasi Katalogin benar-benar gratis?",
              "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Ya, Anda bisa mendaftar dan mulai membuat katalog secara gratis tanpa potongan komisi per transaksi."
              }
            },
            {
              "@@type": "Question",
              "name": "Apakah pembeli perlu mengunduh aplikasi?",
              "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Tidak perlu. Pembeli cukup mengklik link toko Anda melalui browser di HP, memilih produk, lalu pesanan otomatis terkirim ke WhatsApp Anda."
              }
            },
            {
              "@@type": "Question",
              "name": "Bagaimana dengan sistem pembayarannya?",
              "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Sistem pembayaran diselesaikan secara langsung antara Anda dan pembeli melalui WhatsApp via Transfer Bank, E-Wallet, atau COD."
              }
            }
          ]
        }
      ]
    }
    </script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#10b981',
                        secondary: '#047857',
                    }
                }
            }
        }

        // Initial Theme Check
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark .glass-nav {
            background-color: rgba(15, 23, 42, 0.85);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .grid-bg {
            background-size: 40px 40px;
            background-image:
                linear-gradient(to right, rgba(16, 185, 129, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(16, 185, 129, 0.05) 1px, transparent 1px);
            mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
            position: absolute;
            inset: 0;
            z-index: -1;
        }

        .dark .grid-bg {
            background-image:
                linear-gradient(to right, rgba(16, 185, 129, 0.1) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(16, 185, 129, 0.1) 1px, transparent 1px);
        }

        .blob-shape {
            position: absolute;
            filter: blur(100px);
            z-index: -2;
            opacity: 0.15;
            border-radius: 50%;
        }
    </style>
</head>

<body
    class="antialiased relative bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-200 transition-colors duration-300"
    x-data="{
        scrolled: false,
        darkMode: document.documentElement.classList.contains('dark'),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            }
        }
    }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <header class="fixed w-full top-0 z-50 transition-all duration-300"
        :class="scrolled ? 'glass-nav' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center gap-1.5 sm:gap-2">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo Katalogin - Platform Katalog Digital WhatsApp"
                        class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 object-cover">
                    <span class="font-heading font-bold text-lg sm:text-xl tracking-tight text-slate-900 dark:text-white">{{ config('app.name', 'Katalogin') }}</span>
                </a>

                <nav class="hidden md:flex gap-8" aria-label="Navigasi Utama">
                    <a href="#fitur" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">{{ __('Fitur') }}</a>
                    <a href="#cara-kerja" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">{{ __('Cara Kerja') }}</a>
                    <a href="#siapa-cocok" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">{{ __('Untuk Siapa') }}</a>
                    <a href="#testimoni" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">{{ __('Testimoni') }}</a>
                    <a href="#faq" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">{{ __('FAQ') }}</a>
                </nav>

                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Language Switcher -->
                    <div class="flex bg-slate-100 dark:bg-slate-800 rounded-lg p-0.5 sm:p-1">
                        <a href="{{ route('lang.switch', 'id') }}"
                            class="px-1.5 sm:px-2 py-1 text-[10px] sm:text-xs font-bold rounded-md transition-colors {{ app()->getLocale() == 'id' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                            ID
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="px-1.5 sm:px-2 py-1 text-[10px] sm:text-xs font-bold rounded-md transition-colors {{ app()->getLocale() == 'en' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                            EN
                        </a>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button @click="toggleTheme" aria-label="Toggle Dark Mode"
                        class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg p-1.5 sm:p-2 transition-colors">
                        <svg x-show="!darkMode" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>

                    <div class="h-5 sm:h-6 w-px bg-slate-200 dark:bg-slate-700 hidden sm:block"></div>

                    @auth
                        @php
                            $user = auth()->user();
                            $dashRoute = ($user->hasRole('admin-toko') && !$user->hasAnyRole(['administrator', 'admin', 'super-admin']))
                                ? route('app.dashboard')
                                : route('admin.dashboard');
                        @endphp
                        <a href="{{ $dashRoute }}" class="text-xs sm:text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-full shadow-md transition-all flex items-center gap-1.5 active:scale-95">
                            <span>⚡ Ke Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-primary transition-colors">{{ __('Masuk') }}</a>
                        <a href="{{ route('register') }}" class="text-xs sm:text-sm font-semibold text-white bg-primary px-3 py-1.5 sm:px-4 sm:py-2 rounded-full shadow-md hover:bg-secondary transition-colors whitespace-nowrap">{{ __('Mulai Gratis') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden dark:bg-slate-950 transition-colors duration-300">
        <div class="grid-bg"></div>
        <div class="blob-shape bg-emerald-400 w-96 h-96 top-0 left-10 opacity-20 dark:opacity-10"></div>
        <div class="blob-shape bg-sky-400 w-[30rem] h-[30rem] top-20 right-0 opacity-20 dark:opacity-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm font-medium mb-8 shadow-sm">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    {{ __('Tingkatkan Penjualan UMKM Anda Hari Ini') }}
                </div>

                <h1 class="font-heading text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-6 leading-[1.1]">
                    {{ __('Buat Katalog Digital & Toko WhatsApp') }} 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-400">{{ __('Dalam 5 Menit.') }}</span>
                    <br class="hidden sm:inline" />{{ __('Tanpa Potongan Komisi.') }}
                </h1>

                <p class="mt-6 text-base sm:text-lg md:text-xl text-slate-600 dark:text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                    {{ __('Ubah pengunjung menjadi pembeli dengan etalase toko digital yang praktis & profesional. Cocok untuk jualan barang maupun tawaran jasa. Pesanan langsung terkirim ke WhatsApp tanpa ribet.') }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ $dashRoute }}"
                            class="w-full sm:w-auto font-bold text-base px-8 py-4 bg-emerald-600 text-white rounded-full hover:bg-emerald-700 transition-all shadow-xl flex items-center justify-center gap-2 hover:-translate-y-1">
                            ⚡ {{ __('Buka Dashboard Toko Anda') }}
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="w-full sm:w-auto font-bold text-base px-8 py-4 bg-slate-900 dark:bg-primary text-white rounded-full hover:bg-slate-800 dark:hover:bg-secondary transition-all shadow-xl flex items-center justify-center gap-2 hover:-translate-y-1">
                            {{ __('Buat Toko Gratis Sekarang') }}
                        </a>
                    @endauth
                    <a href="/lumiere-skincare" target="_blank"
                        class="w-full sm:w-auto font-bold text-base px-8 py-4 bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-full hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm flex items-center justify-center gap-2 hover:-translate-y-1">
                        {{ __('Lihat Contoh Katalog Toko') }} ↗
                    </a>
                </div>
            </div>
        </div>

        <!-- Hero Mockup Images -->
        <div class="mt-20 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
            <div class="relative rounded-2xl bg-slate-900 dark:bg-slate-800 p-2 shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-sky-500 rounded-2xl transform rotate-1 scale-[1.02] -z-10 opacity-30 blur-xl"></div>
                <div class="flex items-center gap-2 px-4 py-3 bg-slate-800 dark:bg-slate-900 rounded-t-xl border-b border-slate-700 dark:border-slate-800">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <div class="mx-auto bg-slate-900 dark:bg-black text-slate-400 text-xs px-3 py-1 rounded-md font-mono">katalog.anda.com</div>
                </div>

                <!-- Desktop Screenshot -->
                <div class="aspect-[16/9] bg-slate-100 dark:bg-slate-950 rounded-b-xl overflow-hidden relative">
                    <img src="{{ asset('images/mockup-admin.png') }}"
                        onerror="this.onerror=null; this.src='https://placehold.co/1200x800/1e293b/fff?text=Admin+Dashboard+Katalogin'"
                        alt="Dashboard Pengelola Katalog Digital WhatsApp Katalogin" class="w-full h-full object-cover shadow-inner" loading="lazy">
                </div>

                <!-- Floating Mobile Screenshot -->
                <div class="absolute -right-2 -bottom-6 md:-right-6 md:-bottom-10 w-36 sm:w-52 md:w-64 z-30 transform hover:-translate-y-2 transition-transform duration-500">
                    <div class="rounded-[2.2rem] md:rounded-[2.5rem] border-[5px] md:border-[8px] border-slate-800 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden shadow-2xl relative aspect-[1170/2532]">
                        <!-- Dynamic Island Notch -->
                        <div class="absolute top-1.5 inset-x-0 h-3 md:h-4 bg-slate-800 dark:bg-slate-700 rounded-full w-14 md:w-20 mx-auto z-20"></div>
                        <img src="{{ asset('images/mockup-catalog.png') }}"
                            onerror="this.onerror=null; this.src='https://placehold.co/400x850/10b981/fff?text=Toko+Mobile'"
                            alt="Tampilan Katalog Digital HP Pembeli WhatsApp" class="w-full h-full object-fill object-top" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Fitur -->
    <section id="fitur" class="py-24 bg-white dark:bg-slate-900 overflow-hidden transition-colors duration-300 border-t border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="font-heading text-3xl md:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('Fitur Lengkap Katalog Digital WhatsApp') }}
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto">
                    {{ __('Sistem katalog toko online yang membantu Anda berjualan secara praktis dan profesional.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 hover:border-emerald-200 dark:hover:border-emerald-500/50 transition-colors group">
                    <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-3">
                        {{ __('Pesanan Otomatis Masuk WA') }}
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ __('Tidak ada lagi format pesanan yang berantakan. Pembeli memilih produk, checkout, dan rincian pesanan rapi otomatis terkirim langsung ke WhatsApp Anda.') }}
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 hover:border-sky-200 dark:hover:border-sky-500/50 transition-colors group">
                    <div class="w-14 h-14 bg-sky-100 dark:bg-sky-900/50 text-sky-600 dark:text-sky-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-3">
                        {{ __('Bebas Komisi Transaksi 100%') }}
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ __('Jual produk fisik, kuliner, hingga penawaran jasa tanpa potongan komisi. Seluruh hasil penjualan masuk 100% ke kantong bisnis Anda.') }}
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 hover:border-amber-200 dark:hover:border-amber-500/50 transition-colors group">
                    <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-3">
                        {{ __('Analitik Pengunjung & Tracking SEO') }}
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ __('Ketahui produk mana yang paling diminati. Integrasi mudah dengan Google Analytics, Meta Pixel, dan Google Search Console untuk memaksimalkan promosi.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Cara Kerja -->
    <section id="cara-kerja" class="py-24 bg-slate-900 dark:bg-slate-950 text-white relative overflow-hidden transition-colors duration-300">
        <div class="absolute inset-0 grid-bg opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="font-heading text-3xl md:text-5xl font-bold mb-4">{{ __('Cara Kerja Katalogin yang Super Praktis') }}</h2>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ __('Hanya butuh 3 langkah mudah tanpa perlu keahlian koding.') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative text-center">
                <div class="hidden md:block absolute top-10 left-[15%] right-[15%] h-0.5 bg-slate-800 dark:bg-slate-800 z-0"></div>

                <div class="relative z-10">
                    <div class="w-20 h-20 mx-auto bg-slate-800 dark:bg-slate-900 rounded-2xl flex items-center justify-center text-primary mb-6 shadow-xl border border-slate-700 dark:border-slate-800">
                        <span class="font-heading text-3xl font-black">1</span>
                    </div>
                    <h3 class="font-heading text-xl font-bold mb-3">{{ __('Daftar & Upload Produk') }}</h3>
                    <p class="text-slate-400">{{ __('Buat akun toko, pilih nama & warna tema, lalu upload produk beserta harganya.') }}</p>
                </div>
                <div class="relative z-10">
                    <div class="w-20 h-20 mx-auto bg-slate-800 dark:bg-slate-900 rounded-2xl flex items-center justify-center text-primary mb-6 shadow-xl border border-slate-700 dark:border-slate-800">
                        <span class="font-heading text-3xl font-black">2</span>
                    </div>
                    <h3 class="font-heading text-xl font-bold mb-3">{{ __('Sebarkan Link Katalog') }}</h3>
                    <p class="text-slate-400">{{ __('Sebarkan link toko Anda di bio Instagram, Facebook, TikTok, atau WhatsApp Status.') }}</p>
                </div>
                <div class="relative z-10">
                    <div class="w-20 h-20 mx-auto bg-slate-800 dark:bg-slate-900 rounded-2xl flex items-center justify-center text-primary mb-6 shadow-xl border border-slate-700 dark:border-slate-800">
                        <span class="font-heading text-3xl font-black">3</span>
                    </div>
                    <h3 class="font-heading text-xl font-bold mb-3">{{ __('Terima Pesanan di WhatsApp') }}</h3>
                    <p class="text-slate-400">{{ __('Pelanggan memilih produk dan rincian pesanan langsung dikirim ke WhatsApp Anda.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Untuk Siapa -->
    <section id="siapa-cocok" class="py-24 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-heading text-3xl md:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('Solusi Katalog Digital untuk Semua Jenis Usaha') }}
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto">
                    {{ __('Didesain fleksibel untuk mendukung berbagai sektor usaha UMKM Indonesia.') }}
                </p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                <!-- Card 1 -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm text-center border border-slate-100 dark:border-slate-700">
                    <div class="text-4xl mb-4">🛠️</div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('Penyedia Jasa') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Servis AC, Pijat Refleksi, Cuci Sepatu, Desain Grafis, Fotografi') }}</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm text-center border border-slate-100 dark:border-slate-700">
                    <div class="text-4xl mb-4">👕</div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('Fashion & Retail') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Toko Baju, Sepatu, Hijab, Busana Muslim, Aksesoris') }}</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm text-center border border-slate-100 dark:border-slate-700">
                    <div class="text-4xl mb-4">🍔</div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('Kuliner & F&B') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Catering, Kue Kering, Kopi Literan, Frozen Food, Restoran') }}</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm text-center border border-slate-100 dark:border-slate-700">
                    <div class="text-4xl mb-4">📦</div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('Toko Kelontong & Sembako') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Sembako, Warung Madura, Alat Tulis Kantor, Minimarket') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Testimoni -->
    <section id="testimoni" 
        class="py-24 bg-white dark:bg-slate-950 transition-colors duration-300 overflow-hidden"
        x-data="{
            scrollNext() { $refs.slider.scrollBy({ left: 350, behavior: 'smooth' }) },
            scrollPrev() { $refs.slider.scrollBy({ left: -350, behavior: 'smooth' }) }
        }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-16 gap-6">
                <div class="text-left">
                    <h2 class="font-heading text-3xl md:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                        {{ __('Apa Kata Ribuan Pemilik Usaha?') }}
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl">
                        {{ __('Pebisnis dan pemilik UMKM telah membuktikan kemudahan membuat katalog digital dengan Katalogin.') }}
                    </p>
                </div>
                <div class="hidden md:flex gap-3 shrink-0">
                    <button @click="scrollPrev" aria-label="Slide sebelumnya" class="p-3 rounded-full border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="scrollNext" aria-label="Slide berikutnya" class="p-3 rounded-full border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            
            <div x-ref="slider" class="flex overflow-x-auto gap-6 snap-x snap-mandatory pb-8 -mx-4 px-4 sm:mx-0 sm:px-0 scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                <!-- Testimoni 1 -->
                <div class="snap-start shrink-0 w-[85vw] sm:w-[400px] bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 relative">
                    <div class="text-primary mb-4">
                        <svg class="w-8 h-8 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" /></svg>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic mb-6">
                        "{{ __('Semenjak pakai Katalogin, pelanggan nggak perlu nanya-nanya harga lagi. Tinggal kasih link, mereka pilih sendiri. Jualan jadi jauh lebih praktis!') }}"
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold text-xl">A</div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Ahmad R.</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Pemilik Toko Sepatu') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="snap-start shrink-0 w-[85vw] sm:w-[400px] bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 relative">
                    <div class="text-primary mb-4">
                        <svg class="w-8 h-8 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" /></svg>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic mb-6">
                        "{{ __('Tampilannya rapi, elegan, mirip e-commerce beneran padahal cuma diakses lewat link. Customer banyak yang muji olshop saya makin profesional.') }}"
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900 rounded-full flex items-center justify-center text-pink-600 dark:text-pink-400 font-bold text-xl">S</div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Siti Nurhaliza</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Owner Skincare Lokal') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="snap-start shrink-0 w-[85vw] sm:w-[400px] bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 relative">
                    <div class="text-primary mb-4">
                        <svg class="w-8 h-8 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" /></svg>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic mb-6">
                        "{{ __('Pesanan langsung masuk ke WhatsApp lengkap dengan total harga. Saya tinggal balas dengan ongkir dan resi. Rekomendasi banget!') }}"
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xl">B</div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Budi Santoso</div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Grosir Pakaian') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section FAQ (Structured with Schema) -->
    <section id="faq" class="py-24 bg-slate-50 dark:bg-slate-900 transition-colors duration-300 border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-heading text-3xl md:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('Pertanyaan Sering Diajukan (FAQ)') }}
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-lg mx-auto">
                    {{ __('Jawaban cepat seputar pembuatan katalog digital WhatsApp.') }}
                </p>
            </div>

            <div class="space-y-4" x-data="{ selected: null }">
                <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-900/50">
                    <button @click="selected !== 1 ? selected = 1 : selected = null"
                        class="w-full text-left px-6 py-5 font-semibold text-slate-900 dark:text-white flex justify-between items-center focus:outline-none">
                        <span>{{ __('Apakah aplikasi Katalogin benar-benar gratis?') }}</span>
                        <svg class="w-5 h-5 transform transition-transform" :class="selected === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="selected === 1" x-collapse class="px-6 pb-5 text-slate-600 dark:text-slate-400">
                        {{ __('Ya, Anda bisa mendaftar dan mulai membuat katalog secara gratis tanpa batasan jumlah produk. Tidak ada potongan per transaksi karena pembayaran langsung ditransfer ke Anda.') }}
                    </div>
                </div>

                <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-900/50">
                    <button @click="selected !== 2 ? selected = 2 : selected = null"
                        class="w-full text-left px-6 py-5 font-semibold text-slate-900 dark:text-white flex justify-between items-center focus:outline-none">
                        <span>{{ __('Apakah pembeli perlu mengunduh aplikasi?') }}</span>
                        <svg class="w-5 h-5 transform transition-transform" :class="selected === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="selected === 2" x-collapse class="px-6 pb-5 text-slate-600 dark:text-slate-400">
                        {{ __('Tidak perlu. Pembeli cukup mengklik link toko Anda melalui browser di HP, memilih produk, lalu pesanan otomatis terkirim ke WhatsApp Anda.') }}
                    </div>
                </div>

                <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-900/50">
                    <button @click="selected !== 3 ? selected = 3 : selected = null"
                        class="w-full text-left px-6 py-5 font-semibold text-slate-900 dark:text-white flex justify-between items-center focus:outline-none">
                        <span>{{ __('Bagaimana dengan sistem pembayarannya?') }}</span>
                        <svg class="w-5 h-5 transform transition-transform" :class="selected === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="selected === 3" x-collapse class="px-6 pb-5 text-slate-600 dark:text-slate-400">
                        {{ __('Sistem pembayaran diselesaikan secara langsung antara Anda dan pembeli via obrolan WhatsApp (Transfer Bank, E-Wallet, atau COD).') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Section -->
    <section class="py-24 bg-primary text-white text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-heading text-3xl md:text-5xl font-bold mb-6">{{ __('Siap Beralih ke Katalog Digital WhatsApp?') }}</h2>
            <p class="text-emerald-100 text-lg mb-10">
                {{ __('Ribuan UMKM telah menggunakan Katalogin untuk mempermudah transaksi dan meningkatkan pesanan.') }}
            </p>
            @auth
                <a href="{{ $dashRoute }}"
                    class="inline-block font-bold text-lg px-10 py-5 bg-white text-emerald-700 rounded-full hover:bg-slate-50 transition-all shadow-xl hover:-translate-y-1">
                    ⚡ {{ __('Buka Dashboard Toko') }}
                </a>
            @else
                <a href="{{ route('register') }}"
                    class="inline-block font-bold text-lg px-10 py-5 bg-white text-primary rounded-full hover:bg-slate-50 transition-all shadow-xl hover:-translate-y-1">
                    {{ __('Buat Toko Sekarang - Gratis') }}
                </a>
            @endauth
        </div>
    </section>

    <footer class="bg-slate-50 dark:bg-black py-12 border-t border-slate-200 dark:border-slate-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-slate-500 dark:text-slate-400">
            <div class="text-center md:text-left">
                &copy; {{ date('Y') }} <span class="font-bold text-slate-700 dark:text-slate-300">{{ config('app.name', 'Katalogin') }}</span>.
                All rights reserved.<br>
                <span class="text-xs mt-1 block">Memberdayakan UMKM Indonesia untuk Go Digital.</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-900 rounded-full shadow-sm border border-slate-100 dark:border-slate-800">
                <span class="text-xs">A proud product by</span>
                <a href="mailto:hi.intechstudio@gmail.com" class="font-heading font-bold text-slate-800 dark:text-white tracking-tight hover:text-sky-600 dark:hover:text-sky-400 transition-colors">Intech Studio</a>
            </div>
        </div>
    </footer>

</body>

</html>
