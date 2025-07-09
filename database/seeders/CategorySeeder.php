<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kaos Pria',
                'description' => 'Koleksi kaos untuk pria dengan berbagai model dan warna'
            ],
            [
                'name' => 'Kaos Wanita',
                'description' => 'Koleksi kaos untuk wanita dengan desain trendy'
            ],
            [
                'name' => 'Kemeja',
                'description' => 'Kemeja formal dan casual untuk berbagai acara'
            ],
            [
                'name' => 'Jaket',
                'description' => 'Jaket dan outerwear untuk cuaca dingin'
            ],
            [
                'name' => 'Celana',
                'description' => 'Celana jeans, chino, dan celana kasual lainnya'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true
            ]);
        }
    }
}
