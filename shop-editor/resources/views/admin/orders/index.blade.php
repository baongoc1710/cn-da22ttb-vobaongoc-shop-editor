@extends('layouts.app')

@section('title', 'Thông tin cá nhân')
@section('content')
    <section class="section has-background-white-ter">
        <div class="container">
            <div class="level">
                <div class="level-left">
                    <h1 class="title is-4">Quản lý đơn hàng</h1>
                </div>
            </div>
            <div class="box has-background-white-ter">
                <form action="{{ route('admin.orders.print') }}" method="GET" target="_blank">
                    <div class="columns is-vcentered">
                        <div class="column is-narrow">
                            <label class="label">In phiếu gửi hàng (Packing):</label>
                        </div>

                        <div class="column is-narrow">
                            <div class="field">
                                <div class="control">
                                    <input class="input" type="date" name="from_date" required
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <div class="column is-narrow has-text-centered">
                            <span>đến</span>
                        </div>

                        <div class="column is-narrow">
                            <div class="field">
                                <div class="control">
                                    <input class="input" type="date" name="to_date" required
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <button type="submit" class="button is-dark">
                                <span class="icon"><i class="fas fa-print"></i></span>
                                <span>Xuất phiếu in</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box">
                <table class="table is-fullwidth is-hoverable is-striped">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th class="has-text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <p class="has-text-weight-bold">{{ $order->customer_name }}</p>
                                    <small class="has-text-grey">{{ $order->phone }}</small>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="has-text-danger has-text-weight-bold">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                </td>
                                <td>
                                    @php
                                        // Map màu sắc cho đẹp
                                        $colors = [
                                            'pending' => 'is-warning', // Vàng
                                            'confirmed' => 'is-info', // Xanh dương
                                            'printing' => 'is-link', // Tím than (Đang in)
                                            'packing' => 'is-primary', // Xanh ngọc (Đóng gói)
                                            'shipping' => 'is-dark', // Đen/Xám (Đang giao)
                                            'completed' => 'is-success', // Xanh lá
                                            'cancelled' => 'is-danger', // Đỏ
                                        ];

                                        // Map tên hiển thị tiếng Việt
                                        $statusVi = [
                                            'pending' => 'Chờ xử lý',
                                            'confirmed' => 'Đã xác nhận',
                                            'printing' => 'Đang in ấn',
                                            'packing' => 'Đang đóng gói',
                                            'shipping' => 'Đang giao hàng',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                        ];
                                    @endphp

                                    <span class="tag {{ $colors[$order->status] ?? 'is-light' }}">
                                        {{ $statusVi[$order->status] ?? ucfirst($order->status) }}
                                    </span>

                                <td class="has-text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="button is-small is-primary is-outlined">
                                        <span class="icon"><i class="fas fa-eye"></i></span>
                                        <span>Chi tiết & Sửa</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $orders->links('pagination.bulma') }}
                </div>
            </div>
        </div>
    </section>

@endsection
