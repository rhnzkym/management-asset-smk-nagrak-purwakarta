<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomLoan;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = ['name', 'location_id', 'length', 'width', 'area', 'photo', 'status'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'room_id');
    }
    
    public function roomLoans()
    {
        return $this->hasMany(RoomLoan::class, 'room_id');
    }
}
