<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;
use Livewire\Attributes\On;

class ShoppingCart extends Component
{
    public $cartItems = [];
    public $cartCount = 0;
    public $cartTotal = 0;

    public function mount()
    {
        $this->loadCart();
    }

    #[On('cart-updated')]
    public function loadCart()
    {
        $this->cartItems = CartService::getCart();
        $this->cartCount = CartService::getCount();
        $this->cartTotal = CartService::getTotal();
    }

    public function updateQuantity($productId, $quantity)
    {
        CartService::updateQuantity($productId, $quantity);
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function removeItem($productId)
    {
        CartService::remove($productId);
        $this->loadCart();
        $this->dispatch('cart-updated');
        session()->flash('message', 'Produk berhasil dihapus dari keranjang.');
    }

    public function clearCart()
    {
        CartService::clear();
        $this->loadCart();
        $this->dispatch('cart-updated');
        session()->flash('message', 'Keranjang berhasil dikosongkan.');
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
