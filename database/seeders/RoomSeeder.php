<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Location;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $locations = Location::all();
        
        // Define an array of school room names (one per location)
        $schoolRooms = [
            'Kelas 1',
            'Ruang Ibadah',
            'Ruang Kepala Sekolah',
            'Laboratorium Komputer',
            'Perpustakaan',
            'Ruang Guru',
        ];
        
        // Loop through each location and assign one room type to each
        $i = 0;
        foreach ($locations as $location) {
            // Use modulo to cycle through room types if more locations than room types
            $roomIndex = $i % count($schoolRooms);
            
            // Create one room for this location
            Room::create([
                'name' => $schoolRooms[$roomIndex],
                'location_id' => $location->id,
                'length' => 10.00,
                'width' => 8.00,
                'area' => 80.00,
                'photo' => null,
                'status' => true
            ]);
            
            $i++;
        }
    }
} 