<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->cartCount = CartService::getCount();
    }

    #[On('cart-updated')]
    public function updateCount()
    {
        $this->cartCount = CartService::getCount();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}