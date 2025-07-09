<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function invoice(Order $order)
    {
        // Load relasi yang diperlukan
        $order->load(['orderItems.product']);
        
        return view('orders.invoice', compact('order'));
    }
}
