<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        @if(!$showPayment)
            <!-- Form Data Customer -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">📋 Data Pengiriman</h2>
                    
                    <form wire:submit.prevent="proceedToPayment" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" wire:model="customerName" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="John Doe">
                            @error('customerName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" wire:model="customerEmail" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="john@example.com">
                            @error('customerEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telepon *</label>
                            <input type="text" wire:model="customerPhone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="08123456789">
                            @error('customerPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                            <textarea wire:model="customerAddress" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Jl. Contoh No. 123, RT/RW 01/02"></textarea>
                            @error('customerAddress') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota *</label>
                                <input type="text" wire:model="customerCity" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Jakarta">
                                @error('customerCity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos *</label>
                                <input type="text" wire:model="customerPostalCode" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="12345">
                                @error('customerPostalCode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi *</label>
                            <input type="text" wire:model="customerProvince" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="DKI Jakarta">
                            @error('customerProvince') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-semibold">
                            🚀 Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">🛒 Ringkasan Pesanan</h2>
                    
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span class="text-gray-800">Total:</span>
                                <span class="text-blue-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Halaman Pembayaran -->
            @if($snapToken)
                <div class="max-w-2xl mx-auto">
                    <!-- Ringkasan Pesanan -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">🛒 Ringkasan Pesanan</h2>
                        
                        <div class="space-y-3">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between items-center py-2 border-b">
                                    <div>
                                        <h3 class="font-medium">{{ $item['name'] }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center text-lg font-bold">
                                    <span>Total:</span>
                                    <span class="text-blue-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Pembayaran -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <h2 class="text-xl font-semibold mb-4">💳 Pembayaran</h2>
                        <p class="text-gray-600 mb-4">Order ID: <span class="font-mono text-sm">{{ $orderId }}</span></p>

                        <div class="space-y-4">
                            <div id="status" class="text-sm text-blue-600 mb-2">Memuat sistem pembayaran...</div>
                            
                            <button id="payBtn" 
                                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 font-semibold">
                                💳 Bayar Sekarang
                            </button>
                            
                            <p class="text-sm text-gray-500">
                                Anda akan diarahkan ke halaman pembayaran Midtrans yang aman
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SCRIPT YANG PASTI BERHASIL - COPY EXACT DARI DEBUG -->
                <script>
                    console.log('🔄 Starting Midtrans with proven logic...');
                    
                    // Config exact sama dengan debug
                    const CONFIG = {
                        clientKey: '{{ config('midtrans.client_key') }}',
                        snapToken: '{{ $snapToken }}'
                    };
                    
                    console.log('Client Key:', CONFIG.clientKey);
                    console.log('Snap Token:', CONFIG.snapToken);
                    
                    // Function load script dengan Promise (exact copy dari debug)
                    function loadSnapScript() {
                        return new Promise((resolve, reject) => {
                            console.log('🔄 Loading Snap.js script...');
                            
                            // Remove existing script
                            const existingScript = document.querySelector('script[src*="snap.js"]');
                            if (existingScript) {
                                existingScript.remove();
                                console.log('🗑️ Removed existing script');
                            }
                    
                            const script = document.createElement('script');
                            script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
                            script.setAttribute('data-client-key', CONFIG.clientKey);
                            
                            script.onload = function() {
                                console.log('✅ Snap.js loaded successfully!');
                                
                                // KUNCI SUKSES: Delay 1500ms seperti di debug!
                                setTimeout(() => {
                                    if (typeof window.snap !== 'undefined') {
                                        console.log('✅ Snap object is available and ready');
                                        console.log('🔍 Snap object methods:', Object.getOwnPropertyNames(window.snap).join(', '));
                                        resolve(true);
                                    } else {
                                        console.log('❌ Snap object not available after load');
                                        reject(new Error('Snap object not available'));
                                    }
                                }, 1500); // DELAY 1500ms SEPERTI DEBUG!
                            };
                            
                            script.onerror = function() {
                                console.log('❌ Failed to load Snap.js');
                                reject(new Error('Failed to load Snap.js'));
                            };
                            
                            document.head.appendChild(script);
                        });
                    }
                    
                    // Function test popup (exact copy dari debug)
                    function testPopup() {
                        console.log('🚀 Testing Midtrans popup...');
                        
                        // Validasi lengkap
                        if (typeof window.snap === 'undefined') {
                            console.log('❌ Snap object not available');
                            return;
                        }
                    
                        if (!CONFIG.snapToken) {
                            console.log('❌ No snap token available');
                            return;
                        }
                    
                        try {
                            console.log('🎯 Calling window.snap.pay with real token...');
                            console.log('🔍 Token length:', CONFIG.snapToken.length, 'characters');
                            
                            window.snap.pay(CONFIG.snapToken, {
                                onSuccess: function(result) {
                                    console.log('✅ Payment SUCCESS!');
                                    console.log('📄 Result:', JSON.stringify(result, null, 2));
                                    alert('✅ Pembayaran berhasil!');
                                    window.location.href = '/';
                                },
                                onPending: function(result) {
                                    console.log('⏳ Payment PENDING');
                                    console.log('📄 Result:', JSON.stringify(result, null, 2));
                                    alert('⏳ Pembayaran pending.');
                                },
                                onError: function(result) {
                                    console.log('❌ Payment ERROR');
                                    console.log('📄 Result:', JSON.stringify(result, null, 2));
                                    alert('❌ Error pembayaran.');
                                },
                                onClose: function() {
                                    console.log('❌ Popup closed by user');
                                }
                            });
                            
                            console.log('✅ Popup should now be visible!');
                            
                        } catch (error) {
                            console.log('❌ Exception during popup:', error.message);
                            console.log('🔍 Error stack:', error.stack);
                        }
                    }
                    
                    // Initialize dengan Promise
                    async function initializeMidtrans() {
                        try {
                            document.getElementById('status').textContent = 'Memuat script Midtrans...';
                            
                            await loadSnapScript();
                            
                            document.getElementById('status').textContent = '✅ Sistem pembayaran siap!';
                            
                            // Enable button
                            const btn = document.getElementById('payBtn');
                            btn.disabled = false;
                            btn.textContent = '💳 Bayar Sekarang';
                            btn.className = 'bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-all duration-200 font-semibold';
                            
                            // Setup click handler
                            btn.onclick = testPopup;
                            
                        } catch (error) {
                            console.log('❌ Initialization failed:', error.message);
                            document.getElementById('status').textContent = '❌ Gagal memuat sistem pembayaran';
                        }
                    }
                    
                    // Auto-start (exact copy dari debug)
                    window.addEventListener('load', () => {
                        setTimeout(() => {
                            console.log('🚀 Auto-initializing Midtrans...');
                            initializeMidtrans();
                        }, 1000);
                    });
                    
                    // Debug function
                    window.debugMidtrans = function() {
                        console.log('=== MIDTRANS DEBUG ===');
                        console.log('Token:', CONFIG.snapToken);
                        console.log('Client Key:', CONFIG.clientKey);
                        console.log('Snap object:', window.snap);
                        console.log('Script element:', document.querySelector('script[src*="snap.js"]'));
                    };
                </script>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>Error:</strong> Snap token tidak tersedia.
                </div>
            @endif
        @endif
    </div>
</div>