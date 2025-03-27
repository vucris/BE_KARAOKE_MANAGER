<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCart extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'service_id', 'quantity'];
    protected $with = ['service']; // Tự động load service khi lấy giỏ hàng
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
