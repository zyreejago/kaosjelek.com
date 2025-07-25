<x-layouts.app>
    <!-- Banner Section -->
    @if($banners->count() > 0)
        <div class="container mx-auto px-4 py-8">
            @if($banners->count() <= 2)
                <!-- Static Grid for 1 or 2 banners -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    @foreach($banners as $banner)
                        <div class="relative cursor-pointer">
                            <!-- Banner Card -->
                            <div class="aspect-[1/0.8] bg-white rounded-lg shadow-md overflow-hidden">
                                @php
                                    $media = $banner->getFirstMedia('banner_images');
                                    $imagePath = $media ? $media->id . '/' . $media->file_name : null;
                                @endphp
                                
                                @if($imagePath)
                                    <img src="/storage/{{ $imagePath }}" 
                                         alt="{{ $banner->title }}" 
                                         class="w-full h-full object-cover max-w-[700px] max-h-[700px] sm:max-w-none sm:max-h-none">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ substr($banner->title, 0, 1) }}</span>
                                    </div>
                                @endif
                                
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
                <!-- Swiper for more than 2 banners -->
                <div class="relative">
                    <div class="swiper banner-cards-swiper">
                        <div class="swiper-wrapper">
                            @foreach($banners->chunk(2) as $bannerChunk)
                                <div class="swiper-slide">
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach($bannerChunk as $banner)
                                            <div class="relative group cursor-pointer">
                                                <!-- Banner Card -->
                                                <div class="aspect-square bg-white rounded-lg shadow-md overflow-hidden">
                                                    @php
                                                        $media = $banner->getFirstMedia('banner_images');
                                                        $imagePath = $media ? $media->id . '/' . $media->file_name : null;
                                                    @endphp
                                                    
                                                    @if($imagePath)
                                                        <img src="/storage/{{ $imagePath }}" 
                                                             alt="{{ $banner->title }}" 
                                                             class="w-full h-full object-cover transition-transform duration-300 ">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                                            <span class="text-white font-bold text-lg">{{ substr($banner->title, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Overlay -->
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                                                    
                                                    <!-- Content Overlay -->
                                                    <!-- Menghapus bagian ini untuk menghilangkan judul dan subjudul saat hover -->
                                                    
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
            <!-- Products Grid - 2 columns on mobile, smaller size -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6 mb-8">
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
                        
                        <!-- Product Info - Simplified for mobile -->
                        <div class="p-3 md:p-5">
                            <!-- Category -->
                            <div class="mb-1 md:mb-2">
                                <span class="text-xs text-blue-600 uppercase tracking-wider font-semibold bg-blue-50 px-2 py-1 rounded-full">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                            
                            <!-- Product Name -->
                            <h3 class="text-sm md:text-lg font-bold text-gray-900 mb-2 md:mb-3 line-clamp-2 min-h-[2.5rem] md:min-h-[3.5rem]" style="font-family: 'Papyrus', cursive;">
                                {{ $product->name }}
                            </h3>
                            
                            <!-- Price -->
                            <div class="mb-2 md:mb-4">
                                @if($product->sale_price)
                                    <div class="flex items-center space-x-1 md:space-x-2">
                                        <span class="text-base md:text-xl font-bold text-red-600">
                                            Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs md:text-sm text-gray-500 line-through">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-base md:text-xl font-bold text-blue-600">
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

    <style>
        /* Mobile Banner Adjustments */
        @media (max-width: 639px) {
            /* Smaller banner items */
            .swiper-slide .grid .relative .aspect-square {
                max-width: 120px !important;
                max-height: 120px !important;
            }
            
            /* Reduce padding and font size for badges */
            .swiper-slide .grid .relative .absolute .bg-red-500 {
                padding: 0.15rem 0.5rem !important;
                font-size: 0.65rem !important;
            }
            
            /* Reduce gap between items */
            .swiper-slide .grid {
                gap: 0.5rem !important;
            }
            
            /* Smaller navigation buttons */
            .swiper-button-next, .swiper-button-prev {
                transform: scale(0.7);
            }
            
            /* Reduce content overlay padding */
            .swiper-slide .grid .relative .absolute.bottom-0 {
                padding: 0.5rem !important;
            }
            
            /* Smaller text in overlay */
            .swiper-slide .grid .relative .absolute.bottom-0 h3 {
                font-size: 0.7rem !important;
            }
            
            .swiper-slide .grid .relative .absolute.bottom-0 p {
                font-size: 0.65rem !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Banner Cards Swiper (for more than 2 banners)
            const bannerCardsSwiper = new Swiper('.banner-cards-swiper', {
                slidesPerView: 2,
                spaceBetween: 10,
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