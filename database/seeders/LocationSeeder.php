<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Gedung A',
                'address' => 'Jl. Pendidikan No. 1',
                'area' => 500.00,
                'photo' => null
            ],
            [
                'name' => 'Gedung B',
                'address' => 'Jl. Pendidikan No. 2',
                'area' => 450.00,
                'photo' => null
            ],
            [
                'name' => 'Gedung C',
                'address' => 'Jl. Pendidikan No. 3',
                'area' => 400.00,
                'photo' => null
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
} 