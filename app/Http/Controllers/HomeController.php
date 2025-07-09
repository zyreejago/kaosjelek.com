<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        // Ambil 8 produk terbaru untuk ditampilkan di home
        $featuredProducts = Product::with('category')
            ->where('stock_quantity', '>', 0)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
            
        return view('home', compact('banners', 'featuredProducts'));
    }
}