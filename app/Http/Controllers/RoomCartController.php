<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomCart;
use App\Models\Room;
use App\Models\Service;
class RoomCartController extends Controller
{
    // Lấy giỏ hàng của một phòng
    public function getRoomCart($roomId)
    {
        $cart = RoomCart::where('room_id', $roomId)->with('service')->get();
        return response()->json($cart);
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'items' => 'required|array',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        // Lấy danh sách service_id của các sản phẩm được gửi lên
        $serviceIds = collect($request->items)->pluck('service_id')->toArray();

        // Lấy giỏ hàng hiện có của phòng
        $existingCartItems = RoomCart::where('room_id', $request->room_id)
            ->whereIn('service_id', $serviceIds)
            ->get()
            ->keyBy('service_id');

        $newCartItems = [];

        foreach ($request->items as $item) {
            if (isset($existingCartItems[$item['service_id']])) {
                // Nếu sản phẩm đã có, cập nhật số lượng
                $existingCartItems[$item['service_id']]->quantity += $item['quantity'];
                $existingCartItems[$item['service_id']]->save();
            } else {
                // Nếu sản phẩm chưa có, thêm vào mảng để tạo mới
                $newCartItems[] = [
                    'room_id' => $request->room_id,
                    'service_id' => $item['service_id'],
                    'quantity' => $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Chèn những sản phẩm mới vào giỏ hàng
        if (!empty($newCartItems)) {
            RoomCart::insert($newCartItems);
        }

        // Trả về giỏ hàng mới nhất
        $updatedCart = RoomCart::where('room_id', $request->room_id)
            ->with('service') // Lấy thông tin sản phẩm
            ->get();

        return response()->json(['message' => 'Cập nhật giỏ hàng thành công', 'cart' => $updatedCart]);
    }


    // Cập nhật số lượng sản phẩm
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = RoomCart::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cập nhật giỏ hàng thành công']);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart($id)
    {
        RoomCart::findOrFail($id)->delete();
        return response()->json(['message' => 'Xóa sản phẩm khỏi giỏ hàng thành công']);
    }
}
