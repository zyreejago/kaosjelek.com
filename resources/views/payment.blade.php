<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - KaosJelek</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen py-8">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">💳 Pembayaran</h1>
                <p class="text-gray-600 mt-2">Selesaikan pembayaran Anda dengan aman</p>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">🛒 Ringkasan Pesanan</h2>
                
                <div class="space-y-3">
                    @if(session('cart_items'))
                        @foreach(session('cart_items') as $item)
                            <div class="flex justify-between items-center py-2 border-b">
                                <div>
                                    <h3 class="font-medium">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <span class="font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @endif
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-blue-600">Rp {{ number_format(session('cart_total', 0), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h2 class="text-xl font-semibold mb-4">💳 Metode Pembayaran</h2>
                <p class="text-gray-600 mb-4">Order ID: <span class="font-mono text-sm">{{ $orderId ?? 'LOADING...' }}</span></p>

                <div class="space-y-4">
                    <div id="status" class="text-sm text-blue-600 mb-2">Memuat sistem pembayaran...</div>
                    
                    <button id="payBtn" disabled
                            class="bg-gray-400 text-white px-8 py-3 rounded-lg font-semibold cursor-not-allowed">
                        ⏳ Memuat...
                    </button>
                    
                    <p class="text-sm text-gray-500">
                        Anda akan diarahkan ke halaman pembayaran Midtrans yang aman
                    </p>
                    
                    <div class="mt-4">
                        <a href="/checkout" class="text-blue-600 hover:underline">← Kembali ke Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Config
        const CONFIG = {
            clientKey: '{{ config('midtrans.client_key') }}',
            snapToken: '{{ $snapToken ?? '' }}',
            orderId: '{{ $orderId ?? '' }}'
        };

        // Load Snap Script
        function loadSnapScript() {
            return new Promise((resolve, reject) => {
                // Remove existing script
                const existingScript = document.querySelector('script[src*="snap.js"]');
                if (existingScript) {
                    existingScript.remove();
                }

                const script = document.createElement('script');
                script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
                script.setAttribute('data-client-key', CONFIG.clientKey);
                
                script.onload = function() {
                    setTimeout(() => {
                        if (typeof window.snap !== 'undefined') {
                            resolve(true);
                        } else {
                            reject(new Error('Snap object not available'));
                        }
                    }, 1500);
                };
                
                script.onerror = function() {
                    reject(new Error('Failed to load Snap.js'));
                };
                
                document.head.appendChild(script);
            });
        }

        // Open payment popup
        async function openPayment() {
            try {
                const result = await new Promise((resolve, reject) => {
                    window.snap.pay('{{ $snapToken }}', {
                        onSuccess: function(result) {
                            resolve(result);
                        },
                        onPending: function(result) {
                            resolve(result);
                        },
                        onError: function(result) {
                            reject(result);
                        },
                        onClose: function() {
                            reject(new Error('Payment popup closed'));
                        }
                    });
                });
                
                // Jika pembayaran berhasil atau pending
                if (result.transaction_status === 'settlement' || 
                    result.transaction_status === 'capture' || 
                    result.transaction_status === 'pending') {
                    
                    // Kurangi stok dan clear cart
                    await reduceStockAndClearCart(result.order_id);
                    
                    alert('✅ Pembayaran berhasil! Stok telah dikurangi.');
                    window.location.href = '/order-success?order_id=' + result.order_id;
                }
                
            } catch (error) {
                console.error('Payment error:', error);
                alert('❌ Pembayaran gagal: ' + error.message);
            }
        }
        
        // Fungsi untuk mengurangi stok dan clear cart
        async function reduceStockAndClearCart(orderId) {
            try {
                // Panggil endpoint untuk mengurangi stok
                const response = await fetch('/reduce-stock', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                });
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Failed to reduce stock');
                }
                
                // Clear cart
                await fetch('/clear-cart', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
            } catch (error) {
                console.error('Error reducing stock:', error);
                throw error;
            }
        }

        // Initialize payment
        async function initializePayment() {
            try {
                if (!CONFIG.snapToken) {
                    document.getElementById('status').textContent = '❌ Token pembayaran tidak tersedia';
                    return;
                }

                document.getElementById('status').textContent = 'Memuat script Midtrans...';
                
                await loadSnapScript();
                
                document.getElementById('status').textContent = '✅ Sistem pembayaran siap!';
                
                // Enable button
                const btn = document.getElementById('payBtn');
                btn.disabled = false;
                btn.textContent = '💳 Bayar Sekarang';
                btn.className = 'bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-all duration-200 font-semibold';
                btn.onclick = openPayment;
                
            } catch (error) {
                document.getElementById('status').textContent = '❌ Gagal memuat sistem pembayaran';
            }
        }

        // Auto-start
        window.addEventListener('load', () => {
            setTimeout(() => {
                initializePayment();
            }, 1000);
        });
    </script>
</body>
</html>