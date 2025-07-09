<div class="relative h-screen overflow-hidden">
    <!-- Swiper Container -->
    <div class="swiper hero-swiper h-full">
        <div class="swiper-wrapper">
            @foreach($banners as $banner)
                <div class="swiper-slide relative">
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <img src="{{ $banner->image_url }}" 
                             alt="{{ $banner->title }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                    </div>
                    
                    <!-- Content -->
                    <div class="relative z-10 h-full flex items-center justify-center">
                        <div class="text-center text-white max-w-4xl mx-auto px-4">
                            @if($banner->title)
                                <h1 class="text-5xl md:text-7xl font-bold mb-4" style="font-family: 'Papyrus', cursive;">
                                    {{ $banner->title }}
                                </h1>
                            @endif
                            
                            @if($banner->subtitle)
                                <h2 class="text-2xl md:text-3xl mb-6 font-light">
                                    {{ $banner->subtitle }}
                                </h2>
                            @endif
                            
                            @if($banner->description)
                                <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto leading-relaxed">
                                    {{ $banner->description }}
                                </p>
                            @endif
                            
                            @if($banner->button_text && $banner->button_link)
                                <a href="{{ $banner->button_link }}" 
                                   class="inline-block bg-white text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    {{ $banner->button_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Navigation -->
        @if($banners->count() > 1)
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next text-white"></div>
            <div class="swiper-button-prev text-white"></div>
        @endif
    </div>
</div>

<!-- Swiper CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
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