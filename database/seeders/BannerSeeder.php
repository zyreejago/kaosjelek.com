<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::create([
            'title' => 'kaosjelek',
            'subtitle' => 'Fashion Unik untuk Gaya Anda',
            'description' => 'Temukan koleksi kaos dengan desain unik dan kualitas terbaik. Ekspresikan kepribadian Anda dengan gaya yang berbeda.',
            'button_text' => 'Belanja Sekarang',
            'button_link' => '/products',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        
        Banner::create([
            'title' => 'Koleksi Terbaru',
            'subtitle' => 'Desain Eksklusif 2024',
            'description' => 'Dapatkan kaos dengan desain terbaru dan tren fashion masa kini. Limited edition dengan kualitas premium.',
            'button_text' => 'Lihat Koleksi',
            'button_link' => '/products?featured=1',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}