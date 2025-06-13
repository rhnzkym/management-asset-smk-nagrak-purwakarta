<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Room;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();
        $categories = Category::all();
        
        $items = [
            [
                'name' => 'Laptop',
                'category' => 'Elektronik',
                'qty' => 5,
                'is_borrowable' => true
            ],
            [
                'name' => 'Proyektor',
                'category' => 'Elektronik',
                'qty' => 3,
                'is_borrowable' => true
            ],
            [
                'name' => 'Meja',
                'category' => 'Furniture',
                'qty' => 10,
                'is_borrowable' => false
            ],
            [
                'name' => 'Kursi',
                'category' => 'Furniture',
                'qty' => 20,
                'is_borrowable' => false
            ],
            [
                'name' => 'Buku Tulis',
                'category' => 'Alat Tulis',
                'qty' => 50,
                'is_borrowable' => true
            ],
            [
                'name' => 'Bola Basket',
                'category' => 'Peralatan Olahraga',
                'qty' => 5,
                'is_borrowable' => true
            ],
            [
                'name' => 'Mikroskop',
                'category' => 'Peralatan Laboratorium',
                'qty' => 3,
                'is_borrowable' => true
            ]
        ];

        foreach ($items as $itemData) {
            $category = $categories->firstWhere('cat_name', $itemData['category']);
            $room = $rooms->random();
            
            Item::create([
                'cat_id' => $category->id,
                'room_id' => $room->id,
                'item_name' => $itemData['name'],
                'qty' => $itemData['qty'],
                'good_qty' => $itemData['qty'],
                'broken_qty' => 0,
                'lost_qty' => 0,
                'photo' => null,
                'is_borrowable' => $itemData['is_borrowable'],
                'is_active' => true
            ]);
        }
    }
} 