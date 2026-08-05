@extends('app.layouts.main')

@section('title', 'Katalog Produk')

@section('content')
<div class="space-y-4">
    <!-- Header Row -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Daftar Produk</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Kelola dan update produk toko kamu.</p>
        </div>
        <a href="{{ route('app.products.create') }}" 
           class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md flex items-center gap-1.5 transition-colors">
            <span>➕</span>
            <span>Tambah</span>
        </a>
    </div>

    <!-- Livewire Product List Component -->
    <livewire:app.product-list />
</div>
@endsection
