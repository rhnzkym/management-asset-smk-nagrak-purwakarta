<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama', 'username', 'jurusan', 'nomor_telpon', 'email', 'nis', 'nip', 'password', 'role', 'status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relasi ke semua peminjaman item (baik manual maupun dari pinjam ruangan)
    public function itemLoans()
    {
        return $this->hasMany(ItemLoan::class);
    }

    // Relasi ke semua peminjaman ruangan
    public function roomLoans()
    {
        return $this->hasMany(RoomLoan::class);
    }
}
