<?php

namespace App\Http\Controllers;

use App\Models\Clipart;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductColor;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Lấy tất cả sản phẩm (hoặc dùng paginate(8) nếu muốn phân trang)
        $products = Product::all();
        // 2. Duyệt qua từng sản phẩm để lấy ảnh ngẫu nhiên
        foreach ($products as $product) {
            // Tìm 1 màu ngẫu nhiên thuộc sản phẩm này
            $randomColor = ProductColor::where('product_id', $product->id)
                ->inRandomOrder() // Random
                ->first();

            // Gán dữ liệu tạm thời vào đối tượng product để dùng ở View
            if ($randomColor) {
                $product->display_image = $randomColor->image_front;
                $product->random_color_id = $randomColor->id; // Lưu ID màu để lúc bấm thiết kế sẽ ra đúng màu đó
            } else {
                $product->display_image = null; // Hoặc ảnh mặc định
                $product->random_color_id = null;
            }

            // Đếm số lượng màu (Optional: để hiển thị "Có 5 màu sắc")
            $product->colors_count = ProductColor::where('product_id', $product->id)->count();
        }

        return view('index', compact('products'));
    }
    public function designer()
    {
        // Lấy tất cả sản phẩm kèm màu sắc để đổ vào #shirtList
        $products = Product::with('colors')->where('is_active', true)->get();

        // Lấy Cliparts để đổ vào Modal Gallery
        $cliparts = Clipart::all();

        return view('designer', compact('products', 'cliparts'));
    }
}
