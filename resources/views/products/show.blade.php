{{-- resources/views/products/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="product-detail">
        <h1>{{ $product->name }}</h1>
        <div class="product-description">
            <p>{{ $product->description }}</p>
        </div>
        <div class="product-price">
            <strong>Price:</strong> ${{ number_format($product->price, 2) }}
        </div>
        <div class="product-stock">
            <strong>Stock:</strong> {{ $product->stock }}
        </div>

        {{-- Add to Cart Button (Assuming user is logged in and cart functionality is implemented) --}}
        @auth
            <form action="{{ url('/cart') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                <button type="submit">Add to Cart</button>
            </form>
        @endauth
    </div>
@endsection
