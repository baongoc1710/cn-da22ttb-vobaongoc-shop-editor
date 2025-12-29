@extends('layouts.app')

@section('title', 'Đăng Nhập - MyTee Studio')

@section('content')
<section class="hero is-fullheight-with-navbar" style="background-color: #f5f5f5;">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-5-tablet is-4-desktop">
                    
                    <div class="box shadow-lg">
                        <div class="has-text-centered mb-5">
                            <span class="icon is-large has-text-primary">
                                <i class="fas fa-tshirt fa-3x"></i>
                            </span>
                            <h3 class="title is-4 mt-2">Đăng Nhập</h3>
                            <p class="subtitle is-6">Chào mừng trở lại với MyTee Studio</p>
                        </div>

                        {{-- Thông báo lỗi chung --}}
                        @if ($errors->has('email'))
                            <div class="notification is-danger is-light">
                                <button class="delete"></button>
                                {{ $errors->first('email') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="email" name="email" placeholder="example@gmail.com" value="{{ old('email') }}" required autofocus>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Mật khẩu</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="password" name="password" placeholder="********" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                            <div class="field mt-5">
                                <button class="button is-primary is-fullwidth is-medium">
                                    Đăng Nhập
                                </button>
                            </div>
                        </form>

                        <hr>
                        <div class="has-text-centered">
                            <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            const $notification = $delete.parentNode;
            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });
    });
</script>
@endsection