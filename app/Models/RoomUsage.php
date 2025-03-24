<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomUsage extends Model
{
    use HasFactory;

    protected $table = 'room_usages'; // Tên bảng trong database

    protected $fillable = [
        'room_id',
        'check_in_time',
        'check_out_time',
        'total_price',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
