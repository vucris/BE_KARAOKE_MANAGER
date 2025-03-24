<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUsage;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Lấy danh sách phòng
    public function index()
    {
        $rooms = Room::all();
        return response()->json([
            'message' => 'Danh sách phòng hát',
            'rooms' => $rooms
        ], 200);
    }

    // Bắt đầu sử dụng phòng
    public function startRoom(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        if ($room->status !== 'Trống') {
            return response()->json(['message' => 'Phòng này đã được sử dụng!'], 400);
        }


        $checkInTime = now(); // Lấy giờ từ request hoặc mặc định là `now()`
        $roomUsage = RoomUsage::create([
            'room_id' => $id,
            'check_in_time' => $checkInTime,
        ]);

        $room->update(['status' => 'Đang sử dụng']);
        return response()->json([
            'message' => 'Bắt đầu hát!',
            'room_id' => $room->id,
            'room_name' => $room->name,
            'status' => $room->status,
            'check_in_time' => $roomUsage->check_in_time, // Trả về giờ vào
        ]);
    }

    // Dừng phòng và tính tiền
    public function stopRoom($id)
    {
        $roomUsage = RoomUsage::where('room_id', $id)->whereNull('check_out_time')->firstOrFail();
        $room = Room::findOrFail($id);

        $checkOutTime = now(); // Lấy giờ hiện tại trên server
        $checkInTime = $roomUsage->check_in_time;

        // Tính tổng số phút sử dụng
        $minutesUsed = (strtotime($checkOutTime) - strtotime($checkInTime)) / 60;

        // Tính tiền theo phút
        $pricePerMinute = $room->price_per_hour / 60;
        $totalPrice = $minutesUsed * $pricePerMinute;

        $roomUsage->update([
            'check_out_time' => $checkOutTime,
            'total_price' => round($totalPrice, 2) // Làm tròn tiền đến 2 số thập phân
        ]);

        $room->update(['status' => 'Trống']);

        return response()->json([
            'message' => 'Tính tiền thành công!',
            'total_price' => round($totalPrice, 2),
            'minutes_used' => round($minutesUsed, 2),
            'check_out_time' => $checkOutTime
        ]);
    }

    public function show($id)
    {
        $room = Room::with('roomUsage')->findOrFail($id); // Lấy thông tin phòng cùng với thông tin sử dụng phòng (nếu có)

        return response()->json([
            'id' => $room->id,
            'name' => $room->name,
            'status' => $room->status,
            'check_in_time' => optional($room->roomUsage)->check_in_time, // Lấy giờ vào từ bảng room_usages
            'check_out_time' => optional($room->roomUsage)->check_out_time, // Lấy giờ ra (nếu có)
            'total_price' => optional($room->roomUsage)->total_price, // Tổng tiền (nếu có)
        ]);
    }
}
