<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $cartItems = [];
    public $cartTotal = 0;
    public $snapToken = null;
    public $orderId = null;
    
    // Data pelanggan
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $customerAddress = '';
    public $customerCity = '';
    public $customerPostalCode = '';
    public $customerProvince = '';
    
    public $showPayment = false;

    protected $rules = [
        'customerName' => 'required|min:2',
        'customerEmail' => 'required|email',
        'customerPhone' => 'required|min:10',
        'customerAddress' => 'required|min:10',
        'customerCity' => 'required|min:2',
        'customerPostalCode' => 'required|min:5',
        'customerProvince' => 'required|min:2',
    ];

    protected $messages = [
        'customerName.required' => 'Nama lengkap wajib diisi',
        'customerName.min' => 'Nama minimal 2 karakter',
        'customerEmail.required' => 'Email wajib diisi',
        'customerEmail.email' => 'Format email tidak valid',
        'customerPhone.required' => 'Nomor telepon wajib diisi',
        'customerPhone.min' => 'Nomor telepon minimal 10 digit',
        'customerAddress.required' => 'Alamat lengkap wajib diisi',
        'customerAddress.min' => 'Alamat minimal 10 karakter',
        'customerCity.required' => 'Kota wajib diisi',
        'customerPostalCode.required' => 'Kode pos wajib diisi',
        'customerProvince.required' => 'Provinsi wajib diisi',
    ];

    public function mount()
    {
        $this->cartItems = CartService::getCart();
        $this->cartTotal = CartService::getTotal();
        
        if (empty($this->cartItems)) {
            session()->flash('error', 'Keranjang belanja kosong.');
            return redirect()->route('cart');
        }
    }

    public function proceedToPayment()
    {
        $this->validate();
    
        // Store customer data in session dengan session()->put() yang lebih reliable
        session()->put('customer_data', [
            'name' => $this->customerName,
            'email' => $this->customerEmail,
            'phone' => $this->customerPhone,
            'address' => $this->customerAddress,
            'city' => $this->customerCity,
            'postal_code' => $this->customerPostalCode,
            'province' => $this->customerProvince
        ]);
        
        session()->put('cart_items', $this->cartItems);
        session()->put('cart_total', $this->cartTotal);
        
        // Force save session
        session()->save();
        
        // Debug: Log untuk memastikan data tersimpan
        \Log::info('Session data saved:', [
            'customer_data' => session('customer_data'),
            'session_id' => session()->getId()
        ]);
    
        // Redirect to payment page
        return redirect('/payment');
    }

    public function createMidtransTransaction()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    
        $this->orderId = 'ORDER-' . time() . '-' . Str::random(6);
    
        // Prepare items untuk Midtrans
        $items = [];
        foreach ($this->cartItems as $item) {
            $items[] = [
                'id' => (int) $item['id'],
                'price' => (float) $item['price'],
                'quantity' => (int) $item['quantity'],
                'name' => (string) $item['name']
            ];
        }
    
        $params = [
            'transaction_details' => [
                'order_id' => $this->orderId,
                'gross_amount' => (float) $this->cartTotal,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $this->customerName,
                'email' => $this->customerEmail,
                'phone' => $this->customerPhone,
                'billing_address' => [
                    'first_name' => $this->customerName,
                    'email' => $this->customerEmail,
                    'phone' => $this->customerPhone,
                    'address' => $this->customerAddress,
                    'city' => $this->customerCity,
                    'postal_code' => $this->customerPostalCode,
                    'country_code' => 'IDN'
                ],
                'shipping_address' => [
                    'first_name' => $this->customerName,
                    'email' => $this->customerEmail,
                    'phone' => $this->customerPhone,
                    'address' => $this->customerAddress,
                    'city' => $this->customerCity,
                    'postal_code' => $this->customerPostalCode,
                    'country_code' => 'IDN'
                ]
            ],
        ];
    
        try {
            $this->snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat transaksi: ' . $e->getMessage());
            $this->showPayment = false;
        }
    }

    public function clearCart()
    {
        CartService::clear();
        $this->cartItems = [];
        $this->cartTotal = 0;
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}