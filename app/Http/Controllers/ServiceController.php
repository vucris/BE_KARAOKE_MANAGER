<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::all();
        return response()->json(Service::all());
    }

    public function store(Request $request)

    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|max:20000000'
        ], [
            'price.max' => 'Giá dịch vụ không được vượt quá 20,000,000 VNĐ.'
        ]);
        $service = Service::create([
            'name' => $request->name,
            'price' => $request->price
        ]);
        return response()->json([
            'message' => ' Thêm sản pẩm mới vào thực đơn  thành công!',
            'data' => $service
        ], 201);
    }
    // xóa dịch vụ
    public function destroy($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'dich vụ không tồn tại'],404);
        }

        $service->delete();
        return response()->json(['message' => 'Xóa dịch vụ thành công'],200);
    }
}

