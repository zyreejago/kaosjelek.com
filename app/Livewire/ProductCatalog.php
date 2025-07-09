<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;
    
    public $selectedCategory = null;
    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => null],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc']
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Product::with('category')
            ->where('stock_quantity', '>', 0)
            ->where('is_active', true);
            
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }
        
        $products = $query->orderBy($this->sortBy, $this->sortDirection)
                         ->paginate(12);
        
        $categories = Category::all();
        
        return view('livewire.product-catalog', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
