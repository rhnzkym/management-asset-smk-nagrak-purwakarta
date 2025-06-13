<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomLoan;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class RoomLoanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil beberapa user siswa
        $students = User::where('role', 'user')->take(3)->get();
        
        // Ambil beberapa ruangan
        $rooms = Room::take(3)->get();

        // Status yang tersedia
        $statuses = ['pending', 'pinjam', 'kembali'];

        // Buat 8 data peminjaman ruangan
        for ($i = 0; $i < 8; $i++) {
            $user = $students->random();
            $room = $rooms->random();
            $status = $statuses[array_rand($statuses)];
            
            // Generate tanggal peminjaman (dalam 30 hari terakhir)
            $borrowDate = Carbon::now()->subDays(rand(0, 30));
            
            // Generate tanggal pengembalian (jika status 'kembali')
            $returnDate = $status === 'kembali' ? $borrowDate->copy()->addDays(rand(1, 7)) : null;
            
            // Generate tanggal jatuh tempo (7 hari setelah peminjaman)
            $dueDate = $borrowDate->copy()->addDays(7);

            RoomLoan::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'tanggal_pinjam' => $borrowDate,
                'tanggal_kembali' => $returnDate,
                'tanggal_jatuh_tempo' => $dueDate,
                'status' => $status,
                'keterangan' => 'Data seeder untuk testing',
                'created_at' => $borrowDate,
                'updated_at' => $returnDate ?? $borrowDate,
            ]);
        }
    }
} 