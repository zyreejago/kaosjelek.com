<a href="{{ route('cart') }}" class="relative inline-flex items-center p-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-lg transition-all duration-200 group">
    <!-- Ikon keranjang yang lebih bagus -->
    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z"></path>
    </svg>
    
    <!-- Counter badge yang lebih menarik -->
    @if($cartCount > 0)
        <span class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-lg transform scale-110 animate-bounce">
            {{ $cartCount > 99 ? '99+' : $cartCount }}
        </span>
    @endif
</a>