<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id',
        'room_name',
        'check_in_time',
        'check_out_time',
        'total_time_used', // Thêm trường này
        'total_price',
        'room_price',
        'service_price',
        'payment_method',
        'customer_paid',
        'change_amount',
        ];
    public function orderServices()
    {
        return $this->hasMany(OrderService::class, 'order_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

}
