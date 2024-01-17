<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->get();
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $request->total_price,
            'shipping_address' => $request->shipping_address,
            // Add other fields as necessary
        ]);

        // Handle the logic for processing the order

        return redirect()->route('orders.show', $order->id);
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }
}
