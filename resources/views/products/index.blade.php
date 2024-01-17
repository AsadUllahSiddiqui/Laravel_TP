{{-- resources/views/products/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>Products</h1>
    <div class="products">
        @foreach ($products as $product)
            <div class="product">
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->description }}</p>
                <p>Price: ${{ number_format($product->price, 2) }}</p>
                <a href="{{ route('products.show', $product) }}">View Product</a>
            </div>
        @endforeach
    </div>
@endsection
