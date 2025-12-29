@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<section class="section" style="background-color: #f5f5f5; min-height: 90vh;">
    <div class="container">
        <h1 class="title is-3 has-text-centered mb-6">Hồ sơ cá nhân</h1>

        @if(session('success'))
            <div class="notification is-success is-light">
                <button class="delete"></button>
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="columns is-variable is-8">
            
            <div class="column is-6">
                <div class="box h-100">
                    <h3 class="title is-5 has-text-primary mb-4">
                        <i class="fas fa-user-edit mr-2"></i> Thông tin chung
                    </h3>
                    
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="field">
                            <label class="label">Họ và tên</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control has-icons-left">
                                <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </div>
                            @error('email') <p class="help is-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label class="label">Số điện thoại</label>
                            <div class="control has-icons-left">
                                <input class="input" type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="09xxxxxxx">
                                <span class="icon is-small is-left"><i class="fas fa-phone"></i></span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Địa chỉ mặc định</label>
                            <div class="control has-icons-left">
                                <textarea class="textarea" name="address" rows="3" placeholder="Số nhà, đường...">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="field mt-5">
                            <button class="button is-primary is-fullwidth">
                                <span class="icon"><i class="fas fa-save"></i></span>
                                <span>Lưu thay đổi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="column is-6">
                <div class="box h-100" style="background-color: #fff9f9; border: 1px solid #ffecec;">
                    <h3 class="title is-5 has-text-danger mb-4">
                        <i class="fas fa-lock mr-2"></i> Đổi mật khẩu
                    </h3>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="field">
                            <label class="label">Mật khẩu hiện tại</label>
                            <div class="control has-icons-left">
                                <input class="input @error('current_password') is-danger @enderror" type="password" name="current_password" required>
                                <span class="icon is-small is-left"><i class="fas fa-key"></i></span>
                            </div>
                            @error('current_password') <p class="help is-danger">{{ $message }}</p> @enderror
                        </div>

                        <hr>

                        <div class="field">
                            <label class="label">Mật khẩu mới</label>
                            <div class="control has-icons-left">
                                <input class="input @error('new_password') is-danger @enderror" type="password" name="new_password" required>
                                <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                            </div>
                            @error('new_password') <p class="help is-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label class="label">Nhập lại mật khẩu mới</label>
                            <div class="control has-icons-left">
                                <input class="input" type="password" name="new_password_confirmation" required>
                                <span class="icon is-small is-left"><i class="fas fa-check"></i></span>
                            </div>
                        </div>

                        <div class="field mt-5">
                            <button class="button is-danger is-outlined is-fullwidth">
                                Đổi mật khẩu
                            </button>
                        </div>
                    </form>
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