<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        
        $products = [
            [
                'name' => 'Kaos Polos Premium',
                'description' => 'Kaos polos berkualitas tinggi dengan bahan cotton combed 30s yang nyaman dipakai sehari-hari.',
                'price' => 85000,
                'stock_quantity' => 50,
                'category_id' => $categories->where('name', 'Kaos')->first()?->id ?? 1,
                'image_url' => 'https://via.placeholder.com/400x400/FF6B6B/FFFFFF?text=Kaos+Polos'
            ],
            [
                'name' => 'Kemeja Formal Slim Fit',
                'description' => 'Kemeja formal dengan potongan slim fit, cocok untuk acara resmi dan kantor.',
                'price' => 150000,
                'stock_quantity' => 30,
                'category_id' => $categories->where('name', 'Kemeja')->first()?->id ?? 1,
                'image_url' => 'https://via.placeholder.com/400x400/4ECDC4/FFFFFF?text=Kemeja+Formal'
            ],
            [
                'name' => 'Celana Jeans Denim',
                'description' => 'Celana jeans denim berkualitas dengan model regular fit yang cocok untuk berbagai aktivitas.',
                'price' => 200000,
                'stock_quantity' => 25,
                'category_id' => $categories->where('name', 'Celana')->first()?->id ?? 1,
                'image_url' => 'https://via.placeholder.com/400x400/45B7D1/FFFFFF?text=Celana+Jeans'
            ],
            [
                'name' => 'Jaket Hoodie Casual',
                'description' => 'Jaket hoodie dengan bahan fleece yang hangat dan nyaman untuk cuaca dingin.',
                'price' => 180000,
                'stock_quantity' => 20,
                'category_id' => $categories->where('name', 'Jaket')->first()?->id ?? 1,
                'image_url' => 'https://via.placeholder.com/400x400/96CEB4/FFFFFF?text=Jaket+Hoodie'
            ],
            [
                'name' => 'Dress Casual Wanita',
                'description' => 'Dress casual dengan model A-line yang cocok untuk berbagai acara santai.',
                'price' => 120000,
                'stock_quantity' => 35,
                'category_id' => $categories->where('name', 'Dress')->first()?->id ?? 1,
                'image_url' => 'https://via.placeholder.com/400x400/FFEAA7/FFFFFF?text=Dress+Casual'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
