{{-- resources/views/cart/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>Your Cart</h1>
    <div class="cart-items">
        @foreach ($carts as $cart)
            <div class="cart-item">
                <h2>{{ $cart->product->name }}</h2>
                <p>Quantity: {{ $cart->quantity }}</p>
                <p>Price: ${{ number_format($cart->product->price, 2) }}</p>
                <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Remove from Cart</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
