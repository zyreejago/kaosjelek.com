<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function handle(Request $request)
    {
        // Log semua request yang masuk
        \Illuminate\Support\Facades\Log::info('Webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'raw_body' => $request->getContent()
        ]);
        
        try {
            $notification = new Notification();
            
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            
            Log::info('Midtrans Notification', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);
            
            // Cari order berdasarkan order_id
            $order = Order::where('order_number', $orderId)->first();
            
            if (!$order) {
                Log::error('Order not found: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Order not found']);
            }
            
            // Proses berdasarkan status transaksi
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->update(['status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $order->update(['status' => 'processing']); // Ganti dari 'paid' ke 'processing'
                    $this->reduceStock($order);
                }
            } else if ($transactionStatus == 'settlement') {
                $order->update(['status' => 'processing']); // Ganti dari 'paid' ke 'processing'
                $this->reduceStock($order);
            } else if ($transactionStatus == 'pending') {
                $order->update(['status' => 'pending']);
            } else if ($transactionStatus == 'deny') {
                $order->update(['status' => 'cancelled']);
            } else if ($transactionStatus == 'expire') {
                $order->update(['status' => 'cancelled']);
            } else if ($transactionStatus == 'cancel') {
                $order->update(['status' => 'cancelled']);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Midtrans webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    private function reduceStock(Order $order)
    {
        foreach ($order->orderItems as $orderItem) {
            $product = Product::find($orderItem->product_id);
            if ($product && $product->stock_quantity >= $orderItem->quantity) {
                $product->decrement('stock_quantity', $orderItem->quantity);
                Log::info('Stock reduced', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity_reduced' => $orderItem->quantity,
                    'remaining_stock' => $product->fresh()->stock_quantity
                ]);
            } else {
                Log::warning('Insufficient stock', [
                    'product_id' => $orderItem->product_id,
                    'requested_quantity' => $orderItem->quantity,
                    'available_stock' => $product ? $product->stock_quantity : 0
                ]);
            }
        }
    }
}