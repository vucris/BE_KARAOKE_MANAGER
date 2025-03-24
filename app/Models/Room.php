<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms'; // Tên bảng trong database

    protected $fillable = [
        'name',
        'type',
        'price',
        'status',
    ];

    public function roomUsage()
    {
        return $this->hasOne(RoomUsage::class, 'room_id');
    }
}
