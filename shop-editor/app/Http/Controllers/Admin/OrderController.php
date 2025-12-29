<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. Danh sách đơn hàng
    public function index()
    {
        // Lấy đơn hàng mới nhất, kèm thông tin user để hiển thị tên
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // 2. Chi tiết đơn hàng & Form sửa
    public function show($id)
    {
        // Load chi tiết: Items -> SavedDesign (để lấy ảnh)
        $order = Order::with(['user', 'items.savedDesign'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // 3. Cập nhật đơn hàng (Sửa trạng thái, địa chỉ...)
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            // Sửa dòng này khớp với database của bạn
            'status' => 'required|in:pending,confirmed,printing,packing,shipping,completed,cancelled',
            'shipping_address' => 'required|string',
            'phone' => 'required|string',
        ]);

        $order->update([
            'status' => $request->status,
            'shipping_address' => $request->shipping_address,
            'phone' => $request->phone,
            'note' => $request->note
        ]);

        return back()->with('success', 'Cập nhật đơn hàng thành công!');
    }
    // Hàm in phiếu giao hàng hàng loạt
    public function printLabels(Request $request)
    {
        // 1. Khởi tạo query chỉ lấy đơn đang đóng gói
        $query = Order::with(['items.savedDesign', 'user'])
            ->where('status', 'packing'); // Chỉ in đơn đang đóng gói

        // 2. Lọc theo ngày (nếu có chọn)
        if ($request->from_date && $request->to_date) {
            // from_date 00:00:00 đến to_date 23:59:59
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        $orders = $query->latest()->get();

        // 3. Trả về view riêng biệt để in (không dùng layout admin chung)
        return view('admin.orders.print_template', compact('orders', 'request'));
    }
}
