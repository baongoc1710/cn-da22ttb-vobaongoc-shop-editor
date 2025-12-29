@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title is-3">Lịch sử đơn hàng</h1>
        
        @if($orders->count() > 0)
            <div class="box">
                <table class="table is-fullwidth is-hoverable">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="has-text-danger font-weight-bold">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                </td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="tag is-warning">Chờ xử lý</span>
                                    @elseif($order->status == 'completed')
                                        <span class="tag is-success">Hoàn thành</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="tag is-danger">Đã hủy</span>
                                    @else
                                        <span class="tag is-info">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="button is-small is-link is-outlined">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="notification is-info is-light">
                Bạn chưa có đơn hàng nào. <a href="{{ route('design') }}">Đặt ngay!</a>
            </div>
        @endif
    </div>
</section>
@endsection