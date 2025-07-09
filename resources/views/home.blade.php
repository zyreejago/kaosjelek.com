<x-layouts.app>
    <!-- Banner Section -->
    @if($banners->count() > 0)
        <div class="container mx-auto px-4 py-8">
            @if($banners->count() <= 4)
                <!-- Static Grid for 4 or fewer banners -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                    @foreach($banners as $banner)
                        <div class="relative group cursor-pointer">
                            <!-- Banner Card -->
                            <div class="aspect-square bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                @php
                                    $media = $banner->getFirstMedia('banner_images');
                                    $imagePath = $media ? $media->id . '/' . $media->file_name : null;
                                @endphp
                                
                                @if($imagePath)
                                    <img src="/storage/{{ $imagePath }}" 
                                         alt="{{ $banner->title }}" 
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ substr($banner->title, 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                                
                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    @if($banner->title)
                                        <h3 class="text-white text-sm font-semibold truncate" style="font-family: 'Papyrus', cursive;">
                                            {{ $banner->title }}
                                        </h3>
                                    @endif
                                    @if($banner->subtitle)
                                        <p class="text-white/80 text-xs truncate mt-1">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- Badge/Label -->
                                @if($banner->button_text)
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold shadow-lg">
                                            {{ $banner->button_text }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Click Action -->
                            @if($banner->button_link)
                                <a href="{{ $banner->button_link }}" class="absolute inset-0 z-10"></a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Swiper for more than 4 banners -->
                <div class="relative">
                    <div class="swiper banner-cards-swiper">
                        <div class="swiper-wrapper">
                            @foreach($banners->chunk(4) as $bannerChunk)
                                <div class="swiper-slide">
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($bannerChunk as $banner)
                                            <div class="relative group cursor-pointer">
                                                <!-- Banner Card -->
                                                <div class="aspect-square bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                                    @php
                                                        $media = $banner->getFirstMedia('banner_images');
                                                        $imagePath = $media ? $media->id . '/' . $media->file_name : null;
                                                    @endphp
                                                    
                                                    @if($imagePath)
                                                        <img src="/storage/{{ $imagePath }}" 
                                                             alt="{{ $banner->title }}" 
                                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                                            <span class="text-white font-bold text-lg">{{ substr($banner->title, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Overlay -->
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                                                    
                                                    <!-- Content Overlay -->
                                                    <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                        @if($banner->title)
                                                            <h3 class="text-white text-sm font-semibold truncate" style="font-family: 'Papyrus', cursive;">
                                                                {{ $banner->title }}
                                                            </h3>
                                                        @endif
                                                        @if($banner->subtitle)
                                                            <p class="text-white/80 text-xs truncate mt-1">
                                                                {{ $banner->subtitle }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Badge/Label -->
                                                    @if($banner->button_text)
                                                        <div class="absolute top-2 left-2">
                                                            <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold shadow-lg">
                                                                {{ $banner->button_text }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Click Action -->
                                                @if($banner->button_link)
                                                    <a href="{{ $banner->button_link }}" class="absolute inset-0 z-10"></a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Navigation -->
                        <div class="swiper-pagination mt-6"></div>
                        <div class="swiper-button-next text-blue-600"></div>
                        <div class="swiper-button-prev text-blue-600"></div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Featured Products Section -->
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Produk Terbaru <span style="font-family: 'Papyrus', cursive;">kaosjelek</span></h2>
            <p class="text-lg text-gray-600 mb-6">Temukan koleksi terbaru kami</p>
        </div>
        
        @if($featuredProducts->count() > 0)
            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($featuredProducts as $product)
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
            
            <!-- View All Products Button -->
            <div class="text-center">
                <a href="{{ route('products') }}" 
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Lihat Semua Produk
                </a>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600">Belum ada produk tersedia.</p>
                <a href="{{ route('products') }}" 
                   class="inline-block mt-4 bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Jelajahi Produk
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
    <!-- Swiper CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Banner Cards Swiper (for more than 4 banners)
            const bannerCardsSwiper = new Swiper('.banner-cards-swiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
    @endpush
</x-layouts.app>