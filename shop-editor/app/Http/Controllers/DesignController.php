<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedDesign;
use App\Models\UserUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DesignController extends Controller
{
    // 1. API: Upload ảnh từ máy tính (User Upload)
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $user = Auth::user();
            // Lưu vào thư mục public/uploads/users/{id}
            $path = $request->file('image')->store('uploads/users/' . ($user ? $user->id : 'guest'), 'public');

            // Nếu đã đăng nhập thì lưu vào DB để dùng lại lần sau
            if ($user) {
                UserUpload::create([
                    'user_id' => $user->id,
                    'file_path' => 'storage/' . $path
                ]);
            }

            return response()->json([
                'status' => 'success',
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Upload thất bại'], 400);
    }

    // 2. API: Lưu thiết kế (Save Design)
    public function saveDesign(Request $request)
    {
        // Validate dữ liệu từ AJAX
        $request->validate([
            'variant_id' => 'required|exists:product_colors,id',
            'front_json' => 'required', // JSON từ Fabric
            // front_img_base64 gửi lên để làm thumbnail
        ]);

        try {
            $userId = Auth::id(); // Có thể null nếu khách vãng lai

            // Xử lý ảnh Thumbnail (Base64 -> File)
            $frontThumb = $this->saveBase64Image($request->front_img_base64, 'thumbnails');
            $backThumb  = $this->saveBase64Image($request->back_img_base64,  'thumbnails'); // Thêm dòng này
            // Tạo bản ghi thiết kế
            $design = SavedDesign::create([
                'user_id' => $userId,
                'product_color_id' => $request->variant_id,
                'design_name' => $request->name ?? 'Thiết kế mới',

                // Lưu JSON (Laravel tự cast sang mảng nhờ Model)
                'front_design_json' => json_decode($request->front_json),
                'back_design_json'  => $request->back_json ? json_decode($request->back_json) : null,

                'front_preview_img' => $frontThumb,
                'back_preview_img'  => $backThumb,
                // 'back_preview_img' => ... (Tương tự nếu có)
            ]);

            return response()->json([
                'status' => 'success',
                'id' => $design->id,
                'message' => 'Đã lưu thiết kế thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 3. API: Load lại thiết kế cũ (Để sửa)
    public function getDesign($id)
    {
        $design = SavedDesign::with('productColor')->findOrFail($id);

        // Kiểm tra quyền sở hữu (Nếu không phải admin và không phải chủ sở hữu)
        if (Auth::id() && $design->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($design);
    }

    // Hàm phụ: Chuyển Base64 thành file ảnh
    private function saveBase64Image($base64Str, $folder)
    {
        if (!$base64Str) return null;

        // Kiểm tra định dạng base64
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Str, $type)) {
            $data = substr($base64Str, strpos($base64Str, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            $data = base64_decode(str_replace(' ', '+', $data));

            // Tạo tên file ngẫu nhiên
            $fileName = $folder . '/' . Str::random(20) . '.' . $type;

            // Lưu vào storage/app/public/thumbnails/...
            Storage::disk('public')->put($fileName, $data);

            // QUAN TRỌNG: Trả về đường dẫn có chữ 'storage/' ở đầu
            return 'storage/' . $fileName;
        }
        return null;
    }

    // 1. Hiển thị danh sách thiết kế của User
    public function myCollection()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem bộ sưu tập.');
        }

        // Lấy thiết kế của user, mới nhất xếp trước
        $designs = \App\Models\SavedDesign::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12); // Phân trang 12 mẫu mỗi trang

        return view('collection', compact('designs'));
    }

    // 2. Xóa thiết kế
    public function destroy($id)
    {
        $design = \App\Models\SavedDesign::where('user_id', Auth::id())->findOrFail($id);

        // Xóa file ảnh trong storage nếu cần (tùy chọn)
        // \Storage::disk('public')->delete(str_replace('storage/', '', $design->front_preview_img));

        $design->delete();

        return back()->with('success', 'Đã xóa mẫu thiết kế.');
    }
}
