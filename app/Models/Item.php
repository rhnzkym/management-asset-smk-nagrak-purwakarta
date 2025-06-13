<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = ['cat_id', 'room_id', 'item_name', 'conditions', 'qty', 'photo', 'good_qty', 'broken_qty', 'lost_qty', 'is_borrowable', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function itemLoans()
    {
        return $this->hasMany(ItemLoan::class, 'item_id');
    }
}
