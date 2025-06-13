<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'cat_name' => 'Elektronik',
                'cat_code' => 'ELK'
            ],
            [
                'cat_name' => 'Furniture',
                'cat_code' => 'FRN'
            ],
            [
                'cat_name' => 'Alat Tulis',
                'cat_code' => 'ATK'
            ],
            [
                'cat_name' => 'Peralatan Olahraga',
                'cat_code' => 'ORL'
            ],
            [
                'cat_name' => 'Peralatan Laboratorium',
                'cat_code' => 'LAB'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 