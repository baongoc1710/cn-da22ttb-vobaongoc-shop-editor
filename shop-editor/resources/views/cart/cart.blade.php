@extends('layouts.app')

@section('title', 'Giỏ Hàng - MyTee Studio')

@section('content')
@php $total = 0; @endphp
<section class="section">
    <div class="container is-fluid"> {{-- Dùng is-fluid để mở rộng ra 2 bên mép màn hình --}}
        <h1 class="title is-3 has-text-centered">Giỏ hàng của bạn</h1>

        @if (session('cart') && count(session('cart')) > 0)
            <div class="columns">
                {{-- MỞ RỘNG COLUMN TỪ is-8 THÀNH is-12 --}}
                <div class="column is-12">
                    <div class="box">
                        <div class="table-container"> {{-- Thêm container để scroll ngang trên điện thoại --}}
                            <table class="table is-fullwidth is-hoverable is-striped">
                                <thead>
                                    <tr>
                                        {{-- Tăng độ rộng cột ảnh --}}
                                        <th style="width: 35%">Thiết kế (Trước - Sau)</th>
                                        <th style="width: 25%">Sản phẩm</th>
                                        <th style="width: 15%">Giá</th>
                                        <th style="width: 10%">Số lượng</th>
                                        <th style="width: 15%" class="has-text-right">Thành tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach (session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity']; @endphp
                                        <tr data-id="{{ $id }}">
                                            <td>
                                                <div style="display: flex; gap: 20px; align-items: flex-end;">
                                                    {{-- ẢNH MẶT TRƯỚC --}}
                                                    <div style="text-align: center;">
                                                        {{-- Tăng kích thước ảnh lên 128x128 --}}
                                                        <figure class="image is-256x256"> 
                                                            <img
                                                                src="{{ $details['image_front'] ? asset($details['image_front']) : 'https://via.placeholder.com/150' }}"
                                                                style="border: 1px solid #ccc; border-radius: 8px; padding: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); object-fit: contain; height: 100%;">
                                                        </figure>
                                                        <span class="tag is-info is-light" style="margin-top: 5px;">Mặt trước</span>
                                                    </div>

                                                    {{-- ẢNH MẶT SAU --}}
                                                    @if (isset($details['image_back']) && $details['image_back'])
                                                        <div style="text-align: center;">
                                                            <figure class="image is-256x256">
                                                                <img src="{{ asset($details['image_back']) }}"
                                                                    style="border: 1px solid #ccc; border-radius: 8px; padding: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); object-fit: contain; height: 100%;">
                                                            </figure>
                                                            <span class="tag is-warning is-light" style="margin-top: 5px;">Mặt sau</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <td style="vertical-align: middle;">
                                                <p class="title is-5">{{ $details['name'] }}</p>
                                                <p class="subtitle is-6 has-text-grey">
                                                    Loại áo: {{ $details['product_name'] }} <br>
                                                    Size: <strong class="has-text-primary">{{ $details['size'] }}</strong>
                                                </p>
                                            </td>

                                            <td style="vertical-align: middle; font-size: 1.1rem;">
                                                {{ number_format($details['price'], 0, ',', '.') }}₫
                                            </td>

                                            <td style="vertical-align: middle;">
                                                <input type="number" value="{{ $details['quantity'] }}" 
                                                       class="input update-cart has-text-centered" 
                                                       style="width: 80px;" min="1" />
                                            </td>

                                            <td class="has-text-right" style="vertical-align: middle;">
                                                <strong class="is-size-5 has-text-danger">
                                                    {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}₫
                                                </strong>
                                            </td>

                                            <td class="has-text-centered" style="vertical-align: middle;">
                                                <button class="button is-danger is-outlined remove-from-cart" title="Xóa">
                                                    <span class="icon"><i class="fas fa-trash-alt"></i></span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="has-text-right is-size-5"><strong>Tổng thanh toán:</strong></td>
                                        <td class="has-text-right">
                                            <strong class="has-text-danger is-size-4">{{ number_format($total, 0, ',', '.') }}₫</strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="level" style="margin-top: 2rem;">
                            <div class="level-left">
                                <a href="{{ route('home') }}" class="button is-light">
                                    <span class="icon"><i class="fas fa-arrow-left"></i></span>
                                    <span>Tiếp tục mua sắm</span>
                                </a>
                            </div>
                            <div class="level-right">
                                <a href="{{ route('checkout') }}" class="button is-success  shadow-md">
                                    <span>Tiến hành đặt hàng</span>
                                    <span class="icon"><i class="fas fa-check"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- GIỎ HÀNG TRỐNG --}}
            <div class="has-text-centered" style="padding: 100px 0; background: #f9f9f9; border-radius: 8px;">
                <span class="icon is-large has-text-grey-lighter" style="font-size: 5rem; margin-bottom: 20px;">
                    <i class="fas fa-shopping-cart"></i>
                </span>
                <p class="title is-4 has-text-grey">Giỏ hàng của bạn đang trống.</p>
                <p class="subtitle is-6">Hãy thiết kế chiếc áo độc đáo cho riêng bạn ngay bây giờ!</p>
                <a href="{{ route('design') }}" class="button is-primary is-medium is-rounded" style="margin-top: 20px">
                    <span class="icon"><i class="fas fa-pencil-alt"></i></span>
                    <span>Bắt đầu thiết kế</span>
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // 1. Cập nhật số lượng
    $(".update-cart").change(function(e) {
        e.preventDefault();
        var ele = $(this);
        var row = ele.parents("tr");

        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}',
                id: row.attr("data-id"),
                quantity: ele.val()
            },
            success: function(response) {
                window.location.reload();
            }
        });
    });

    // 2. Xóa sản phẩm
    $(".remove-from-cart").click(function(e) {
        e.preventDefault();
        var ele = $(this);
        var row = ele.parents("tr");

        if (confirm("Bạn có chắc muốn xóa sản phẩm này không?")) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: row.attr("data-id")
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }
    });
</script>
@endpush