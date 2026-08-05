@extends('app.layouts.main')

@section('title', isset($product) && $product->id ? 'Edit Produk' : 'Tambah Produk')

@section('content')
    <livewire:app.product-form :product="$product" />
@endsection
