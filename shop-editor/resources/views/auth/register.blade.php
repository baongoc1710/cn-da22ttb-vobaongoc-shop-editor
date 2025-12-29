@extends('layouts.app')

@section('title', 'Đăng Ký - MyTee Studio')

@section('content')
<section class="hero is-fullheight-with-navbar" style="background-color: #f5f5f5;">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-5-tablet is-4-desktop">
                    
                    <div class="box shadow-lg">
                        <div class="has-text-centered mb-5">
                            <h3 class="title is-4">Tạo Tài Khoản</h3>
                            <p class="subtitle is-6">Tham gia cộng đồng thiết kế áo</p>
                        </div>

                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf

                            <div class="field">
                                <label class="label">Họ và tên</label>
                                <div class="control has-icons-left">
                                    <input class="input @error('name') is-danger @enderror" type="text" name="name" value="{{ old('name') }}" placeholder="Nguyễn Văn A" required>
                                    <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                                </div>
                                @error('name') <p class="help is-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control has-icons-left">
                                    <input class="input @error('email') is-danger @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
                                    <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                                </div>
                                @error('email') <p class="help is-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="field">
                                <label class="label">Mật khẩu</label>
                                <div class="control has-icons-left">
                                    <input class="input @error('password') is-danger @enderror" type="password" name="password" placeholder="Tối thiểu 6 ký tự" required>
                                    <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                                </div>
                                @error('password') <p class="help is-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="field">
                                <label class="label">Nhập lại mật khẩu</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
                                    <span class="icon is-small is-left"><i class="fas fa-check"></i></span>
                                </div>
                            </div>

                            <div class="field mt-5">
                                <button class="button is-primary is-fullwidth is-medium">Đăng Ký</button>
                            </div>
                        </form>

                        <hr>
                        <div class="has-text-centered">
                            <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection