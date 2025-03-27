<?php
namespace App\Http\Controllers;
use App\Models\OrderService;
use App\Models\Room;
use App\Models\RoomCart;
use App\Models\RoomUsage;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $checkInTime = Carbon::now();
        $roomUsage = RoomUsage::create([
            'room_id' => $id,
            'check_in_time' => $checkInTime,
        ]);
        $room->update(['status' => 'Đang sử dụng']);
        return response()->json([
            'message' => 'Phòng đã được bật !',
            'room_id' => $room->id,
            'room_name' => $room->name,
            'status' => $room->status,
            'check_in_time' => $roomUsage->check_in_time,
        ]);
    }
    // Lấy giá tiền hiện tại
    public function checkoutRoom(Request $request, $roomId)
    {
        // 1️⃣ Kiểm tra phòng có tồn tại không
        $room = Room::find($roomId);
        if (!$room) {
            return response()->json(['message' => 'Phòng không tồn tại'], 404);
        }

        // 2️⃣ Kiểm tra phòng có đang được sử dụng không
        if ($room->status !== 'Đang sử dụng') {
            return response()->json(['message' => 'Phòng chưa có khách hoặc đã thanh toán'], 400);
        }

        // 3️⃣ Lấy thông tin sử dụng phòng (check_in_time từ bảng room_usages)
        $roomUsage = RoomUsage::where('room_id', $roomId)->first();
        if (!$roomUsage || !$roomUsage->check_in_time) {
            return response()->json(['message' => 'Không tìm thấy thời gian check-in của phòng'], 400);
        }

        // 4️⃣ Tính tổng thời gian sử dụng
        $checkInTime = Carbon::parse($roomUsage->check_in_time);
        $checkOutTime = Carbon::now();
        $totalMinutes = $checkInTime->diffInMinutes($checkOutTime);

        if ($totalMinutes <= 0) {
            return response()->json(['message' => 'Thời gian sử dụng phòng không hợp lệ'], 400);
        }

        // 5️⃣ Tính tiền giờ hát
        $pricePerHour = $room->price_per_hour; // Lấy giá phòng
        $totalRoomPrice = ($totalMinutes / 60) * $pricePerHour;

        // 6️⃣ Lấy danh sách dịch vụ trong giỏ hàng
        $roomCart = RoomCart::where('room_id', $roomId)->with('service')->get();

        // 7️⃣ Tính tổng tiền dịch vụ, nếu giỏ hàng trống thì bằng 0
        $totalServicePrice = 0;
        if (!$roomCart->isEmpty()) {
            foreach ($roomCart as $cartItem) {
                $totalServicePrice += $cartItem->service->price * $cartItem->quantity;
            }
        }

        // 8️⃣ Tổng tiền cuối cùng (chỉ có tiền phòng nếu giỏ hàng trống)
        $finalTotalPrice = $totalRoomPrice + $totalServicePrice;

        // 9️⃣ Tính tổng thời gian sử dụng (giờ & phút)
        $totalHours = floor($totalMinutes / 60);
        $totalRemainingMinutes = $totalMinutes % 60;
        $totalTimeUsed = "{$totalHours} giờ {$totalRemainingMinutes} phút";

        DB::beginTransaction();
        try {
            // 🔟 Lưu vào `order_details`
            $order = OrderDetail::create([
                'room_id' => $roomId,
                'room_name' => $room->name,
                'check_in_time' => $checkInTime,
                'check_out_time' => $checkOutTime,
                'total_time_used' => $totalTimeUsed,
                'room_price' => round($totalRoomPrice, 2), // Lưu tiền hát riêng
                'service_price' => round($totalServicePrice, 2), // Lưu tiền dịch vụ riêng
                'total_price' => round($finalTotalPrice, 2),
                'payment_method' => $request->payment_method ?? 'Tiền mặt',
                'customer_paid' => $request->customer_paid,
                'change_amount' => max(0, $request->customer_paid - round($finalTotalPrice, 2)),
            ]);

            // 1️⃣1️⃣ Lưu dịch vụ từ giỏ hàng vào `order_services` (chỉ khi có dịch vụ)
            if (!$roomCart->isEmpty()) {
                foreach ($roomCart as $cartItem) {
                    OrderService::create([
                        'order_id' => $order->id,
                        'service_id' => $cartItem->service_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->service->price * $cartItem->quantity,
                    ]);
                }
            }

            // 1️⃣2️⃣ Xóa giỏ hàng sau khi thanh toán
            RoomCart::where('room_id', $roomId)->delete();

            // 1️⃣3️⃣ Cập nhật trạng thái phòng
            $room->update(['status' => 'Trống']);

            // 1️⃣4️⃣ Xóa dữ liệu phòng khỏi bảng `room_usages`
            RoomUsage::where('room_id', $roomId)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Thanh toán thành công',
                'data' => $order
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Lỗi thanh toán', 'error' => $e->getMessage()], 500);
        }
    }


    public function getRoomCurrentPrice($roomId)
    {
        // Kiểm tra phòng có tồn tại không
        $room = Room::find($roomId);
        if (!$room) {
            return response()->json(['message' => 'Phòng không tồn tại'], 404);
        }

        // Lấy thông tin check-in từ room_usages
        $roomUsage = RoomUsage::where('room_id', $roomId)->first();
        if (!$roomUsage || !$roomUsage->check_in_time) {
            return response()->json(['message' => 'Không tìm thấy thời gian check-in'], 400);
        }

        // Tính thời gian đã sử dụng
        $checkInTime = Carbon::parse($roomUsage->check_in_time);
        $currentTime = Carbon::now();
        $totalMinutes = $checkInTime->diffInMinutes($currentTime);

        // Nếu dưới 2 phút, trả về 0
        if ($totalMinutes < 2) {
            return response()->json([
                'message' => 'Chưa đủ 2 phút sử dụng',
                'total_price' => 0
            ], 200);
        }

        // Tính tiền dựa trên giá phòng theo giờ
        $pricePerHour = $room->price_per_hour;
        $totalPrice = ($totalMinutes / 60) * $pricePerHour;

        return response()->json([
            'message' => 'Tổng tiền hát hiện tại',
            'total_price' => round($totalPrice, 2)
        ], 200);
    }

    // Xem thông tin phòng
    public function show($id)
    {
        $room = Room::with(['roomUsage' => function ($query) {
            $query->latest()->first();
        }])->findOrFail($id);

        return response()->json([
            'id' => $room->id,
            'name' => $room->name,
            'status' => $room->status,
            'check_in_time' => optional($room->roomUsage)->check_in_time,
            'check_out_time' => optional($room->roomUsage)->check_out_time,
            'total_price' => optional($room->roomUsage)->total_price,
        ]);
    }
}
