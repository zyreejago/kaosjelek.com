<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><span class="text-gray-900">{{ $product->name }}</span></li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Product Images -->
        <div>
            <!-- Main Image -->
            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden mb-4">
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <img src="/storage/{{ $product->images[$selectedImage] }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Thumbnail Images -->
            @if($product->images && is_array($product->images) && count($product->images) > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $index => $image)
                        <button wire:click="selectImage({{ $index }})"
                                class="aspect-square bg-gray-200 rounded-lg overflow-hidden border-2 {{ $selectedImage == $index ? 'border-blue-500' : 'border-transparent' }} hover:border-blue-300 transition-colors">
                            <img src="/storage/{{ $image }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <!-- Category -->
            <div class="mb-2">
                <span class="text-sm text-gray-500 uppercase tracking-wide">{{ $product->category->name }}</span>
            </div>

            <!-- Product Name -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4" style="font-family: 'Papyrus', cursive;">{{ $product->name }}</h1>

            <!-- Price -->
            <div class="mb-6">
                @if($product->sale_price)
                    <div class="flex items-center space-x-3">
                        <span class="text-3xl font-bold text-red-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                        <span class="text-xl text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm font-semibold">
                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                        </span>
                    </div>
                @else
                    <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- SKU & Stock -->
            <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div>
                    <span class="text-sm text-gray-500">SKU:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $product->sku }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Stok:</span>
                    <span class="text-sm font-medium {{ $product->stock_quantity <= 5 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $product->stock_quantity }} tersedia
                    </span>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                <div class="flex items-center space-x-3">
                    <button wire:click="decrementQuantity" 
                            class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors"
                            {{ $quantity <= 1 ? 'disabled' : '' }}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <span class="text-xl font-semibold w-12 text-center">{{ $quantity }}</span>
                    <button wire:click="incrementQuantity" 
                            class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors"
                            {{ $quantity >= $product->stock_quantity ? 'disabled' : '' }}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Add to Cart Button -->
            <div class="space-y-3">
                @if($product->stock_quantity > 0)
                    <button wire:click="addToCart" 
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
                        Tambah ke Keranjang
                    </button>
                @else
                    <button disabled class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
                
                <a href="{{ route('home') }}" 
                   class="w-full block text-center border border-gray-300 py-3 px-6 rounded-lg font-semibold hover:bg-gray-50 transition-colors duration-200">
                    Lanjut Belanja
                </a>
            </div>

            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif
        </div>
    </div>
</div>
