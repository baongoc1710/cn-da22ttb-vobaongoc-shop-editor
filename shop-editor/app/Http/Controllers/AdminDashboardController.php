<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Thống kê doanh thu, chi phí, lợi nhuận
        // Chỉ tính các đơn hàng đã hoàn thành (completed)
        
        $stats = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->select(
                DB::raw('SUM(order_items.unit_price * order_items.quantity) as revenue'), // Doanh thu
                DB::raw('SUM((order_items.unit_cost + order_items.print_cost) * order_items.quantity) as cost'), // Chi phí (Vốn + In)
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->where('orders.status', 'completed')
            ->first();

        $profit = $stats->revenue - $stats->cost;

        return view('admin.dashboard', compact('stats', 'profit'));
    }
}