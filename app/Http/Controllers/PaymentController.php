<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingInfo; // Tambahkan import ini
use App\Services\CartService;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function show(Request $request)
    {
        $cartItems = CartService::getCart();
        $cartTotal = CartService::getTotal();
        
        // Coba ambil dari session dulu
        $customerData = session('customer_data', []);
        
        // Jika session kosong, coba dari URL parameters
        if (empty($customerData) && $request->has('customer')) {
            try {
                $customerData = json_decode(base64_decode($request->get('customer')), true);
                // Simpan kembali ke session
                session()->put('customer_data', $customerData);
            } catch (\Exception $e) {
                \Log::error('Failed to decode customer data from URL: ' . $e->getMessage());
            }
        }
        
        // Debug logging
        \Log::info('Customer Data Source Check:', [
            'from_session' => session('customer_data'),
            'from_url' => $request->has('customer'),
            'final_data' => $customerData,
            'session_id' => session()->getId()
        ]);
    
        if (empty($cartItems)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong.');
        }
        
        // Jika masih tidak ada data customer, redirect kembali ke checkout
        if (empty($customerData)) {
            return redirect('/checkout')->with('error', 'Data customer tidak ditemukan. Silakan isi form kembali.');
        }
    
        $orderId = 'ORDER-' . time() . '-' . Str::random(6);
    
        // Simpan order ke database
        $order = Order::create([
            'order_number' => $orderId,
            'customer_name' => $customerData['name'] ?? 'Customer',
            'customer_email' => $customerData['email'] ?? 'customer@example.com',
            'customer_phone' => $customerData['phone'] ?? '08123456789',
            'status' => 'pending',
            'subtotal' => $cartTotal,
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'total_amount' => $cartTotal,
            // HAPUS baris-baris ini karena tidak ada di fillable Order model:
            // 'customer_address' => $customerData['address'] ?? '',
            // 'customer_city' => $customerData['city'] ?? '',
            // 'customer_postal_code' => $customerData['postal_code'] ?? '',
            // 'customer_province' => $customerData['province'] ?? ''
        ]);
    
        // PERBAIKI: Tambahkan error handling dan logging untuk ShippingInfo
        try {
            $shippingData = [
                'order_id' => $order->id,
                'recipient_name' => $customerData['name'] ?? 'Default Name',
                'phone' => $customerData['phone'] ?? '08123456789',
                'address' => $customerData['address'] ?? 'Default Address',
                'city' => $customerData['city'] ?? 'Default City',
                'province' => $customerData['province'] ?? 'Default Province',
                'postal_code' => $customerData['postal_code'] ?? '12345',
                'shipping_method' => 'Standard',
                'tracking_number' => null,
                'shipping_cost' => 0
            ];
            
            \Log::info('Creating ShippingInfo with data:', $shippingData);
            
            $shippingInfo = ShippingInfo::create($shippingData);
            
            \Log::info('ShippingInfo created successfully:', ['id' => $shippingInfo->id]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to create ShippingInfo: ' . $e->getMessage(), [
                'customer_data' => $customerData,
                'order_id' => $order->id
            ]);
            // Jangan stop proses, tapi log error
        }
    
        // Simpan order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity']
            ]);
        }
    
        // Prepare transaction details
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $cartTotal
        ];
    
        // Prepare item details
        $itemDetails = [];
        foreach ($cartItems as $item) {
            $itemDetails[] = [
                'id' => $item['id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => $item['name']
            ];
        }
    
        // Prepare customer details
        $customerDetails = [
            'first_name' => $customerData['name'] ?? 'Customer',
            'email' => $customerData['email'] ?? 'customer@example.com',
            'phone' => $customerData['phone'] ?? '08123456789',
            'billing_address' => [
                'first_name' => $customerData['name'] ?? 'Customer',
                'address' => $customerData['address'] ?? 'Address',
                'city' => $customerData['city'] ?? 'City',
                'postal_code' => $customerData['postal_code'] ?? '12345',
                'country_code' => 'IDN'
            ]
        ];
    
        // Prepare transaction data
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails
        ];
    
        try {
            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);
            
            // Store data in session
            session([
                'current_order_id' => $orderId,
                'snap_token' => $snapToken
            ]);
    
            return view('payment', [
                'snapToken' => $snapToken,
                'orderId' => $orderId
            ]);
    
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect('/checkout')->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }
}