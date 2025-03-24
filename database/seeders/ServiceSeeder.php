<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB
use Carbon\Carbon; // Import Carbon để dùng now()

class ServiceSeeder extends Seeder
{
    public function run()
    {
        DB::table('services')->insert([
            ['name' => 'Bia Heineken', 'price' => 25000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bia Tiger', 'price' => 22000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bia Sài Gòn', 'price' => 18000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Khoai tây chiên', 'price' => 50000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gà rán', 'price' => 120000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Xúc xích chiên', 'price' => 40000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Đậu phộng', 'price' => 30000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Nước ngọt Coca', 'price' => 15000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Nước ngọt Pepsi', 'price' => 15000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Nước suối', 'price' => 10000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
