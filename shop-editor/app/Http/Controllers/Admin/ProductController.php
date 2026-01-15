<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 1. Danh sách sản phẩm
    public function index()
    {
        // SỬA: Thêm with('colors') để load sẵn dữ liệu màu
        $products = Product::with('colors')->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }
    // 2. Form thêm mới
    public function create()
    {
        return view('admin.products.create');
    }

    // 4. Form sửa
    public function edit($id)
    {
        // Load sản phẩm kèm danh sách màu
        $product = Product::with('colors')->findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    // SỬA HÀM UPDATE: Chỉ update thông tin chung
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'import_price' => 'required|numeric',
            // Validate cho màu đầu tiên (tùy chọn)
            'first_color_name' => 'nullable|string',
            'first_hex_code' => 'nullable|string',
            'first_image_front' => 'nullable|image|max:2048',
            'first_image_back' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        
        // Tạo slug unique (tự động thêm số nếu trùng)
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $data['slug'] = $slug;

        // Tạo sản phẩm
        $product = Product::create($data);

        // Nếu có thông tin màu đầu tiên thì tạo luôn
        if ($request->first_color_name && $request->hasFile('first_image_front')) {
            $colorData = [
                'product_id' => $product->id,
                'color_name' => $request->first_color_name,
                'hex_code' => $request->first_hex_code ?? '#ffffff',
            ];

            // Upload ảnh mặt trước
            if ($request->hasFile('first_image_front')) {
                $path = $request->file('first_image_front')->store('products/colors', 'public');
                $colorData['image_front'] = 'storage/' . $path;
            }

            // Upload ảnh mặt sau (nếu có)
            if ($request->hasFile('first_image_back')) {
                $path = $request->file('first_image_back')->store('products/colors', 'public');
                $colorData['image_back'] = 'storage/' . $path;
            }

            ProductColor::create($colorData);
        }

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // Cập nhật luôn hàm update
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'import_price' => 'required|numeric',
        ]);

        // Tạo slug unique (bỏ qua sản phẩm hiện tại)
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'base_price' => $request->base_price,
            'import_price' => $request->import_price,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        return back()->with('success', 'Cập nhật thành công!');
    }
    // === HÀM MỚI: THÊM MÀU ===
    public function storeColor(Request $request, $productId)
    {
        $request->validate([
            'color_name' => 'required|string',
            'hex_code' => 'required|string', // Kiểm tra dữ liệu đầu vào
            'image_front' => 'required|image|max:2048',
            'image_back' => 'nullable|image|max:2048',
        ]);

        $data = [
            'product_id' => $productId,
            'color_name' => $request->color_name,
            'hex_code'   => $request->hex_code, // <--- PHẢI CÓ DÒNG NÀY ĐỂ LƯU
        ];

        // ... code upload ảnh giữ nguyên ...
        if ($request->hasFile('image_front')) {
            $path = $request->file('image_front')->store('products/colors', 'public');
            $data['image_front'] = 'storage/' . $path;
        }

        if ($request->hasFile('image_back')) {
            $path = $request->file('image_back')->store('products/colors', 'public');
            $data['image_back'] = 'storage/' . $path;
        }

        ProductColor::create($data); // Lúc này $data đã có hex_code và Model đã cho phép

        return back()->with('success', 'Đã thêm màu mới thành công!');
    }
    // === HÀM MỚI: CẬP NHẬT MÀU ===
    public function updateColor(Request $request, $colorId)
    {
        $color = ProductColor::findOrFail($colorId);

        $request->validate([
            'color_name' => 'required|string',
            'hex_code'   => 'required|string', // <--- 1. Thêm validate
            'image_front' => 'nullable|image|max:2048',
            'image_back'  => 'nullable|image|max:2048',
        ]);

        // Cập nhật thông tin chữ
        $color->color_name = $request->color_name;
        $color->hex_code   = $request->hex_code; // <--- 2. Thêm dòng này để lưu mã màu

        // Xử lý ảnh (Giữ nguyên logic cũ)
        if ($request->hasFile('image_front')) {
            // Xóa ảnh cũ
            if ($color->image_front) {
                Storage::disk('public')->delete(str_replace('storage/', '', $color->image_front));
            }
            // Lưu ảnh mới
            $path = $request->file('image_front')->store('products/colors', 'public');
            $color->image_front = 'storage/' . $path;
        }

        if ($request->hasFile('image_back')) {
            if ($color->image_back) {
                Storage::disk('public')->delete(str_replace('storage/', '', $color->image_back));
            }
            $path = $request->file('image_back')->store('products/colors', 'public');
            $color->image_back = 'storage/' . $path;
        }

        $color->save();

        return back()->with('success', 'Đã cập nhật màu sắc.');
    }   

    // === HÀM MỚI: XÓA MÀU ===
    /**
     * Xóa hoàn toàn Sản phẩm (Xóa Product + Xóa Colors + Xóa Ảnh)
     */
    public function destroy($id)
    {
        // Lấy sản phẩm kèm danh sách màu
        $product = Product::with('colors')->findOrFail($id);

        // 1. Duyệt qua từng màu để xóa ảnh trong ổ cứng
        foreach ($product->colors as $color) {
            $this->deleteImages($color);
        }

        // 2. Xóa tất cả record màu trong database
        $product->colors()->delete();

        // 3. Xóa record sản phẩm chính
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm và toàn bộ hình ảnh liên quan.');
    }

    /**
     * Xóa một Màu sắc cụ thể (Xóa Color Record + Xóa Ảnh)
     */
    public function destroyColor($id)
    {
        $color = ProductColor::findOrFail($id);

        // 1. Xóa ảnh trong ổ cứng
        $this->deleteImages($color);

        // 2. Xóa record trong database
        $color->delete();

        return back()->with('success', 'Đã xóa màu sắc thành công.');
    }

    /**
     * Hàm phụ: Giúp xóa file ảnh vật lý trong Storage
     */
    private function deleteImages($color)
    {
        // Đường dẫn trong DB thường là: storage/products/colors/abc.jpg
        // Đường dẫn thực tế Storage cần là: products/colors/abc.jpg

        if ($color->image_front) {
            $pathFront = str_replace('storage/', '', $color->image_front);
            if (Storage::disk('public')->exists($pathFront)) {
                Storage::disk('public')->delete($pathFront);
            }
        }

        if ($color->image_back) {
            $pathBack = str_replace('storage/', '', $color->image_back);
            if (Storage::disk('public')->exists($pathBack)) {
                Storage::disk('public')->delete($pathBack);
            }
        }
    }
}
