<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['name' => 'Phòng VIP 1', 'type' => 'VIP', 'price_per_hour' => 500000, 'status' => 'Trống'],
            ['name' => 'Phòng VIP 2', 'type' => 'VIP', 'price_per_hour' => 600000, 'status' => 'Trống'],
            ['name' => 'Phòng Thường 1', 'type' => 'Thường', 'price_per_hour' => 200000, 'status' => 'Trống'],
            ['name' => 'Phòng Thường 2', 'type' => 'Thường', 'price_per_hour' => 250000, 'status' => 'Trống'],
            ['name' => 'Phòng Thường 3', 'type' => 'Thường', 'price_per_hour' => 250000, 'status' => 'Trống'],
            ['name' => 'Phòng VIP 3', 'type' => 'VIP', 'price_per_hour' => 700000, 'status' => 'Trống'],
            ['name' => 'Phòng Thường 4', 'type' => 'Thường', 'price_per_hour' => 200000, 'status' => 'Đang sử dụng'],
            ['name' => 'Phòng Thường 5', 'type' => 'Thường', 'price_per_hour' => 300000, 'status' => 'Bảo trì'],
            ['name' => 'Phòng VIP 4', 'type' => 'VIP', 'price_per_hour' => 800000, 'status' => 'Trống'],
            ['name' => 'Phòng VIP 5', 'type' => 'VIP', 'price_per_hour' => 900000, 'status' => 'Trống'],
        ];

        DB::table('rooms')->insert($rooms);
    }
}
