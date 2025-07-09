<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Checkout</h1>

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(!$showPayment)
            <!-- Form Data Pelanggan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Form Data Diri -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">📋 Data Pengiriman</h2>
                    
                    <form wire:submit.prevent="proceedToPayment" class="space-y-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" id="customerName" wire:model="customerName" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Masukkan nama lengkap">
                            @error('customerName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="customerEmail" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="customerEmail" wire:model="customerEmail" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="contoh@email.com">
                            @error('customerEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="customerPhone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon *</label>
                            <input type="tel" id="customerPhone" wire:model="customerPhone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="08123456789">
                            @error('customerPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label for="customerAddress" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                            <textarea id="customerAddress" wire:model="customerAddress" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Jalan, RT/RW, Kelurahan, Kecamatan"></textarea>
                            @error('customerAddress') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kota dan Kode Pos -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="customerCity" class="block text-sm font-medium text-gray-700 mb-1">Kota *</label>
                                <input type="text" id="customerCity" wire:model="customerCity" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Jakarta">
                                @error('customerCity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="customerPostalCode" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos *</label>
                                <input type="text" id="customerPostalCode" wire:model="customerPostalCode" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="12345">
                                @error('customerPostalCode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label for="customerProvince" class="block text-sm font-medium text-gray-700 mb-1">Provinsi *</label>
                            <input type="text" id="customerProvince" wire:model="customerProvince" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">📦 Informasi Pengiriman</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Estimasi pengiriman: 2-5 hari kerja</li>
                            <li>• Gratis ongkir untuk pembelian di atas Rp 100.000</li>
                            <li>• Barang akan dikemas dengan aman</li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <!-- Halaman Pembayaran -->
            @if($snapToken)
                <div class="max-w-2xl mx-auto">
                    <!-- Data Pengiriman Konfirmasi -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">📋 Data Pengiriman</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-600">Nama:</span>
                                <span class="text-gray-800">{{ $customerName }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Email:</span>
                                <span class="text-gray-800">{{ $customerEmail }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Telepon:</span>
                                <span class="text-gray-800">{{ $customerPhone }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Kota:</span>
                                <span class="text-gray-800">{{ $customerCity }}, {{ $customerProvince }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="font-medium text-gray-600">Alamat:</span>
                                <span class="text-gray-800">{{ $customerAddress }}, {{ $customerPostalCode }}</span>
                            </div>
                        </div>
                    </div>

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
                            <div id="payment-status" class="text-sm text-gray-500 mb-2">Memuat sistem pembayaran...</div>
                            
                            <button id="pay-button" 
                                    disabled
                                    class="bg-gray-400 text-white px-8 py-3 rounded-lg font-semibold cursor-not-allowed">
                                💳 Memuat...
                            </button>
                            
                            <p class="text-sm text-gray-500">
                                Anda akan diarahkan ke halaman pembayaran Midtrans yang aman
                            </p>
                        </div>
                    </div>
                </div>

                <!-- HAPUS SEMUA SCRIPT LAMA, GANTI DENGAN INI -->
                <script>
                    // Konfigurasi
                    const snapToken = '{{ $snapToken }}';
                    const clientKey = '{{ config('midtrans.client_key') }}';
                    
                    console.log('🔄 Loading Midtrans...');
                    
                    // Load script
                    const script = document.createElement('script');
                    script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
                    script.setAttribute('data-client-key', clientKey);
                    document.head.appendChild(script);
                    
                    // Langsung setup setelah load
                    script.onload = function() {
                        console.log('✅ Script loaded');
                        
                        // Cek snap object setiap 100ms
                        const checkSnap = setInterval(() => {
                            if (typeof window.snap !== 'undefined') {
                                clearInterval(checkSnap);
                                console.log('✅ Snap ready!');
                                
                                // Update UI
                                document.getElementById('payment-status').textContent = 'Siap bayar!';
                                const btn = document.getElementById('pay-button');
                                btn.disabled = false;
                                btn.className = 'bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 font-semibold';
                                btn.textContent = '💳 Bayar Sekarang';
                                
                                // Setup payment
                                btn.onclick = () => {
                                    console.log('🚀 Opening popup...');
                                    window.snap.pay(snapToken, {
                                        onSuccess: (result) => {
                                            alert('Pembayaran berhasil!');
                                            window.location.href = '/';
                                        },
                                        onPending: (result) => {
                                            alert('Pembayaran pending.');
                                        },
                                        onError: (result) => {
                                            alert('Error pembayaran.');
                                        },
                                        onClose: () => {
                                            console.log('Popup closed');
                                        }
                                    });
                                };
                            }
                        }, 100);
                    };
                    
                    script.onerror = () => {
                        console.log('❌ Script error');
                        document.getElementById('payment-status').textContent = 'Error loading payment system';
                    };
                </script>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>Error:</strong> Snap token tidak tersedia. Silakan coba lagi.
                </div>
            @endif
        @endif
    </div>
</div>