<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'nama' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => 'active'
        ]);

        // Create Receptionist
        User::create([
            'nama' => 'Resepsionis',
            'username' => 'resepsionis',
            'email' => 'resepsionis@example.com',
            'password' => Hash::make('password123'),
            'role' => 'resepsionis',
            'status' => 'active'
        ]);

        // Create Students
        $students = [
            [
                'nama' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'john@example.com',
                'jurusan' => 'Teknik Komputer Jaringan',
                'nis' => '12345',
                'nomor_telpon' => '081234567890'
            ],
            [
                'nama' => 'Jane Smith',
                'username' => 'janesmith',
                'email' => 'jane@example.com',
                'jurusan' => 'Agribisnis Tanaman Pangan dan Hortikultura',
                'nis' => '12346',
                'nomor_telpon' => '081234567891'
            ],
            [
                'nama' => 'Mike Johnson',
                'username' => 'mikejohnson',
                'email' => 'mike@example.com',
                'jurusan' => 'Teknik Komputer Jaringan',
                'nis' => '12347',
                'nomor_telpon' => '081234567892'
            ]
        ];

        foreach ($students as $student) {
            User::create([
                'nama' => $student['nama'],
                'username' => $student['username'],
                'email' => $student['email'],
                'jurusan' => $student['jurusan'],
                'nis' => $student['nis'],
                'nomor_telpon' => $student['nomor_telpon'],
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active'
            ]);
        }
    }
} 