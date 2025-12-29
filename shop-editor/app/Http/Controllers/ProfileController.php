<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // 1. Hiển thị form
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // 2. Cập nhật thông tin chung
    public function update(Request $request)
    {
        // CÁCH SỬA: Lấy user trực tiếp từ Model thông qua ID
        $user = User::find(Auth::id());

        // Kiểm tra nếu không tìm thấy user (trường hợp hiếm)
        if (!$user) {
            return back()->with('error', 'Không tìm thấy tài khoản.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric|digits_between:9,11',
            'address' => 'nullable|string|max:255',
            // Sửa lại cú pháp unique cho chuẩn Laravel mới
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'Tên không được để trống.',
            'phone.numeric' => 'Số điện thoại phải là số.',
            'email.unique' => 'Email này đã được sử dụng bởi người khác.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        $user->save(); // Lúc này hàm save() chắc chắn hoạt động

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // 3. Đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        // 2. SỬA ĐOẠN NÀY: Lấy User thông qua ID để đảm bảo là Model xịn, có hàm save()
        $user = User::find(Auth::id());

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);

        // 3. Bây giờ hàm save() sẽ hoạt động 100%
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
