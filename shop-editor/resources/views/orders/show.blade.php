@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<section class="section" style="background-color: #f4f6f8; min-height: 100vh;">
    <div class="container">
        
        <div class="level mb-5">
            <div class="level-left">
                <div>
                    <h1 class="title is-3 has-text-dark mb-2">
                        Đơn hàng #{{ $order->id }}
                    </h1>
                    <div class="tags are-medium">
                        @if($order->status == 'pending')
                            <span class="tag is-warning">Chờ xử lý</span>
                        @elseif($order->status == 'completed')
                            <span class="tag is-success">Hoàn thành</span>
                        @elseif($order->status == 'cancelled')
                            <span class="tag is-danger">Đã hủy</span>
                        @else
                            <span class="tag is-link">{{ ucfirst($order->status) }}</span>
                        @endif
                        <span class="tag is-light">{{ $order->created_at->format('d/m/Y - H:i') }}</span>
                    </div>
                </div>
            </div>
            <div class="level-right">
                <a href="{{ route('orders.index') }}" class="button is-white shadow-sm font-weight-bold">
                    <span class="icon"><i class="fas fa-arrow-left"></i></span>
                    <span>Danh sách đơn</span>
                </a>
            </div>
        </div>

        <div class="columns is-variable is-6">
            
            <div class="column is-8">
                <div class="box shadow-sm" style="border-top: 4px solid #3273dc;">
                    <h3 class="title is-5 mb-5 has-text-dark">
                        <i class="fas fa-tshirt mr-2 has-text-primary"></i> Sản phẩm đã đặt
                    </h3>
                    
                    <div class="content">
                        @foreach($order->items as $item)
                            @php 
                                $design = $item->savedDesign;
                            @endphp
                            
                            <div class="card mb-4" style="border: 1px solid #eee; box-shadow: none;">
                                <div class="card-content">
                                    <div class="columns">
                                        
                                        <div class="column is-7">
                                            @if($design)
                                                <div class="columns is-mobile is-variable is-2">
                                                    {{-- MẶT TRƯỚC --}}
                                                    <div class="column is-6 has-text-centered">
                                                        <figure class="image is-square" style="background: #fff; border: 1px solid #ddd; border-radius: 6px; overflow: hidden;">
                                                            <img src="{{ $design->front_preview_img ? asset($design->front_preview_img) : 'https://via.placeholder.com/300?text=No+Image' }}" 
                                                                 style="object-fit: contain;">
                                                        </figure>
                                                        <span class="tag is-info is-light mt-2" style="width: 100%">Mặt trước</span>
                                                    </div>

                                                    {{-- MẶT SAU (KIỂM TRA NẾU CÓ) --}}
                                                    @if(!empty($design->back_preview_img))
                                                        <div class="column is-6 has-text-centered">
                                                            <figure class="image is-square" style="background: #fff; border: 1px solid #ddd; border-radius: 6px; overflow: hidden;">
                                                                <img src="{{ asset($design->back_preview_img) }}" 
                                                                     style="object-fit: contain;">
                                                            </figure>
                                                            <span class="tag is-warning is-light mt-2" style="width: 100%">Mặt sau</span>
                                                        </div>
                                                    @else
                                                        {{-- Nếu không có mặt sau thì hiện placeholder mờ để cân đối layout --}}
                                                        <div class="column is-6 has-text-centered">
                                                            <div style="height: 100%; background: #f9f9f9; border: 1px dashed #ddd; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #ccc;">
                                                                Không có mặt sau
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="notification is-danger is-light">
                                                    Thiết kế gốc đã bị xóa.
                                                </div>
                                            @endif
                                        </div>

                                        <div class="column is-5">
                                            <h4 class="title is-5 mb-2">{{ $design->design_name ?? 'Thiết kế tùy chỉnh' }}</h4>
                                            
                                            <table class="table is-narrow is-fullwidth" style="background: transparent;">
                                                <tr>
                                                    <td class="has-text-grey">Size:</td>
                                                    <td><strong>{{ $item->size }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="has-text-grey">Đơn giá:</td>
                                                    <td>{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                                                </tr>
                                                <tr>
                                                    <td class="has-text-grey">Số lượng:</td>
                                                    <td><strong>x{{ $item->quantity }}</strong></td>
                                                </tr>
                                                <tr style="border-top: 2px solid #ddd;">
                                                    <td class="pt-3">Thành tiền:</td>
                                                    <td class="pt-3"><strong class="has-text-danger is-size-5">{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}₫</strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="columns mt-5 pt-4" style="border-top: 2px dashed #dbdbdb;">
                        <div class="column is-6 is-offset-6">
                            <div class="level is-mobile mb-2">
                                <div class="level-left">Tạm tính</div>
                                <div class="level-right">{{ number_format($order->total_amount, 0, ',', '.') }}₫</div>
                            </div>
                            <div class="level is-mobile mb-2">
                                <div class="level-left">Phí vận chuyển</div>
                                <div class="level-right text-success">0₫</div>
                            </div>
                            <div class="level is-mobile">
                                <div class="level-left"><strong class="title is-5">Tổng cộng</strong></div>
                                <div class="level-right"><strong class="title is-4 has-text-primary">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-4">
                <div class="box shadow-sm" style="border-top: 4px solid #23d160; position: sticky; top: 20px;">
                    <h3 class="title is-5 mb-4 has-text-dark">
                        <i class="fas fa-address-card mr-2 has-text-success"></i> Thông tin nhận hàng
                    </h3>
                    
                    <div class="media mb-3">
                        <div class="media-left">
                            <span class="icon has-text-grey-light"><i class="fas fa-user fa-lg"></i></span>
                        </div>
                        <div class="media-content">
                            <p class="heading">Người nhận</p>
                            <p class="title is-6">{{ $order->customer_name }}</p>
                        </div>
                    </div>

                    <div class="media mb-3">
                        <div class="media-left">
                            <span class="icon has-text-grey-light"><i class="fas fa-phone fa-lg"></i></span>
                        </div>
                        <div class="media-content">
                            <p class="heading">Điện thoại</p>
                            <p class="title is-6">{{ $order->phone }}</p>
                        </div>
                    </div>

                    <div class="media mb-3">
                        <div class="media-left">
                            <span class="icon has-text-grey-light"><i class="fas fa-map-marker-alt fa-lg"></i></span>
                        </div>
                        <div class="media-content">
                            <p class="heading">Địa chỉ giao hàng</p>
                            <p class="subtitle is-6">{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    <div class="media mb-3">
                        <div class="media-left">
                            <span class="icon has-text-grey-light"><i class="fas fa-credit-card fa-lg"></i></span>
                        </div>
                        <div class="media-content">
                            <p class="heading">Thanh toán</p>
                            <p class="subtitle is-6">{{ $order->payment_method }}</p>
                        </div>
                    </div>

                    @if($order->note)
                    <div class="message is-warning is-small mt-4">
                        <div class="message-body">
                            <strong>Ghi chú:</strong> {{ $order->note }}
                        </div>
                    </div>
                    @endif
                    
                    <hr>
                    @if($order->status == 'pending')
                        <div class="mb-3">
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Hành động này không thể hoàn tác.');">
                                @csrf
                                <button type="submit" class="button is-danger is-fullwidth is-outlined">
                                    <span class="icon"><i class="fas fa-times-circle"></i></span>
                                    <span>Hủy đơn hàng</span>
                                </button>
                            </form>
                            <p class="help is-danger has-text-centered mt-1">
                                *Chỉ có thể hủy khi đơn chưa được xử lý
                            </p>
                        </div>
                    @endif
                    <div class="has-text-centered">
                        <button class="button is-fullwidth is-outlined" disabled>
                            <span class="icon"><i class="fas fa-print"></i></span>
                            <span>In hóa đơn</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .shadow-sm { box-shadow: 0 0.5em 1em -0.125em rgba(10, 10, 10, 0.1), 0 0px 0 1px rgba(10, 10, 10, 0.02); }
    .card-content { padding: 1.5rem; }
</style>
@endsection