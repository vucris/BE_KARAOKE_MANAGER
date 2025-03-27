<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',     // ID của đơn hàng
        'service_id',   // ID của dịch vụ
        'quantity',     // Số lượng dịch vụ
        'price',        // Giá mỗi đơn vị dịch vụ tại thời điểm thanh toán
        'total_price',  // Tổng tiền của dịch vụ (quantity * price)
    ];


    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_id');
    }
}
