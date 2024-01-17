{{-- resources/views/orders/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>Your Orders</h1>
    <div class="orders">
        @foreach ($orders as $order)
            <div class="order">
                <h2>Order #{{ $order->id }}</h2>
                <p>Total Price: ${{ number_format($order->total_price, 2) }}</p>
                <p>Status: {{ $order->status }}</p>
                <p>Shipping Address: {{ $order->shipping_address }}</p>
                <a href="{{ route('orders.show', $order) }}">View Order</a>
            </div>
        @endforeach
    </div>
@endsection
