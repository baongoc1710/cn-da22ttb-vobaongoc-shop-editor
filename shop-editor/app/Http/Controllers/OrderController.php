<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SavedDesign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. Thêm vào giỏ hàng (Lưu Session)
    public function addToCart(Request $request)
    {
        $designId = $request->design_id;
        $design = SavedDesign::with(['productColor.product'])->find($designId);

        if (!$design) return back()->with('error', 'Lỗi thiết kế');

        $cart = session()->get('cart', []);

        // Key duy nhất cho mỗi dòng trong giỏ
        $cartKey = $designId . '_' . $request->size;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $product = $design->productColor->product;
            // Tính giá: Giá gốc + Phụ phí Size (nếu có logic tính size)
            $price = $product->base_price;
            if ($request->size == 'XXL') $price += 15000;
            if ($request->size == 'XL') $price += 10000;

            $cart[$cartKey] = [
                "design_id" => $designId,
                "name" => $design->design_name,
                "product_name" => $product->name,
                // QUAN TRỌNG: Kiểm tra cột này trong Database bảng saved_designs có dữ liệu không
                "image_front" => $design->front_preview_img,
                "image_back"  => $design->back_preview_img,
                "quantity" => $request->quantity,
                "size" => $request->size,
                "price" => $price,
                "cost" => $product->import_price
            ];
        }

        session()->put('cart', $cart);
        // THÊM DÒNG NÀY: Tính tổng số lượng để trả về cho Ajax
        $totalQty = 0;
        foreach ($cart as $item) {
            $totalQty += $item['quantity']; // Hoặc dùng count($cart) nếu muốn đếm số loại sản phẩm
        }

        // Trả về JSON chứa số lượng mới
        return response()->json([
            'success' => 'Thêm thành công',
            'cart_count' => count($cart)
        ]);
        // return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // 2. Trang Checkout & Xử lý đặt hàng
    public function checkout(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) return redirect()->route('home');

        // Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.checkout', compact('cart', 'total'));
    }

    public function placeOrder(Request $request)
    {
        // 1. TẠM ẨN DÒNG NÀY ĐỂ CODE CHẠY TIẾP
        // dd($request->all()); 

        // Validate dữ liệu
        $request->validate([
            'customer_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $cart = session()->get('cart');

        // Debug 1: Kiểm tra xem giỏ hàng có dữ liệu không
        if (!$cart) {
            dd("Giỏ hàng đang trống! Session cart không có dữ liệu.");
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Debug 2: Kiểm tra User ID (Rất quan trọng)
            // Nếu Auth::id() null và User ID 1 không tồn tại trong DB, code sẽ lỗi ngay tại đây.
            $userId = Auth::id() ?? 1;

            // Kiểm tra xem User ID 1 có tồn tại không (nếu là khách vãng lai)
            if (!\App\Models\User::find($userId)) {
                // Nếu chưa có user nào, tạo tạm user ảo để test
                $newUser = \App\Models\User::create([
                    'name' => 'Guest',
                    'email' => 'guest' . time() . '@test.com',
                    'password' => bcrypt('123'),
                    'role' => 'customer'
                ]);
                $userId = $newUser->id;
            }

            // Tạo Order
            $order = Order::create([
                'user_id' => $userId,
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'shipping_address' => $request->address,
                'note' => $request->note,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method ?? 'COD',
                'status' => 'pending'
            ]);

            // Tạo Order Items
            foreach ($cart as $key => $item) {
                // Debug 3: Kiểm tra từng item xem có đủ key không
                if (!isset($item['design_id']) || !isset($item['cost'])) {
                    // dd("Lỗi dữ liệu Item tại key: $key. Thiếu design_id hoặc cost", $item);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'saved_design_id' => $item['design_id'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'unit_cost' => $item['cost'],
                    'print_cost' => 15000,
                ]);
            }

            DB::commit();
            session()->forget('cart');

            // === THÀNH CÔNG ===
            // Thay vì redirect, ta dùng dd() để xem kết quả Order vừa tạo
            // dd("ĐÃ THÊM THÀNH CÔNG! Check Database bảng Orders ID: " . $order->id, $order->load('items'));

            return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công! Mã đơn: #' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            // === CÓ LỖI ===
            // Hiển thị chi tiết lỗi để sửa
            // dd("CÓ LỖI XẢY RA (Transaction Rolled Back): " . $e->getMessage() . " - Tại dòng: " . $e->getLine());
        }
    }

    // API: Cập nhật số lượng
    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                $cart[$request->id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Đã cập nhật giỏ hàng!');
        }
    }

    // API: Xóa sản phẩm khỏi giỏ
    public function removeCart(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Đã xóa sản phẩm!');
        }
    }

    // --- THÊM VÀO CUỐI FILE OrderController ---

    // 1. Danh sách đơn hàng của tôi
    public function index()
    {
        // Nếu chưa đăng nhập thì bắt đăng nhập (hoặc xử lý cho khách vãng lai sau)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem đơn hàng.');
        }

        // Lấy đơn hàng của user hiện tại, mới nhất xếp trước
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    // 2. Xem chi tiết đơn hàng
    public function show($id)
    {
        // Load đơn hàng kèm theo các món (items) và thông tin thiết kế
        $order = Order::with('items')->findOrFail($id);

        // Bảo mật: Không cho xem đơn của người khác
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('orders.show', compact('order'));
    }
    // Xử lý hủy đơn hàng
    public function cancel($id)
    {
        // 1. Tìm đơn hàng của user hiện tại
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // 2. Kiểm tra điều kiện: Chỉ hủy được đơn 'pending'
        if ($order->status == 'pending') {
            $order->status = 'cancelled';
            $order->save();

            return back()->with('success', 'Đã hủy đơn hàng thành công.');
        }

        // 3. Nếu đơn đã xử lý rồi thì báo lỗi
        return back()->with('error', 'Không thể hủy đơn hàng này do đã được xử lý hoặc đang vận chuyển.');
    }
}
