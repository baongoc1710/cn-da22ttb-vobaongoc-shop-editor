@extends('layouts.app')

@section('title', 'Thông tin cá nhân')
@section('content')
    <section class="section has-background-white-ter">
        <div class="container">
            <div class="level">
                <div class="level-left">
                    <h1 class="title is-4">Chi tiết đơn hàng #{{ $order->id }}</h1>
                </div>
                <div class="level-right">
                    <a href="{{ route('admin.orders.index') }}" class="button">
                        <span class="icon"><i class="fas fa-arrow-left"></i></span>
                        <span>Quay lại danh sách</span>
                    </a>
                </div>
            </div>

            <div class="columns is-variable is-6">

                <div class="column is-8">
                    <div class="box h-100">
                        <h3 class="title is-5 mb-4 has-text-link">1. Sản phẩm đã đặt</h3>

                        <table class="table is-fullwidth">
                            <thead>
                                <tr>
                                    <th>Hình ảnh (Thiết kế)</th>
                                    <th>Thông tin</th>
                                    <th class="has-text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    @php $design = $item->savedDesign; @endphp
                                    <tr>
                                        <td width="150">
                                            @if ($design)
                                                <div class="columns is-mobile is-gapless mb-0">
                                                    <div class="column">
                                                        <figure class="image is-64x64" style="border:1px solid #eee">
                                                            <img src="{{ asset($design->front_preview_img) }}">
                                                        </figure>
                                                        <p class="is-size-7 has-text-centered has-text-grey">Trước</p>
                                                    </div>
                                                    @if ($design->back_preview_img)
                                                        <div class="column">
                                                            <figure class="image is-64x64" style="border:1px solid #eee">
                                                                <img src="{{ asset($design->back_preview_img) }}">
                                                            </figure>
                                                            <p class="is-size-7 has-text-centered has-text-grey">Sau</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="tag is-danger">Thiết kế đã xóa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <p><strong>{{ $design->design_name ?? 'Custom Design' }}</strong></p>
                                            <p class="is-size-7">Size: <strong>{{ $item->size }}</strong> | SL:
                                                <strong>{{ $item->quantity }}</strong>
                                            </p>
                                            <p class="is-size-7">Đơn giá: {{ number_format($item->unit_price) }}₫</p>
                                        </td>
                                        <td class="has-text-right has-text-weight-bold">
                                            {{ number_format($item->unit_price * $item->quantity) }}₫
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="has-text-right"><strong>Tổng cộng:</strong></td>
                                    <td class="has-text-right has-text-danger is-size-5 has-text-weight-bold">
                                        {{ number_format($order->total_amount) }}₫
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="box h-100" style="background-color: #fcfcfc; border: 1px solid #eee;">
                        <h3 class="title is-5 mb-4 has-text-primary">2. Cập nhật đơn hàng</h3>

                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="field">
                                <label class="label">Trạng thái đơn hàng</label>
                                <div class="control">
                                    <div class="select is-fullwidth is-primary">
                                        <select name="status">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                1. Chờ xử lý (Pending)
                                            </option>
                                            <option value="confirmed"
                                                {{ $order->status == 'confirmed' ? 'selected' : '' }}>
                                                2. Đã xác nhận (Confirmed)
                                            </option>
                                            <option value="printing" {{ $order->status == 'printing' ? 'selected' : '' }}>
                                                3. Đang in ấn (Printing)
                                            </option>
                                            <option value="packing" {{ $order->status == 'packing' ? 'selected' : '' }}>
                                                4. Đang đóng gói (Packing)
                                            </option>
                                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>
                                                5. Đang giao hàng (Shipping)
                                            </option>
                                            <option value="completed"
                                                {{ $order->status == 'completed' ? 'selected' : '' }}>
                                                6. Hoàn thành (Completed)
                                            </option>
                                            <option value="cancelled"
                                                {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                7. Đã hủy (Cancelled)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="field">
                                <label class="label">Người nhận</label>
                                <div class="control">
                                    <input class="input" type="text" value="{{ $order->customer_name }}" readonly
                                        disabled style="background-color: #f5f5f5;">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Số điện thoại</label>
                                <div class="control">
                                    <input class="input" type="text" name="phone" value="{{ $order->phone }}"
                                        required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Địa chỉ giao hàng</label>
                                <div class="control">
                                    <textarea class="textarea" name="shipping_address" rows="3" required>{{ $order->shipping_address }}</textarea>
                                </div>
                            </div>

                            @if ($order->note)
                                <div class="message is-small is-warning">
                                    <div class="message-body">
                                        <strong>Ghi chú từ khách:</strong> {{ $order->note }}
                                    </div>
                                </div>
                            @endif

                            <div class="field mt-5">
                                <button type="submit" class="button is-primary is-fullwidth">
                                    <span class="icon"><i class="fas fa-save"></i></span>
                                    <span>Lưu thay đổi</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
