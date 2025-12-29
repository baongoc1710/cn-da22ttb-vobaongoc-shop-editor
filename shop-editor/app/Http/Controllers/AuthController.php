<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 1. Hiển thị form đăng nhập
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Xử lý đăng nhập
    public function login(Request $request)
    {
        // Validate dữ liệu
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Thử đăng nhập (Auth::attempt tự động mã hóa password để so sánh)
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate(); // Tạo session mới để bảo mật

            // Nếu là Admin thì chuyển vào trang admin, khách thì về trang chủ
            return redirect()->intended(route('design'))->with('success', 'Đăng nhập thành công!');
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    // 3. Hiển thị form đăng ký
    public function showRegister()
    {
        return view('auth.register');
    }

    // 4. Xử lý đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // password_confirmation
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.'
        ]);

        // Tạo User mới
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa password
            'role' => 'customer'
        ]);

        // Đăng nhập luôn sau khi đăng ký
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công!');
        }

        return redirect()->route('login');
    }

    // 5. Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đã đăng xuất.');
    }
}