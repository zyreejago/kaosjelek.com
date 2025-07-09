<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Services\CartService;

class ProductDetail extends Component
{
    public Product $product;
    public $quantity = 1;
    public $selectedImage = 0;

    public function mount($slug)
    {
        $this->product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function selectImage($index)
    {
        $this->selectedImage = $index;
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->stock_quantity) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if (CartService::add($this->product->id, $this->quantity)) {
            $this->dispatch('cart-updated');
            session()->flash('message', 'Produk berhasil ditambahkan ke keranjang!');
        } else {
            session()->flash('error', 'Gagal menambahkan produk ke keranjang.');
        }
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
