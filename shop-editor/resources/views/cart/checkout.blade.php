@extends('layouts.app')

@section('title', 'Thanh Toán - MyTee Studio')

@section('content')
<section class="section" style="background-color: #f5f5f5; min-height: 100vh;">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-10">
                
                <h1 class="title is-3 has-text-centered" style="margin-bottom: 30px;">Xác nhận đặt hàng</h1>

                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf {{-- Bắt buộc phải có để bảo mật form --}}
                    
                    <div class="columns">
                        <div class="column is-7">
                            <div class="box" style="padding: 30px;">
                                <h3 class="title is-5 mb-4"><i class="fas fa-map-marker-alt mr-2"></i> Thông tin giao hàng</h3>
                                
                                {{-- Họ tên --}}
                                <div class="field">
                                    <label class="label">Họ và tên người nhận</label>
                                    <div class="control has-icons-left">
                                        <input class="input @error('customer_name') is-danger @enderror" type="text" name="customer_name" placeholder="Ví dụ: Nguyễn Văn A" value="{{ old('customer_name', Auth::user()->name ?? '') }}">
                                        <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                                    </div>
                                    @error('customer_name') <p class="help is-danger">{{ $message }}</p> @enderror
                                </div>

                                {{-- Số điện thoại --}}
                                <div class="field">
                                    <label class="label">Số điện thoại</label>
                                    <div class="control has-icons-left">
                                        <input class="input @error('phone') is-danger @enderror" type="tel" name="phone" placeholder="Ví dụ: 0909123456" value="{{ old('phone', Auth::user()->phone ?? '') }}">
                                        <span class="icon is-small is-left"><i class="fas fa-phone"></i></span>
                                    </div>
                                    @error('phone') <p class="help is-danger">{{ $message }}</p> @enderror
                                </div>

                                {{-- Địa chỉ --}}
                                <div class="field">
                                    <label class="label">Địa chỉ nhận hàng</label>
                                    <div class="control has-icons-left">
                                        <textarea class="textarea @error('address') is-danger @enderror" name="address" rows="3" placeholder="Số nhà, tên đường, phường/xã...">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                                    </div>
                                    @error('address') <p class="help is-danger">{{ $message }}</p> @enderror
                                </div>

                                {{-- Ghi chú --}}
                                <div class="field">
                                    <label class="label">Ghi chú (Tùy chọn)</label>
                                    <div class="control">
                                        <textarea class="textarea" name="note" rows="2" placeholder="Ví dụ: Giao hàng giờ hành chính...">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Phương thức thanh toán --}}
                            <div class="box" style="padding: 30px; margin-top: 20px;">
                                <h3 class="title is-5 mb-4"><i class="fas fa-credit-card mr-2"></i> Phương thức thanh toán</h3>
                                
                                <div class="control">
                                    <label class="radio" style="display: block; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                        <input type="radio" name="payment_method" value="COD" checked>
                                        <span style="margin-left: 10px; font-weight: bold;">Thanh toán khi nhận hàng (COD)</span>
                                        <p class="help" style="margin-left: 25px;">Bạn sẽ thanh toán tiền mặt cho shipper khi nhận áo.</p>
                                    </label>
                                    
                                    <label class="radio" style="display: block; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                        <input type="radio" name="payment_method" value="Banking">
                                        <span style="margin-left: 10px; font-weight: bold;">Chuyển khoản Ngân hàng / QR Code</span>
                                        <p class="help" style="margin-left: 25px;">Quét mã QR để thanh toán nhanh chóng.</p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="column is-5">
                            <div class="box" style="padding: 30px; background-color: #fff; border-top: 4px solid #48c774; position: sticky; top: 20px;">
                                <h3 class="title is-5 mb-4">Đơn hàng của bạn</h3>
                                
                                @php $total = 0; @endphp
                                <div class="content">
                                    @if(session('cart'))
                                        <ul style="list-style: none; margin: 0; padding: 0;">
                                            @foreach(session('cart') as $id => $details)
                                                @php $subtotal = $details['price'] * $details['quantity']; $total += $subtotal; @endphp
                                                <li style="border-bottom: 1px dashed #eee; padding-bottom: 10px; margin-bottom: 10px; display: flex; gap: 10px;">
                                                    <figure class="image is-48x48">
                                                        <img src="{{ $details['image_front'] ? asset($details['image_front']) : 'https://via.placeholder.com/64' }}" style="border-radius: 4px; border: 1px solid #ddd;">
                                                    </figure>
                                                    <div style="flex: 1;">
                                                        <strong>{{ $details['name'] }}</strong><br>
                                                        <small class="has-text-grey">{{ $details['product_name'] }} (Size: {{ $details['size'] }})</small><br>
                                                        <small>x{{ $details['quantity'] }}</small>
                                                    </div>
                                                    <div class="has-text-right">
                                                        {{ number_format($subtotal, 0, ',', '.') }}₫
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                                <hr>

                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <span class="subtitle is-6">Tạm tính:</span>
                                    </div>
                                    <div class="level-right">
                                        <strong>{{ number_format($total, 0, ',', '.') }}₫</strong>
                                    </div>
                                </div>
                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <span class="subtitle is-6">Phí vận chuyển:</span>
                                    </div>
                                    <div class="level-right">
                                        <strong>Miễn phí</strong>
                                    </div>
                                </div>

                                <div class="level is-mobile mt-4" style="border-top: 2px solid #eee; padding-top: 15px;">
                                    <div class="level-left">
                                        <span class="title is-5">Tổng cộng:</span>
                                    </div>
                                    <div class="level-right">
                                        <span class="title is-4 has-text-danger">{{ number_format($total, 0, ',', '.') }}₫</span>
                                    </div>
                                </div>

                                <div class="field mt-5">
                                    <div class="control">
                                        <button type="submit" class="button is-success is-fullwidth shadow-md" style="font-weight: bold;">
                                            <span>ĐẶT HÀNG NGAY</span>
                                            <span class="icon is-small"><i class="fas fa-check"></i></span>
                                        </button>
                                    </div>
                                    <p class="help has-text-centered mt-2">Bấm đặt hàng đồng nghĩa với việc bạn đồng ý với chính sách của chúng tôi.</p>
                                </div>
                                
                                <div class="has-text-centered mt-4">
                                    <a href="{{ route('cart.index') }}" class="button is-text is-small">
                                        <span class="icon"><i class="fas fa-arrow-left"></i></span>
                                        <span>Quay lại giỏ hàng</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection