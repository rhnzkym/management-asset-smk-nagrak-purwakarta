<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            RoomSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            UserSeeder::class,
        ]);
    }
} 