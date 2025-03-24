<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services'; // Tên bảng trong database

    protected $fillable = ['name', 'price']; // Chỉ có name và price

    public $timestamps = true; // Sử dụng created_at và updated_at
}
