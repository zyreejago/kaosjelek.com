<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Livewire\ProductCatalog;
use App\Livewire\ProductDetail;
use App\Livewire\ShoppingCart;
use App\Livewire\Checkout;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\HomeController;
use App\Services\CartService;

// Change this line from ProductCatalog::class to HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', ProductCatalog::class)->name('products');
Route::get('/product/{slug}', ProductDetail::class)->name('product.detail');
Route::get('/cart', ShoppingCart::class)->name('cart');
Route::get('/checkout-new', \App\Livewire\Checkout::class)->name('checkout.new');
// Checkout route
Route::get('/checkout', App\Livewire\Checkout::class)->name('checkout');

// Payment route - ALTERNATIF
Route::get('/payment', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payment');

// Debug route untuk generate token Midtrans
Route::get('/debug-token', function() {
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
    \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized', true);
    \Midtrans\Config::$is3ds = config('midtrans.is_3ds', true);

    $params = [
        'transaction_details' => [
            'order_id' => 'DEBUG-' . time() . '-' . \Illuminate\Support\Str::random(6),
            'gross_amount' => 100000,
        ],
        'item_details' => [[
            'id' => 'debug-item-1',
            'price' => 100000,
            'quantity' => 1,
            'name' => 'Debug Product Test'
        ]],
        'customer_details' => [
            'first_name' => 'Debug User',
            'email' => 'debug@test.com',
            'phone' => '08123456789',
            'billing_address' => [
                'first_name' => 'Debug User',
                'email' => 'debug@test.com',
                'phone' => '08123456789',
                'address' => 'Jl. Debug No. 123',
                'city' => 'Jakarta',
                'postal_code' => '12345',
                'country_code' => 'IDN'
            ]
        ]
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'client_key' => config('midtrans.client_key'),
            'order_id' => $params['transaction_details']['order_id'],
            'amount' => $params['transaction_details']['gross_amount']
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'config_check' => [
                'server_key' => config('midtrans.server_key') ? 'Available' : 'Missing',
                'client_key' => config('midtrans.client_key') ? 'Available' : 'Missing',
                'is_production' => config('midtrans.is_production')
            ]
        ]);
    }
});

// Midtrans webhook
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');

// Route untuk clear cart
Route::post('/clear-cart', function() {
    CartService::clearCart();
    return response()->json(['success' => true]);
})->name('cart.clear');

// Route untuk halaman sukses
Route::get('/order-success', function(Request $request) {
    $orderId = $request->get('order_id');
    return view('order-success', compact('orderId'));
})->name('order.success');

// Route untuk mengurangi stok setelah pembayaran berhasil
Route::post('/reduce-stock', function(Request $request) {
    try {
        $orderId = $request->input('order_id');
        
        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Order ID required']);
        }
        
        // Cari order
        $order = \App\Models\Order::where('order_number', $orderId)->first();
        
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }
        
        // Update status order menjadi 'processing' (bukan 'paid')
        $order->update(['status' => 'processing']);
        
        // Kurangi stok untuk setiap item
        $stockReduced = [];
        foreach ($order->orderItems as $orderItem) {
            $product = \App\Models\Product::find($orderItem->product_id);
            
            if ($product && $product->stock_quantity >= $orderItem->quantity) {
                $oldStock = $product->stock_quantity;
                $product->decrement('stock_quantity', $orderItem->quantity);
                
                $stockReduced[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity_reduced' => $orderItem->quantity,
                    'old_stock' => $oldStock,
                    'new_stock' => $product->fresh()->stock_quantity
                ];
                
                \Illuminate\Support\Facades\Log::info('Stock reduced', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity_reduced' => $orderItem->quantity,
                    'remaining_stock' => $product->fresh()->stock_quantity
                ]);
            } else {
                \Illuminate\Support\Facades\Log::warning('Insufficient stock', [
                    'product_id' => $orderItem->product_id,
                    'requested_quantity' => $orderItem->quantity,
                    'available_stock' => $product ? $product->stock_quantity : 0
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Stock reduced successfully',
            'order_status' => $order->fresh()->status,
            'stock_changes' => $stockReduced
        ]);
        
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error reducing stock: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error reducing stock: ' . $e->getMessage()
        ], 500);
    }
})->name('reduce.stock');

// Route untuk invoice
Route::get('/orders/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');
