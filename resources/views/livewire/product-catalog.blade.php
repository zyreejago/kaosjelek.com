<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Koleksi <span style="font-family: 'Papyrus', cursive;">kaosjelek</span></h1>
        <p class="text-lg text-gray-600">Temukan gaya unik yang cocok untuk Anda</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari nama atau deskripsi produk..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select wire:model.live="selectedCategory" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                <select wire:model.live="sortBy" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="name">Nama</option>
                    <option value="price">Harga</option>
                    <option value="created_at">Terbaru</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group relative">
                    <!-- Product Image -->
                    <div class="aspect-square bg-gray-100 relative overflow-hidden">
                        @if($product->images && is_array($product->images) && count($product->images) > 0)
                            <img src="/storage/{{ $product->images[0] }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 right-3 flex justify-between">
                            @if($product->is_featured)
                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                    ⭐ Featured
                                </span>
                            @else
                                <span></span>
                            @endif
                            
                            @if($product->stock_quantity <= 5)
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                    Stok: {{ $product->stock_quantity }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Sale Badge -->
                        @if($product->sale_price)
                            <div class="absolute top-3 right-3">
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                                    {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                </span>
                            </div>
                        @endif
                        
                        <!-- Hover Detail Button -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                            <a href="{{ route('product.detail', $product->slug) }}" 
                               class="bg-white text-gray-900 px-6 py-3 rounded-lg font-semibold shadow-lg transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:bg-gray-50">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-5">
                        <!-- Category -->
                        <div class="mb-2">
                            <span class="text-xs text-blue-600 uppercase tracking-wider font-semibold bg-blue-50 px-2 py-1 rounded-full">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        
                        <!-- Product Name -->
                        <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 min-h-[3.5rem]" style="font-family: 'Papyrus', cursive;">
                            {{ $product->name }}
                        </h3>
                        
                        <!-- Price -->
                        <div class="mb-4">
                            @if($product->sale_price)
                                <div class="flex items-center space-x-2">
                                    <span class="text-xl font-bold text-red-600">
                                        Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-sm text-gray-500 line-through">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                <span class="text-xl font-bold text-blue-600">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4.5M9 5v-.5" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Tidak ada produk ditemukan</h3>
                <p class="text-gray-500 mb-6">Coba ubah filter pencarian atau kategori Anda.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Reset Filter
                </a>
            </div>
        </div>
    @endif
</div>
